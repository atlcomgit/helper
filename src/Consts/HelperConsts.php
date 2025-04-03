<?php

declare(strict_types=1);

namespace Atlcom\Consts;

class HelperConsts
{
    public const NUMERIC_NAMES = [ // ciphStr
        ['нол', 'нулев'],
        ['од', 'перв'],
        ['дв', 'втор'],
        ['тр', 'трет'],
        ['четыр', 'четвёрт'],
        ['пят', 'пят'],
        ['шест', 'шест'],
        ['сем', 'седьм'],
        ['вос', 'восьм'],
        ['девят', 'девят'],
        ['десят', 'десят'],
        ['одиннадцат', 'одиннадцат'],
        ['двенадцат', 'двенадцат'],
        ['тринадцат', 'тринадцат'],
        ['четырнадцат', 'четырнадцат'],
        ['пятнадцат', 'пятнадцат'],
        ['шестнадцат', 'шестнадцат'],
        ['семнадцат', 'семнадцат'],
        ['восемнадцат', 'восемнадцат'],
        ['девятнадцат', 'девятнадцат'],
        ['двадцат', 'двадцат'],
        ['тридцат', 'тридцат'],
        ['сорок', 'сороков'],
        ['пят', 'пятидесят'],
        ['шест', 'шестидесят'],
        ['сем', 'семидесят'],
        ['вос', 'восьмидесят'],
        ['девяност', 'девяност'],
        ['с', 'сот'],
        ['дв', 'двухсот'],
        ['тр', 'трёхсот'],
        ['четыр', 'четырёхсот'],
        ['пят', 'пятисот'],
        ['шест', 'шестисот'],
        ['сем', 'семисот'],
        ['вос', 'восьмисот'],
        ['девят', 'девятьсот'],
        ['тысяч', 'тысячн'],
        ['миллион', 'миллионн'],
        ['миллиард', 'миллиардн'],
        ['триллион', 'триллионн'],
        ['квадриллион', 'квадриллионн'],
        ['квинтиллион', 'квинтиллионн'],
        ['секстиллион', 'секстиллионн'],
        ['септиллион', 'септиллионн'],
    ];

    public const NUMBER_CASE_X1M = [ // numCaseX1M
        ['ов', '', 'а'],
        ['ов', 'а', 'ов'],
        ['ам', 'у', 'ам'],
        ['ов', '', 'а'],
        ['ами', 'ом', 'ами'],
        ['ах', 'е', 'ах'],
    ];

    public const NUMBER_CASE_X1K = [ // numCaseX1K
        ['', 'а', 'и'],
        ['', 'и', ''],
        ['', 'е', 'ам'],
        ['', 'у', 'и'],
        ['ами', 'ей', 'ами'],
        ['ах', 'е', 'ах'],
    ];

    // public const NUMBER_CASE_X100 = [1, 2, 3, 4, 5, 5, 5, 6, 5]; // numCaseX100 (original)
    public const NUMBER_CASE_X100 = [0, 1, 2, 3, 4, 4, 4, 5, 4]; // numCaseX100

    public const NUMBER_CASE_100 = [ // numCase100
        // 100 | 200 | 300 | 400 | 500-700,900 | 800
        ['то', 'ести', 'иста', 'еста', 'ьсот', 'емьсот'],
        ['та', 'ухста', 'ёхста', 'ёхста', 'иста', 'ьмиста'],
        ['та', 'ухстам', 'ёмстам', 'ёмстам', 'истам', 'ьмистам'],
        ['то', 'ести', 'иста', 'еста', 'ьсот', 'емьсот'],
        ['та', 'ухстами', 'ёхстами', 'ёхстами', 'истами', 'ьмистами'],
        ['та', 'ухста', 'ёхста', 'ёхста', 'иста', 'ьмиста'],
    ];

    public const NUMBER_CASE_X1 = [0, 1, 2, 3, 4, 5, 5, 5, 6, 5]; // numCaseX1

    public const NUMBER_CASE_X10 = [5, 5, 5, 7, 8, 8, 8, 9, 10]; // numCaseX10 (original)
    // public const NUMBER_CASE_X10 = [0, 4, 4, 4, 6, 7, 7, 7, 8, 9]; // numCaseX10 (original)

    public const NUMBER_CASE_10 = [ // numCase10
        [
            // 0 | 1 | 2 | 3 | 4 | 5-7,9,10-20,30.. | 8 | 40 | 50,60,70 | 80 | 90
            ['ь', 'ин', 'а', 'и', 'е', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
            ['ь', 'на', 'е', 'и', 'е', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
            ['ь', 'но', 'ое', 'ое', 'е', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
            ['ь', 'ни', 'ое', 'ое', 'е', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
        ],
        [
            ['я', 'ного', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
            ['я', 'ной', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
            ['я', 'ного', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
            ['я', 'них', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
        ],
        [
            ['ю', 'ному', 'ум', 'ём', 'ём', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
            ['ю', 'ной', 'ум', 'ём', 'ём', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
            ['ю', 'ному', 'ум', 'ём', 'ём', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
            ['ю', 'ним', 'ум', 'ём', 'ём', 'и', 'ьми', 'а', 'идесяти', 'ьмидесяти', 'о'],
        ],
        [
            ['ь', 'ин', 'а', 'и', 'и', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
            ['ь', 'ну', 'е', 'и', 'и', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
            ['ь', 'но', 'ое', 'ое', 'е', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
            ['ь', 'ни', 'ое', 'ое', 'е', 'ь', 'емь', '', 'ьдесят', 'емьдесят', 'о'],
        ],
        [
            ['ём', 'ним', 'умя', 'емя', 'ьмя', 'ью', 'емью', 'а', 'идестью', 'ьмидесятью', 'о'],
            ['ём', 'ной', 'умя', 'емя', 'ьмя', 'ью', 'емью', 'а', 'идестью', 'ьмидесятью', 'о'],
            ['ём', 'ним', 'умя', 'емя', 'ьмя', 'ью', 'емью', 'а', 'идестью', 'ьмидесятью', 'о'],
            ['ём', 'ними', 'умя', 'емя', 'ьмя', 'ью', 'емью', 'а', 'идестью', 'ьмидесятью', 'о'],
        ],
        [
            ['е', 'ном', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идести', 'ьмидесяти', 'о'],
            ['е', 'ной', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идести', 'ьмидесяти', 'о'],
            ['е', 'ном', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идести', 'ьмидесяти', 'о'],
            ['е', 'них', 'ух', 'ёх', 'ёх', 'и', 'ьми', 'а', 'идести', 'ьмидесяти', 'о'],
        ],
    ];

    public const NUMBER_ORD_X1 = [0, 1, 0, 2, 1, 1, 0, 0, 0, 1]; // ordCaseX1

    public const NUMBER_ORD_X10 = [1, 1, 1, 0, 1, 1, 1, 1, 1]; // ordCaseX10

    public const NUMBER_ORD_X100 = [1, 1, 1, 1, 1, 1, 1, 1, 1]; // ordCaseX100 (original)
    // public const NUMBER_ORD_X100 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; // ordCaseX100

    public const NUMBER_ORD = [ // ordCase
        [
            // 0,2,6-8,40 | 1,4,5,9..20,30,50,60,70,80,90 | 3
            ['ой', 'ый', 'ий'],
            ['ая', 'ая', 'ья'],
            ['ое', 'ое', 'ье'],
            ['ые', 'ые', 'ьи'],
        ],
        [
            ['ого', 'ого', 'ьего'],
            ['ой', 'ой', 'ьей'],
            ['ого', 'ого', 'ьего'],
            ['ых', 'ых', 'ьих'],
        ],
        [
            ['ому', 'ому', 'ьему'],
            ['ой', 'ой', 'ьей'],
            ['ому', 'ому', 'ьему'],
            ['ым', 'ым', 'ьим'],
        ],
        [
            ['ой', 'ый', 'ий'],
            ['ую', 'ая', 'ью'],
            ['ое', 'ое', 'ье'],
            ['ые', 'ые', 'ьи'],
        ],
        [
            ['ым', 'ым', 'ьим'],
            ['ой', 'ой', 'ьей'],
            ['ым', 'ым', 'ьим'],
            ['ыми', 'ыми', 'ьими'],
        ],
        [
            ['ом', 'ом', 'ьем'],
            ['ой', 'ой', 'ьей'],
            ['ом', 'ом', 'ьем'],
            ['ых', 'ых', 'ьих'],
        ],
    ];

    public const NUMBER_INT_CASE = [ // intCase
        ['ых', 'ая', 'ых'],
        ['ых', 'ой', 'ых'],
        ['ых', 'ой', 'ым'],
        ['ых', 'ая', 'ых'],
        ['ыми', 'ой', 'ыми'],
        ['ых', 'ой', 'ых'],
    ];
}
