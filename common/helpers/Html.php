<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/3/16
 * Time: 3:28 PM
 */
namespace common\helpers;


use common\core\web\BaseFormatter;
use common\modules\tools\business\BusinessTranslate;
use common\Factory;
use common\utilities\Common;
use yii\helpers\Url;
use Yii;

class Html extends \yii\helpers\Html
{
    public static function activeText($stdVal = '', $cmpVal = '', $text = 'active')
    {
        if (is_string($stdVal) && is_string($cmpVal) && strpos($stdVal, $cmpVal) !== false) {
            return "$text";
        }
        return null;
    }

    public static function moneyToDouble($money)
    {
        $money = str_replace(',', '', $money);
        return doubleval($money);
    }

    public static function yesOrNo($v)
    {
        return $v ? \Yii::t('app', 'Yes') : \Yii::t('app', 'No');
    }

    public static function displayImageFromRelativePath($path, $options = [], $isHyperlink = true)
    {
        $defaultOptions = [
            'type' => 'normal-form',
        ];
        $options += $defaultOptions;
        if (!isset($options['style'])) {
            $options['style'] = 'width: auto;';
        }
        switch ($options['type']) {
            case 'avatar' :
                $options['style'] .= 'max-width: 72px;';
                break;
            case 'grid' :
                $options['style'] .= 'max-width: 50px;';
                break;
            case 'view' :
                $options['style'] .= 'max-width: 100px;';
                break;
            case 'normal-form' :
                $options['style'] .= 'max-width: 100px;';
                break;
        }

        $link = $path ? Factory::$app->params['cdn_link'] . $path : self::emptyImageUrl();

        if ($isHyperlink) {
            return "<a href='{$link}' target='_blank'>" . self::img($link, $options) . "</a>";
        }

        return self::img($link, $options);
    }

    private static $emptyImageUrl;

    public static function emptyImageUrl()
    {
        if (self::$emptyImageUrl === null) {
            self::$emptyImageUrl = Url::home(true) . '/imgs/nothing-to-display.png';
        }
        return self::$emptyImageUrl;

    }

    public static function updownBtn($target, $defaultHidden = true)
    {
        if ($target[0] != '#') {
            $target = '#' . $target;
        }
        if ($defaultHidden) {
            return self::a("<i class='glyphicon glyphicon-chevron-down'></i>", false, ['class' => 'ddToggleDown pull-right', 'data-target' => $target]);
        }
        return self::a("<i class='glyphicon glyphicon-chevron-up'></i>", false, ['class' => 'ddToggleUp pull-right', 'data-target' => $target]);
    }

    public static function getStatusSearchForIndex($searchModel)
    {
        return [
            'attribute' => 'status',
            'value' => function ($model) {
                return Common::getStrStatus($model->status);
            },
            'filter' => self::activeDropDownList($searchModel, 'status', Common::getStatusArr(), [
                'class' => 'form-control',
                'prompt' => Yii::t('app', 'All'),
            ]),
        ];
    }

    public static function setRatingDisplay($rating)
    {
        $html = "<div class='rating'>";
        $i = 0;
        while ($i < 5) {
            if ($i >= $rating) {
                $html = $html . "<label></label>";
            } else {
                $html = $html . "<label style='color: #d06c2e'></label>";
            }
            $i++;
        }
        return $html . "</div>";
    }

    public static function filterSearchFromTo($formName, $attr)
    {
        $queryParams = Factory::$app->request->queryParams;
        $queryParams = Factory::createObject($queryParams, true);
        $fromValue = $queryParams[$formName][$attr]['from'];
        $toValue = $queryParams[$formName][$attr]['to'];
        $inputFrom = Html::input('text', $formName . "[$attr][from]", $fromValue,
            ['class' => 'form-control',
                'placeholder' => 'From',
            ]);
        $inputTo = Html::input('text', $formName . "[$attr][to]", $toValue,
            ['class' => 'form-control',
                'placeholder' => 'To',
                'style' => 'margin-top: 5px'
            ]);

        return $inputFrom . $inputTo;
    }

    public static function datetimepickerInput($type, $name = null, $value = null, $options = [])
    {
        if ($time = strtotime($value)) {
            $value = date(BaseFormatter::PHP_DATE_FORMAT, $time);
        }
        $input = parent::input($type, $name, $value, $options);
        return "<div style='position: relative'>{$input}</div>";
    }

    public static function nestHtmlTagToText($text, $search, $nestTag = 'span', $options = ['style' => 'background-color: yellow'])
    {
        return str_replace($search, Html::tag($nestTag, $search, $options), $text);
    }

}