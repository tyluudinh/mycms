<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 1/4/16
 * Time: 10:40 AM
 */

namespace common\utilities;


class StringUtil
{
    /**
     * @example input VE12345678 => return VE
     *
     * @param $word string have first characters is non-numeric letters
     * @return string
     */
    public static function getFirstLetters($word)
    {
        $l = strlen($word);
        $letters = '';
        for ($i = 0; $i < $l; $i++) {
            if (!is_numeric($word[$i])) {
                $letters .= $word[$i];
            } else {
                return $letters;
            }
        }
        return $letters;

    }
}