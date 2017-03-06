<?php

namespace common\modules\adminUser\models;

use common\core\web\mvc\BaseModel;
use Yii;

/**
 * This is the model class for table "adminuser.role_right".
 *
 * @property integer $uid
 * @property integer $role_id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property integer $is_owner
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class RoleRight extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adminuser.role_right';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module', 'controller', 'action'], 'string'],
            [['role_id', 'is_owner', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'role_id'    => 'Role Name',
            'controller' => 'Controller',
            'action'     => 'Action',
            'is_owner'   => 'Is Owner',
        ];

        return array_merge($labels, parent::attributeLabels());
    }

    public static function getPermission($roleId, $module = false, $controller, $action)
    {
        $conditions = ['role_id' => $roleId, 'controller' => $controller, 'action' => $action];
        if ($module) {
            $conditions['module'] = $module; 
        }
        $rightDb = static::find()
            ->andWhere($conditions)
            ->one();

        return $rightDb;
    }

}
