<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 10/26/16
 * Time: 11:23 AM
 */

namespace common\core\web\mvc;

use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;

class BaseNoSqlCollection extends ActiveRecord
{
    public static function findOneOrNew($conditions = [])
    {
        $d = static::findOne($conditions);
        if (empty($d)) {
            return new static;
        }

        return $d;
    }
    public static function findOneOrFail($conditions = [])
    {
        $d = static::findOne($conditions);
        if (empty($d)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Data not found or unavailable.'));
        }

        return $d;
    }

}