<?php

namespace common\modules\adminUser\models;

use common\core\web\mvc\BaseModel;
use Yii;

/**
 * @property integer $uid
 * @property string $code
 * @property string $name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Role extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adminuser.role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'code'], 'string'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
        ];

        return array_merge($labels, parent::attributeLabels());
    }
}
