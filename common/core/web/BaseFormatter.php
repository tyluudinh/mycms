<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 8/4/16
 * Time: 2:57 PM
 */

namespace common\core\web;

use common\helpers\Html;
use common\modules\adminUser\models\User;
use common\utilities\Common;
use yii\i18n\Formatter;
use Yii;
use MongoDB\BSON\UTCDateTime;

class BaseFormatter extends Formatter
{
    const PHP_DATETIME_FORMAT = 'd/m/Y H:i:s';
    const PHP_DATE_FORMAT = 'd/m/Y';
    const PHP_TIME_FORMAT = 'H:i';

    public $dateFormat = 'php:d/m/Y';
    public $datetimeFormat = 'php:d/m/Y H:i:s';

    private $systemDate;
    private $systemDatetime;

    public function init()
    {
        parent::init();
    }

    public function getSystemDate()
    {
        if ($this->systemDate === null) {
            $this->systemDate = date('Y-m-d');
        }
        return $this->systemDate;
    }

    public function getSystemDatetime()
    {
        if ($this->systemDatetime === null) {
            $this->systemDatetime = date('Y-m-d');
        }
        return $this->systemDatetime;
    }

    private function getBasicThumbnailPathOfImage($value)
    {
        $basicThumbPath = Yii::getAlias('@static') . '/' . THUMBNAIL_SIZE_200x200 . '/' . $value;
        if (is_file($basicThumbPath)) {
            return THUMBNAIL_SIZE_200x200 . '/' . $value;
        }
        return $value;
    }

    public function asImage($value, $options = [])
    {
        $options['type'] = 'normal-form';
        return Html::displayImageFromRelativePath($this->getBasicThumbnailPathOfImage($value), $options);
    }

    public function asImageGrid($value, $options = [])
    {
        $options['type'] = 'grid';
        return Html::displayImageFromRelativePath($this->getBasicThumbnailPathOfImage($value), $options);
    }

    public function asImageView($value, $options = [])
    {
        $options['type'] = 'view';
        return Html::displayImageFromRelativePath($this->getBasicThumbnailPathOfImage($value), $options);
    }
    
    public function asStaff($value)
    {
        return User::findOneOrNew($value)->fullname;
    }

    public function asStatus($value)
    {
        return Common::getStrStatus($value);
    }

    public function asJsonTable($value)
    {
        return Common::jsonToDebug($value);
    }

    public function asGender($value)
    {
        return Common::getGenders($value);
    }

    public function asStarRating($value)
    {
        return Html::setRatingDisplay($value);
    }

    public function asCurrency($value, $currency = null, $options = [], $textOptions = [])
    {
        $currency = $currency ?: 'USD';
        return parent::asCurrency($value, $currency, $options, $textOptions);
    }

}