<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 8/5/16
 * Time: 12:24 PM
 */

namespace backend\models;


use common\core\web\mvc\BaseModel;
use common\Factory;

class BaseSearchModel extends BaseModel
{
    private static $_searchQuery;

    public static function isValueSet($v)
    {
        return !is_null($v) && $v !== '';
    }
    
    public static function buildSearchQuery($params = [])
    {
        if (self::$_searchQuery === null) {
            $params = empty($params) ? Factory::$app->request->queryParams : $params;
            $query  = self::find();
            $model = new static();
            foreach ($params as $field => $searchVal) {
                if ($model->hasAttribute($field)) {
                    if (in_array($field, static::searchComboBoxFields()) && self::isValueSet($searchVal)) {
                        $query->andWhere([$field => $searchVal]);
                    } else {
                        if (in_array($field, static::searchTextFields()) && self::isValueSet($searchVal)) {
                            $query->andWhere(['like', $field, $searchVal]);
                        } elseif (in_array($field, static::searchRangeFields()) && is_array($searchVal)) {
                            if (self::isValueSet($searchVal['from']) && self::isValueSet($searchVal['to'])) {
                                $query->andWhere(['between', $field, $searchVal['from'], $searchVal['to']]);
                            }
                        }
                    }
                }
            }

            self::$_searchQuery = $query;
        }

        return self::$_searchQuery;

    }

    /** @return array */
    public static function searchComboBoxFields()
    {
        return [];
    }

    /** @return array */
    public static function searchTextFields()
    {
        return [];
    }

    /** @return array */
    public static function searchRangeFields()
    {
        return [];
    }

}