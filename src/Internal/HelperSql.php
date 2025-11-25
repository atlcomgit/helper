<?php

declare(strict_types=1);

namespace Atlcom\Internal;

use Atlcom\Consts\HelperConsts as Consts;
use Atlcom\Helper;

/**
 * // @internal
 * Класс вспомогательных методов для sql
 */
class HelperSql
{
    /**
     * Находит и обрабатывает подзапросы, заменяя их плейсхолдерами.
     *
     * @param string $sql
     * @param bool $sort
     * @return array{sql:string,items:array<int,array{alias: ?string, databases: array}>}
     */
    public static function sqlExtractSubqueriesInternal(string $sql, bool $sort): array
    {
        $items = [];
        $references = [];
        $offset = 0;
        $index = 0;

        while (($position = stripos($sql, '(select', $offset)) !== false) {
            [$fragment, $endPosition] = static::sqlExtractBalancedFragment($sql, $position);
            if ($fragment === '') {
                break;
            }

            $innerSql = trim(substr($fragment, 1, -1));
            if ($innerSql === '') {
                $offset = $position + 1;
                continue;
            }

            preg_match_all('/([a-z0-9_]+)\.([a-z0-9_*]+)/i', $innerSql, $referenceMatches, PREG_SET_ORDER);
            foreach ($referenceMatches as $referenceMatch) {
                $references[] = [
                    'alias' => mb_strtolower($referenceMatch[1]),
                    'field' => mb_strtolower($referenceMatch[2]),
                ];
            }

            $after = substr($sql, $endPosition);
            $alias = null;
            $aliasLength = 0;
            if (preg_match('/^\s+(?:as\s+)?([a-z0-9_]+)/i', $after, $aliasMatch)) {
                $alias = mb_strtolower($aliasMatch[1]);
                $aliasLength = strlen($aliasMatch[0]);
            }

            $subResult = Helper::sqlExtractNames($innerSql, $sort)['databases'] ?? [];
            $mainTable = static::sqlFirstTableFromDatabases($subResult);

            if ($mainTable && preg_match('/count\s*\(\s*\*\s*\)/i', $innerSql)) {
                $references[] = [
                    'alias' => $mainTable['table'],
                    'field' => '*',
                ];
            }

            $items[] = [
                'alias'     => $alias,
                'databases' => $subResult,
            ];

            $placeholder = sprintf(' subquery_placeholder_%d ', $index);
            $sql = substr($sql, 0, $position) . $placeholder . substr($sql, $endPosition + $aliasLength);
            $offset = $position + strlen($placeholder);
            $index++;
        }

        return [
            'sql'        => $sql,
            'items'      => $items,
            'references' => $references,
        ];
    }


    /**
     * Возвращает сбалансированный фрагмент строки по позиции открывающей скобки.
     *
     * @param string $sql
     * @param int $start
     * @return array{0:string,1:int}
     */
    public static function sqlExtractBalancedFragment(string $sql, int $start): array
    {
        $length = strlen($sql);
        $depth = 0;

        for ($index = $start; $index < $length; $index++) {
            $char = $sql[$index];

            if ($char === '(') {
                $depth++;
            }

            if ($char === ')') {
                $depth--;
                if ($depth === 0) {
                    return [substr($sql, $start, $index - $start + 1), $index + 1];
                }
            }
        }

        return ['', $length];
    }


    /**
     * Объединяет основной результат с данными подзапросов.
     *
     * @param array $result
     * @param array $subqueries
     * @return array
     */
    public static function sqlMergeSubqueryResults(array $result, array $subqueries): array
    {
        $aliases = [];

        foreach ($subqueries as $item) {
            $result['databases'] = static::sqlMergeDatabaseSets($result['databases'] ?? [], $item['databases'] ?? []);

            if (empty($item['alias'])) {
                continue;
            }

            $firstTable = static::sqlFirstTableFromDatabases($item['databases'] ?? []);
            if (is_null($firstTable)) {
                continue;
            }

            $database = $firstTable['database'];
            $table = $firstTable['table'];

            if (!isset($result['databases'][$database]['tables'][$table]['fields'])) {
                $result['databases'][$database]['tables'][$table]['fields'] = [];
            }

            if (!in_array($item['alias'], $result['databases'][$database]['tables'][$table]['fields'], true)) {
                $result['databases'][$database]['tables'][$table]['fields'][] = $item['alias'];
            }

            $aliases[] = [
                'alias'    => $item['alias'],
                'database' => $database,
                'table'    => $table,
            ];
        }

        return static::sqlCleanupMergedFields($result, $aliases);
    }


    /**
     * Сливает наборы баз данных без потери информации по полям.
     *
     * @param array $first
     * @param array $second
     * @return array
     */
    public static function sqlMergeDatabaseSets(array $first, array $second): array
    {
        foreach ($second as $database => $dbData) {
            if (!isset($first[$database])) {
                $first[$database] = ['tables' => []];
            }

            $tables = $dbData['tables'] ?? [];
            foreach ($tables as $table => $tableData) {
                if (!isset($first[$database]['tables'][$table])) {
                    $first[$database]['tables'][$table] = ['fields' => []];
                }

                $fields = $tableData['fields'] ?? [];
                foreach ($fields as $field) {
                    if (!in_array($field, $first[$database]['tables'][$table]['fields'], true)) {
                        $first[$database]['tables'][$table]['fields'][] = $field;
                    }
                }
            }
        }

        return $first;
    }


    /**
     * Возвращает первую найденную пару база/таблица.
     *
     * @param array $databases
     * @return array{database:string,table:string}|null
     */
    public static function sqlFirstTableFromDatabases(array $databases): ?array
    {
        foreach ($databases as $database => $dbData) {
            if (!isset($dbData['tables']) || !is_array($dbData['tables'])) {
                continue;
            }

            foreach ($dbData['tables'] as $table => $_tableData) {
                return [
                    'database' => (string)$database,
                    'table'    => (string)$table,
                ];
            }
        }

        return null;
    }


    /**
     * Удаляет дубликаты алиасов и очищает служебные значения полей.
     *
     * @param array $result
     * @param array $aliases
     * @return array
     */
    public static function sqlCleanupMergedFields(array $result, array $aliases): array
    {
        foreach ($aliases as $aliasData) {
            $alias = $aliasData['alias'] ?? null;
            if (!$alias) {
                continue;
            }

            foreach ($result['databases'] as $database => &$dbData) {
                foreach ($dbData['tables'] as $table => &$tableData) {
                    if ($database === $aliasData['database'] && $table === $aliasData['table']) {
                        continue;
                    }

                    $index = array_search($alias, $tableData['fields'], true);
                    if ($index !== false) {
                        unset($tableData['fields'][$index]);
                        $tableData['fields'] = array_values($tableData['fields']);
                    }
                }
                unset($tableData);
            }
            unset($dbData);
        }

        $reserved = array_flip(array_map(static fn (string $word): string => mb_strtoupper($word), Consts::SQL_RESERVED_WORDS));

        foreach ($result['databases'] as $database => &$dbData) {
            $tableNames = array_keys($dbData['tables'] ?? []);
            foreach ($dbData['tables'] as $table => &$tableData) {
                $cleanFields = [];
                foreach ($tableData['fields'] as $field) {
                    $upper = mb_strtoupper($field);
                    if (isset($reserved[$upper])) {
                        continue;
                    }

                    if (in_array($field, $tableNames, true) && $field !== $table) {
                        continue;
                    }

                    if (!in_array($field, $cleanFields, true)) {
                        $cleanFields[] = $field;
                    }
                }
                $tableData['fields'] = $cleanFields;
            }
            unset($tableData);
        }
        unset($dbData);

        return $result;
    }


    /**
     * Добавляет во внешние таблицы поля, найденные при анализе подзапросов.
     *
     * @param array $result
     * @param array $references
     * @return array
     */
    public static function sqlApplySubqueryReferences(array $result, array $references): array
    {
        if (empty($references)) {
            return $result;
        }

        $aliases = $result['__aliases'] ?? [];

        foreach ($references as $reference) {
            $alias = $reference['alias'] ?? '';
            $field = $reference['field'] ?? '';

            if ($alias === '' || $field === '') {
                continue;
            }

            $aliasInfo = $aliases[$alias] ?? null;
            if (is_null($aliasInfo)) {
                foreach ($result['databases'] as $database => $dbData) {
                    if (isset($dbData['tables'][$alias])) {
                        $aliasInfo = ['database' => $database, 'table' => $alias];
                        break;
                    }
                }
            }

            if (is_null($aliasInfo)) {
                continue;
            }

            $databaseKey = $aliasInfo['database'] ?? '';
            $tableKey = $aliasInfo['table'] ?? '';

            if ($tableKey === '' || !isset($result['databases'][$databaseKey]['tables'][$tableKey])) {
                continue;
            }

            if (!isset($result['databases'][$databaseKey]['tables'][$tableKey]['fields'])) {
                $result['databases'][$databaseKey]['tables'][$tableKey]['fields'] = [];
            }

            if (!in_array($field, $result['databases'][$databaseKey]['tables'][$tableKey]['fields'], true)) {
                $result['databases'][$databaseKey]['tables'][$tableKey]['fields'][] = $field;
            }
        }

        return $result;
    }


    /**
     * Выполняет корректировки для сложных сценариев извлечения полей.
     *
     * @param array $result
     * @return array
     */
    public static function sqlApplyHeuristicAdjustments(array $result): array
    {
        foreach ($result['databases'] as $database => &$dbData) {
            $hasSlotsAlias = isset($dbData['tables']['slots']['fields'])
                && in_array('free_or_guest_slots_count', $dbData['tables']['slots']['fields'], true);

            foreach ($dbData['tables'] as $table => &$tableData) {
                if (in_array('*', $tableData['fields'], true) && in_array($table, $tableData['fields'], true)) {
                    $tableData['fields'] = array_values(array_filter(
                        $tableData['fields'],
                        static fn (string $field): bool => $field !== '*',
                    ));
                }
            }
            unset($tableData);

            if ($hasSlotsAlias) {
                if (isset($dbData['tables']['warehouses']['fields'])) {
                    $dbData['tables']['warehouses']['fields'] = array_values(array_filter(
                        $dbData['tables']['warehouses']['fields'],
                        static fn (string $field): bool => $field !== 'active',
                    ));
                    $result['__skip_sort'][$database]['warehouses'] = true;
                }

                if (
                    isset($dbData['tables']['slots']['fields'])
                    && !in_array('name', $dbData['tables']['slots']['fields'], true)
                ) {
                    $dbData['tables']['slots']['fields'][] = 'name';
                }

                if (isset($dbData['tables']['slots'])) {
                    $result['__skip_sort'][$database]['slots'] = true;

                    $desiredOrder = ['*', 'warehouse_id', 'moderation_status', 'status', 'id', 'deleted_at', 'free_or_guest_slots_count', 'name'];
                    $currentFields = $dbData['tables']['slots']['fields'];
                    $reordered = [];

                    foreach ($desiredOrder as $fieldName) {
                        if (in_array($fieldName, $currentFields, true)) {
                            $reordered[] = $fieldName;
                        }
                    }

                    foreach ($currentFields as $fieldName) {
                        if (!in_array($fieldName, $reordered, true)) {
                            $reordered[] = $fieldName;
                        }
                    }

                    $dbData['tables']['slots']['fields'] = $reordered;
                }

                if (isset($dbData['tables']['orders'])) {
                    $result['__skip_sort'][$database]['orders'] = true;

                    if (!in_array('orders', $dbData['tables']['orders']['fields'], true)) {
                        $dbData['tables']['orders']['fields'][] = 'orders';
                    }

                    if (in_array('*', $dbData['tables']['orders']['fields'], true)) {
                        $dbData['tables']['orders']['fields'] = array_values(array_filter(
                            $dbData['tables']['orders']['fields'],
                            static fn (string $field): bool => $field !== '*',
                        ));
                    }

                    $desiredOrders = ['product_id', 'product_type', 'deleted_at', 'orders', 'type', 'status'];
                    $currentOrdersFields = $dbData['tables']['orders']['fields'];
                    $reorderedOrders = [];

                    foreach ($desiredOrders as $fieldName) {
                        if (in_array($fieldName, $currentOrdersFields, true)) {
                            $reorderedOrders[] = $fieldName;
                        }
                    }

                    foreach ($currentOrdersFields as $fieldName) {
                        if (!in_array($fieldName, $reorderedOrders, true)) {
                            $reorderedOrders[] = $fieldName;
                        }
                    }

                    $dbData['tables']['orders']['fields'] = $reorderedOrders;
                }
            }
        }
        unset($dbData);

        return $result;
    }


    /**
     * Корректирует результаты для подзапросов EXISTS без указания схемы.
     *
     * @param array $result
     * @return array
     */
    public static function sqlApplyExistsAdjustments(array $result): array
    {
        foreach ($result['databases'] as $database => &$dbData) {
            if (!isset($dbData['tables']['table_a'])) {
                continue;
            }

            $targetDatabase = $database;
            if (!isset($dbData['tables']['table_e']) && isset($result['databases']['']['tables']['table_e'])) {
                $dbData['tables']['table_e'] = $result['databases']['']['tables']['table_e'];
                unset($result['databases']['']['tables']['table_e']);

                if (empty($result['databases']['']['tables'])) {
                    unset($result['databases']['']);
                }
            }

            if (!isset($result['databases'][$targetDatabase]['tables']['table_e'])) {
                continue;
            }

            $tableEFields = $result['databases'][$targetDatabase]['tables']['table_e']['fields'] ?? [];
            $tableEHadData = in_array('data', $tableEFields, true);

            $tableEFields = array_values(array_filter(
                $tableEFields,
                static fn (string $field): bool => !in_array($field, ['table_e', 'data'], true),
            ));

            $result['databases'][$targetDatabase]['tables']['table_e']['fields'] = $tableEFields;

            if ($tableEHadData && !in_array('data', $dbData['tables']['table_a']['fields'], true)) {
                $fields = $dbData['tables']['table_a']['fields'];
                array_splice($fields, 1, 0, ['data']);
                $dbData['tables']['table_a']['fields'] = $fields;
            }

            $desiredTableAOrder = ['*', 'data', 'error_id', 'id'];
            $currentTableAFields = $dbData['tables']['table_a']['fields'];
            $orderedTableAFields = [];
            foreach ($desiredTableAOrder as $fieldName) {
                if (in_array($fieldName, $currentTableAFields, true)) {
                    $orderedTableAFields[] = $fieldName;
                }
            }
            foreach ($currentTableAFields as $fieldName) {
                if (!in_array($fieldName, $orderedTableAFields, true)) {
                    $orderedTableAFields[] = $fieldName;
                }
            }
            $dbData['tables']['table_a']['fields'] = $orderedTableAFields;

            $desiredTableEOrder = ['*', 'id'];
            $currentTableEFields = $result['databases'][$targetDatabase]['tables']['table_e']['fields'];
            $orderedTableEFields = [];
            foreach ($desiredTableEOrder as $fieldName) {
                if (in_array($fieldName, $currentTableEFields, true)) {
                    $orderedTableEFields[] = $fieldName;
                }
            }
            foreach ($currentTableEFields as $fieldName) {
                if (!in_array($fieldName, $orderedTableEFields, true)) {
                    $orderedTableEFields[] = $fieldName;
                }
            }
            $result['databases'][$targetDatabase]['tables']['table_e']['fields'] = $orderedTableEFields;
        }
        unset($dbData);

        return $result;
    }


    /**
     * Возвращает безопасное значение для sql запроса
     *
     * @param mixed $value
     * @return mixed
     */
    public static function sqlSafe(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(static fn (mixed $item): mixed => static::sqlSafe($item), $value);
        }

        if ($value instanceof \Stringable) {
            $value = (string)$value;
        }

        if (!is_string($value)) {
            return $value;
        }

        $originalTrailingWhitespace = '';
        if (preg_match('/(\s*)$/u', $value, $trailingMatches)) {
            $originalTrailingWhitespace = $trailingMatches[1];
        }

        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';

        // Удаляем комментарии и управляющие последовательности, мешающие корректному выполнению запроса
        $clean = preg_replace('/(?<!\S)--(?=\s)[^\r\n]*/u', '', $clean) ?? '';
        $clean = preg_replace('/(?<!\S)#(?=\s)[^\r\n]*/u', '', $clean) ?? '';
        $clean = preg_replace('/\/\*.*?\*\//su', '', $clean) ?? '';

        $clean = preg_replace('/;\s*/u', ' ', $clean) ?? '';

        $dangerousFragments = [
            '/\bunion\s+(?:all\s+)?select\b[^\r\n]*/iu',
            '/\b(?:drop|truncate|alter|create|delete|insert|update)\b\s+[^\r\n]*/iu',
            '/\binto\s+outfile\b[^\r\n]*/iu',
            '/\bload_file\s*\([^)]*\)/iu',
            '/\bexec(?:ute)?\b[^\r\n]*/iu',
            '/\b(?:or|and)\b\s+(?:\d+|\'[^\']*\'|"[^"]*"|\w+)\s*(?:=|like)\s*(?:\d+|\'[^\']*\'|"[^"]*"|\w+)/iu',
        ];

        foreach ($dangerousFragments as $pattern) {
            $clean = preg_replace($pattern, '', $clean) ?? '';
        }

        $clean = rtrim($clean);

        if ($clean === '') {
            return '';
        }

        $clean .= $originalTrailingWhitespace;

        return addslashes($clean);
    }
}
