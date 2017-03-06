<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/9/16
 * Time: 4:08 PM
 * Modified by:   Nhut
 * Modified time: 2016-07-26 14:42:00
 */

namespace common\core\web\mvc;


use common\core\web\BaseFormatter;
use \Yii;
use common\core\oop\ObjectInterface;
use common\core\oop\ObjectScalar;
use common\Factory;
use common\utilities\ArraySimple;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\web\NotFoundHttpException;

class BaseModel extends ActiveRecord implements ObjectInterface
{
    public function isEmpty()
    {
        return $this->primaryKey === null || count($this->attributes) === 0;
    }

    public $emptyField;

    public function setAttributes($values, $safeOnly = true)
    {
        $columnsSchemas = $this->getTableSchema()->columns;

        foreach ($values as $attr => $value) {
            if (isset($columnsSchemas[$attr])) {
                //ignore setting values for image, metadata... fields
                if (in_array($columnsSchemas[$attr]->dbType, ['jsonb', 'json'])) {
                    unset($values[$attr]);
                }
                if ($columnsSchemas[$attr]->dbType[0] == '_') {
                    //array
                    unset($values[$attr]);
                }
                if ($columnsSchemas[$attr]->type == Schema::TYPE_TIMESTAMP) {
                    $dateObject = \DateTime::createFromFormat(BaseFormatter::PHP_DATETIME_FORMAT, $values[$attr]);
                    if (!$dateObject) {
                        $dateObject = \DateTime::createFromFormat(BaseFormatter::PHP_DATE_FORMAT, $values[$attr]);
                    }
                    $this->setAttribute($attr, ($dateObject ? $dateObject->format('Y-m-d H:i:s') : null));
                    unset($values[$attr]);
                } else if ($columnsSchemas[$attr]->type == Schema::TYPE_DATE) {
                    $dateObject = \DateTime::createFromFormat(BaseFormatter::PHP_DATE_FORMAT, $values[$attr]);
                    $this->setAttribute($attr, ($dateObject ? $dateObject->format('Y-m-d') : null));
                    unset($values[$attr]);
                }

            } else if (property_exists($this, $attr)) {
                $this->{$attr} = $value;
            }
        }

        return parent::setAttributes($values, $safeOnly);
    }

    /**
     * override insert & update method (add created_by, created_at, updated_by, updated_at attributes value)
     * @param bool|true $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->getIsNewRecord()) {
            $this->_setCreatedAt();
        } else {
            $this->_setUpdatedAt();
        }

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * override insert mothod, add created_by and created_at attributes value
     * @param bool|true $runValidation
     * @param null $attributes
     * @return bool
     * @throws \Exception
     */
    public function insert($runValidation = true, $attributes = null)
    {
        $this->_setCreatedAt();

        return parent::insert($runValidation, $attributes);
    }

    /**
     * override update method, add updated_by and updated_at attributes value
     * @param bool|true $runValidation
     * @param null $attributes
     * @return bool
     * @throws \Exception
     */
    public function update($runValidation = true, $attributes = null)
    {
        $this->_setUpdatedAt();

        return parent::update($runValidation, $attributes);
    }

    /**
     * override delete mothod, don't delete, only set status to deleted
     *
     * @param bool|true $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function delete($runValidation = false, $attributeNames = null)
    {
        if ($this->hasAttribute('status')) {
            $this->_setDeletedStatus();

            return parent::save($runValidation, $attributeNames);
        }

        return parent::delete();
    }

    /**
     * override deleteAll method, only update deleted_at
     * @param string|array $condition
     * @param bool $isForce
     * @param array $params
     * @return int
     */
    public static function deleteAll($condition = '', $params = [], $isForce = false)
    {
        $model = new static();
        if ($isForce || !$model->hasAttribute('status')) {
            return parent::deleteAll($condition, $params);
        }

        $attrs = ['status' => STATUS_DELETED];
        if ($model->hasAttribute('updated_at')) {
            $attrs['updated_at'] = date('Y-m-d H:i:s', time());
        }
        return parent::updateAll($attrs, $condition, $params);
    }

    /**
     * Hook into every kind of find method which is use by ActiveRecord, that's only find the not yet deleted records
     * @return BaseActiveQuery
     */
    public static function find()
    {
        /** @var $query BaseActiveQuery */
        $query = \Yii::createObject(BaseActiveQuery::className(), [get_called_class()]);
        $model = new static;
        if (!$model->hasAttribute('status')) {
            return $query;
        }

        $tableName = static::tableName();
        if (in_array(Factory::$app->id, ['app-backend'])) {
            $query->andWhere($tableName . '.status <> ' . STATUS_DELETED);
        } else {
            $query->andWhere([$tableName . '.status' => [STATUS_ACTIVE]]);
        }
        $query->orWhere($tableName . '.status IS NULL ');

        return $query;
    }

    /**
     * @param array $conditions
     * @return static
     */
    public static function findOneOrNew($conditions = [])
    {
        $d = static::findOne($conditions);
        if (empty($d)) {
            return new static;
        }

        return $d;
    }

    /**
     * @param array $conditions
     * @return static
     * @throws NotFoundHttpException
     */
    public static function findOneOrFail($conditions = [])
    {
        $d = static::findOne($conditions);
        if (empty($d)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Data not found or unavailable.'));
        }

        return $d;
    }

    /**
     * @return BaseActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function findAsVector()
    {
        $model = new static;
        if (!$model->hasAttribute('status')) {
            return parent::find();
        }
        /** @var $query BaseActiveQuery */
        $query = \Yii::createObject(BaseActiveQuery::className(), [get_called_class()]);
        $query->setAsVector();
        $tableName = static::tableName();
        if (in_array(Factory::$app->id, ['app-backend'])) {
            $query->andWhere($tableName . '.status <> ' . STATUS_DELETED);
        } else {
            $query->andWhere([$tableName . '.status' => [STATUS_ACTIVE]]);
        }
        $query->orWhere($tableName . '.status IS NULL ');
        if ($model->hasAttribute('priority')) {
            $query->orderBy([$tableName . '.priority' => SORT_ASC]);
        }

        return $query;
    }

    /**
     * Extract 2 fields in query to key value pair array
     * @param $fields
     * @param bool $asObject
     * @param mixed $condition
     * @return ObjectScalar|array
     */
    public static function findKeyValue($fields, $condition = '', $asObject = true)
    {
        if (count($fields) == 1) {
            $fields[1] = $fields[0];
        }
        $query = static::find()->select($fields)->andWhere($condition)->from(static::tableName());
        $rows = $query->createCommand()->queryAll();

        return ArraySimple::keyValue($rows, $fields, $asObject);
    }

    /**
     * get max value of field
     * @param $field
     * @return mixed
     */
    public static function getMax($field)
    {
        return self::find()->max($field);
    }

    /**
     * get min value of field
     * @param $field
     * @return mixed
     */
    public static function getMin($field)
    {
        return self::find()->min($field);
    }

    public static function getCount($condition = null)
    {
        if ($condition) {
            return self::find()->where($condition)->count();
        } else {
            return self::find()->count();
        }
    }

    /**
     * In case of system auto update, some field such as update_by should be disabled
     * @param $field string a field in table schema
     * @return $this
     */
    public function unsetField($field)
    {
        $this->{$field} = null;

        return $this;
    }

    /**
     * get current user id
     * @return int $userId
     */
    private function _getCurrentUserId()
    {
        if (Factory::$app->id == 'app-console') {
            return 0;
        }
        $user = adminuser();

        return $user ? $user->getId() : 0;
    }

    protected function _setCreatedAt()
    {
        if ($this->hasAttribute('created_by')) {
            $this->setAttribute('created_by', self::_getCurrentUserId());
        }

        if ($this->hasAttribute('created_at')) {
            $this->setAttribute('created_at', gmdate('Y-m-d H:i:s', time()));
        }

        if ($this->hasAttribute('updated_at')) {
            $this->setAttribute('updated_at', gmdate('Y-m-d H:i:s', time()));
        }

        return $this;
    }

    protected function _setUpdatedAt()
    {
        if ($this->hasAttribute('updated_by')) {
            $this->setAttribute('updated_by', self::_getCurrentUserId());
        }
        if ($this->hasAttribute('updated_at')) {
            $this->setAttribute('updated_at', gmdate('Y-m-d H:i:s', time()));
        }

        return $this;
    }

    protected function _setDeletedStatus()
    {
        $this->setAttribute('status', STATUS_DELETED);

        return $this;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'priority' => Yii::t('app', 'Priority'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Last Updated At'),
            'metadata' => Yii::t('app', 'Metadata'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'ip' => Yii::t('app', 'Ip'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'platform' => Yii::t('app', 'Platform'),
            'platform_version' => Yii::t('app', 'Platform Version'),
            'platform_build' => Yii::t('app', 'Platform Build'),
        ];
    }

    public function getFirstErrorMessage()
    {
        $errors = $this->getFirstErrors();
        if (!empty($errors)) {
            return array_values($errors)[0];
        }
        return false;
    }

    /**
     * return a copy of called Model
     *
     * @return self|static
     */
    public function copy()
    {
        $data = $this->toArray();
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $o = new static;
        $o->setAttributes($data);

        return $o;
    }

    public static function labelOf($attr)
    {
        return (new static)->getAttributeLabel($attr);
    }

    public function getFirstAttribute()
    {
        return array_keys($this->attributes)[0];
    }

    public static function getCountRecordsCreatedAtToday($condition = [])
    {
        $today = Factory::$app->formatter->getSystemDate();
        $query = self::find()
            ->select('*')
            ->from(static::tableName())
            ->where("date(created_at) = date('$today')")
            ->andWhere($condition)
            ->count();
        return $query;
    }

    /**
     * @return BaseActiveQuery
     */
    public static function baseSearch()
    {
        $query = static::find();
        $whateverFilter = Factory::$app->request->get('search-whatever');
        if ($whateverFilter) {
            foreach (self::getStringFields() as $i => $field) {
                if ($i == 0) {
                    $query->andFilterWhereLowercase(['like', $field, $whateverFilter]);
                } else {
                    $query->orFilterWhereLowercase(['like', $field, $whateverFilter]);
                }
            }
        }

        return $query;
    }

    public static function getStringFields()
    {
        $columnsSchemas = static::getTableSchema()->columns;
        $stringFields = [];
        foreach ($columnsSchemas as $column => $schema) {
            if ($schema->type == Schema::TYPE_STRING) {
                $stringFields[] = $column;
            }
        }

        return $stringFields;
    }

    public static function getComparableFields()
    {
        $columnsSchemas = static::getTableSchema()->columns;
        $comparableFields = [];
        foreach ($columnsSchemas as $column => $schema) {
            if (in_array($schema->phpType, [Schema::TYPE_INTEGER, Schema::TYPE_DOUBLE]) || in_array($schema->type, [Schema::TYPE_DATE, Schema::TYPE_DATETIME, Schema::TYPE_TIMESTAMP])) {
                $comparableFields[] = $column;
            }
        }

        return $comparableFields;
    }

}
