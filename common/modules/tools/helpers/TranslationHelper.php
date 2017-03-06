<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 10/11/16
 * Time: 4:00 PM
 */

namespace common\modules\tools\helpers;

use common\modules\tools\business\BusinessTranslate;
use common\Factory;
use yii\helpers\Html;

class TranslationHelper
{
    public static function getValueLanguage()
    {
        $arrayLanguages = BusinessTranslate::getAppLanguages();
        foreach ($arrayLanguages as $key => $value) {
            if ($key != Factory::$app->language) {
                return Html::a($value, ['/tools/translate/change-language', 'language' => $key],
                    ['class' => 'btn btn-primary btnChangeLanguage', 'data-lang' => $key]);
            }
        }
        return Html::a($arrayLanguages['en'], ['/tools/translate/change-language', 'language' => BusinessTranslate::LANGUAGE_2_EN],
            ['class' => 'btn btn-primary btnChangeLanguage', 'data-lang' => BusinessTranslate::LANGUAGE_2_EN]);
    }

}