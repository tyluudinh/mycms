<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/9/16
 * Time: 4:34 PM
 */

namespace common\core\web\mvc;


use common\core\oop\Vector;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;
use Yii;

class BaseActiveQuery extends ActiveQuery
{
    private $_asVector = false;

    public function setAsVector()
    {
        $this->_asVector = true;
    }

    /**
     * @param array $rows
     * @return array | BaseModel[] | Vector
     */
    public function populate($rows)
    {
        $data = parent::populate($rows);
        if ($this->_asVector) {
            return (new Vector($data));
        }
        return $data;

    }

    public function populateRecord()
    {
        
    }

    /**
     * @param null $db
     * @return array | BaseModel[] | Vector
     */
    public function all($db = null)
    {
        if ((new $this->modelClass())->hasAttribute('priority')) {
            $this->addOrderBy(['priority' => SORT_ASC]);
        }
        return parent::all();
    }

    public function oneOrNew($db = null)
    {
        $row = parent::one($db);
        return $row ?: new $this->modelClass;
    }

    public function oneOrFail($db = null)
    {
        $row = parent::one($db);
        if ($row) {
            return $row;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Can not find your data'));
    }

    public function findFor($name, $model)
    {
        $value = parent::findFor($name, $model);
        if ($value === null) {
            return new $this->modelClass();
        }
        return $value;
    }

    public function andFilterWhereLowercase(array $condition)
    {
        if (in_array($condition[0], ['like'])) {
            return $this->andFilterWhere([$condition[0], "lower({$condition[1]})", strtolower($condition[2])]);
        }
        return $this;
    }

    public function orFilterWhereLowercase(array $condition)
    {
        if (in_array($condition[0], ['like'])) {
            return $this->orFilterWhere([$condition[0], "lower({$condition[1]})", strtolower($condition[2])]);
        }
        return $this;
    }


}