<?php
/**
 * Code written is strictly used within this program.
 * Any other use of this code is in violation of copy rights.
 *
 * @package   Components
 * @author    Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright 2016 Developer
 * @license   - No License
 * @version   GIT: $Id$
 * @link      -
 */
namespace Bridge\Components\Exporter;

/**
 * StringUtility class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class StringUtility
{

    /**
     * Get the string length.
     *
     * @param string $str String data parameter.
     *
     * @return integer
     */
    public static function getLength($str)
    {
        return mb_strlen($str);
    }

    /**
     * Replace some accent character from the given string.
     *
     * @param string $str String data parameter.
     *
     * @return string
     */
    public static function replaceAccent($str)
    {
        $a = [
            'À',
            'Á',
            'Â',
            'Ã',
            'Ä',
            'Å',
            'Æ',
            'Ç',
            'È',
            'É',
            'Ê',
            'Ë',
            'Ì',
            'Í',
            'Î',
            'Ï',
            'Ð',
            'Ñ',
            'Ò',
            'Ó',
            'Ô',
            'Õ',
            'Ö',
            'Ø',
            'Ù',
            'Ú',
            'Û',
            'Ü',
            'Ý',
            'ß',
            'à',
            'á',
            'â',
            'ã',
            'ä',
            'å',
            'æ',
            'ç',
            'è',
            'é',
            'ê',
            'ë',
            'ì',
            'í',
            'î',
            'ï',
            'ñ',
            'ò',
            'ó',
            'ô',
            'õ',
            'ö',
            'ø',
            'ù',
            'ú',
            'û',
            'ü',
            'ý',
            'ÿ',
            'Ā',
            'ā',
            'Ă',
            'ă',
            'Ą',
            'ą',
            'Ć',
            'ć',
            'Ĉ',
            'ĉ',
            'Ċ',
            'ċ',
            'Č',
            'č',
            'Ď',
            'ď',
            'Đ',
            'đ',
            'Ē',
            'ē',
            'Ĕ',
            'ĕ',
            'Ė',
            'ė',
            'Ę',
            'ę',
            'Ě',
            'ě',
            'Ĝ',
            'ĝ',
            'Ğ',
            'ğ',
            'Ġ',
            'ġ',
            'Ģ',
            'ģ',
            'Ĥ',
            'ĥ',
            'Ħ',
            'ħ',
            'Ĩ',
            'ĩ',
            'Ī',
            'ī',
            'Ĭ',
            'ĭ',
            'Į',
            'į',
            'İ',
            'ı',
            'Ĳ',
            'ĳ',
            'Ĵ',
            'ĵ',
            'Ķ',
            'ķ',
            'Ĺ',
            'ĺ',
            'Ļ',
            'ļ',
            'Ľ',
            'ľ',
            'Ŀ',
            'ŀ',
            'Ł',
            'ł',
            'Ń',
            'ń',
            'Ņ',
            'ņ',
            'Ň',
            'ň',
            'ŉ',
            'Ō',
            'ō',
            'Ŏ',
            'ŏ',
            'Ő',
            'ő',
            'Œ',
            'œ',
            'Ŕ',
            'ŕ',
            'Ŗ',
            'ŗ',
            'Ř',
            'ř',
            'Ś',
            'ś',
            'Ŝ',
            'ŝ',
            'Ş',
            'ş',
            'Š',
            'š',
            'Ţ',
            'ţ',
            'Ť',
            'ť',
            'Ŧ',
            'ŧ',
            'Ũ',
            'ũ',
            'Ū',
            'ū',
            'Ŭ',
            'ŭ',
            'Ů',
            'ů',
            'Ű',
            'ű',
            'Ų',
            'ų',
            'Ŵ',
            'ŵ',
            'Ŷ',
            'ŷ',
            'Ÿ',
            'Ź',
            'ź',
            'Ż',
            'ż',
            'Ž',
            'ž',
            'ſ',
            'ƒ',
            'Ơ',
            'ơ',
            'Ư',
            'ư',
            'Ǎ',
            'ǎ',
            'Ǐ',
            'ǐ',
            'Ǒ',
            'ǒ',
            'Ǔ',
            'ǔ',
            'Ǖ',
            'ǖ',
            'Ǘ',
            'ǘ',
            'Ǚ',
            'ǚ',
            'Ǜ',
            'ǜ',
            'Ǻ',
            'ǻ',
            'Ǽ',
            'ǽ',
            'Ǿ',
            'ǿ'
        ];
        $b = [
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'AE',
            'C',
            'E',
            'E',
            'E',
            'E',
            'I',
            'I',
            'I',
            'I',
            'D',
            'N',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'U',
            'U',
            'U',
            'U',
            'Y',
            's',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'ae',
            'c',
            'e',
            'e',
            'e',
            'e',
            'i',
            'i',
            'i',
            'i',
            'n',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'u',
            'y',
            'y',
            'A',
            'a',
            'A',
            'a',
            'A',
            'a',
            'C',
            'c',
            'C',
            'c',
            'C',
            'c',
            'C',
            'c',
            'D',
            'd',
            'D',
            'd',
            'E',
            'e',
            'E',
            'e',
            'E',
            'e',
            'E',
            'e',
            'E',
            'e',
            'G',
            'g',
            'G',
            'g',
            'G',
            'g',
            'G',
            'g',
            'H',
            'h',
            'H',
            'h',
            'I',
            'i',
            'I',
            'i',
            'I',
            'i',
            'I',
            'i',
            'I',
            'i',
            'IJ',
            'ij',
            'J',
            'j',
            'K',
            'k',
            'L',
            'l',
            'L',
            'l',
            'L',
            'l',
            'L',
            'l',
            'l',
            'l',
            'N',
            'n',
            'N',
            'n',
            'N',
            'n',
            'n',
            'O',
            'o',
            'O',
            'o',
            'O',
            'o',
            'OE',
            'oe',
            'R',
            'r',
            'R',
            'r',
            'R',
            'r',
            'S',
            's',
            'S',
            's',
            'S',
            's',
            'S',
            's',
            'T',
            't',
            'T',
            't',
            'T',
            't',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'W',
            'w',
            'Y',
            'y',
            'Y',
            'Z',
            'z',
            'Z',
            'z',
            'Z',
            'z',
            's',
            'f',
            'O',
            'o',
            'U',
            'u',
            'A',
            'a',
            'I',
            'i',
            'O',
            'o',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'A',
            'a',
            'AE',
            'ae',
            'O',
            'o'
        ];
        return str_replace($a, $b, $str);
    }

    /**
     * Convert the given string to camel case format.
     *
     * @param string $str String data parameter.
     *
     * @return string
     */
    public static function toCamelCase($str)
    {
        # Sample: Bambang Adrian Sitompul : bambangAdrianSitompul
        # TODO: Implement the toCamelCase method.
    }

    /**
     * Build json format from string.
     *
     * @param string $str               String data parameter.
     * @param string $delimiter         Delimiter that separate each record.
     * @param string $keyValueSeparator Separator character that will be used to fetch the key and the value.
     *
     * @return string
     */
    public static function toJson($str, $delimiter = ',', $keyValueSeparator = '=')
    {
        $data = [];
        $callbackFunction = function ($arrValue) use ($keyValueSeparator) {
            $arrValue = explode($keyValueSeparator, trim($arrValue));
            return [$arrValue[0] => trim($arrValue[1])];
        };
        $arrString = array_map($callbackFunction, explode($delimiter, $str));
        foreach ($arrString as $row) {
            $data = array_merge($data, $row);
        }
        return json_encode($data);
    }

    /**
     * Convert the given string to pascal case format.
     *
     * @param string $str String data parameter.
     *
     * @return string
     */
    public static function toPascalCase($str)
    {
        # Sample: Bambang Adrian Sitompul : BambangAdrianSitompul
        # Sample: bambang adrian sitompul : BambangAdrianSitompul
        # TODO: Implement the toPascalCase method.
    }

    /**
     * Convert the given string to underscore case format.
     *
     * @param string $str String data parameter.
     *
     * @return string
     */
    public static function toUnderScoreCase($str)
    {
        # Sample: Bambang Adrian Sitompul : bambang_adrian_sitompul
        # TODO: Implement the toUnderScoreCase method.
    }

    /**
     * Convert the given string to url friendly format.
     *
     * @param string $str       String data parameter.
     * @param array  $replace   Array of data that will be used for replace the character on the given string.
     * @param string $delimiter The delimiter that will used to replace the special character.
     *
     * @return string
     */
    public static function toUriFriendly($str, array $replace = [], $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }
        $clean = static::replaceAccent($str);
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        return $clean;
    }
}
