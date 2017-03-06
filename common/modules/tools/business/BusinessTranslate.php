<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 10/7/16
 * Time: 9:31 AM
 */

namespace common\modules\tools\business;

use common\business\BaseBusinessPublisher;

class BusinessTranslate extends BaseBusinessPublisher
{
    /**
     * @link https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     */
    const LANGUAGE_2_EN = 'en';
    const LANGUAGE_2_VI = 'vi';

    public static function getTranslatePath()
    {
        return APPROOT . '/common/messages/';
    } 

    public static function getAppLanguages()
    {
        return [
            self::LANGUAGE_2_EN => 'English',
            self::LANGUAGE_2_VI => 'Tiếng Việt',
        ];
    }

}