<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/9/16
 * Time: 4:09 PM
 */

namespace common\modules\adminUser\business;

use backend\controllers\SiteController;
use common\core\oop\ObjectScalar;
use common\modules\adminUser\models\Role;
use common\modules\adminUser\models\RoleRight;
use common\Factory;
use common\utilities\ArraySimple;
use common\utilities\Common;
use common\modules\adminUser\models\User;
use common\modules\file\business\BusinessFile;
use common\business\BaseBusinessPublisher;

class BusinessAdminUser extends BaseBusinessPublisher
{
    private static $_instance;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    public static $additionalThumbnails = [
        'avatar' => ['384x384'],
    ];

    public static function getPermission()
    {
        $loggedUser = Factory::$app->user->getIdentity();
        if ($loggedUser) {
            if (Factory::$app->controller instanceof SiteController) {
                if (in_array(Factory::$app->controller->action->id, ['logout', 'index'])) {
                    return true;
                }
            }
            if ($loggedUser->username == 'sadmin') {
                return true;
            } else {
                $roleId = $loggedUser->role_id;
                $currentModule = Factory::$app->controller->module->id;
                if (empty(Factory::$app->getModule($currentModule))) {
                    $currentModule = false;
                }
                $currentController = Common::getClassName(Factory::$app->controller->className());
                $currentAction = str_replace('action', '', Factory::$app->controller->action->actionMethod);
                $roleRight = RoleRight::getPermission($roleId, $currentModule, $currentController, $currentAction);
                if ($roleRight) {
                    return true;
                }
            }
        }

        return false;
    }

    private static $_rightsOfRole;
    
    public static function getProvidedActions($roleId = null)
    {
        $rightsOfRole = RoleRight::find()
            ->select(['module', 'controller', 'action'])
            
            ->andWhere(['role_id' => $roleId])
            ->all();
        if (self::$_rightsOfRole === null) {
            self::$_rightsOfRole = Factory::createObject(ArraySimple::makeKeyPathRecursive($rightsOfRole, ['module',
                'controller',
                'action']));
        }
        
        return self::$_rightsOfRole; 
    }
    
    public function isSuperAdmin()
    {
        $iden = Factory::$app->user->getIdentity();

        return $iden && ($iden->username == 'sadmin' || $iden->primaryKey === 1);
    }

    public function isAdmin()
    {
        $iden = Factory::$app->user->getIdentity();

        return $iden && $iden->role_id === 1;
    }

    public function create(User $user, ObjectScalar $requestData)
    {
        $user->setAttributes($requestData->toArray());
        if (!$user->validate(['password_hash', 'password_hash_repeat'])) {
            return false;
        }
        if ($requestData['password_hash']) {
            $user->setPassword($requestData['password_hash']);
        } else {
            return false;
        }

        return $this->save($user);
    }

    public function update(User $user, ObjectScalar $requestData)
    {
        $user->setAttributes($requestData->toArray());
        return $this->save($user);
    }

    public function save(User $model, $validation = false, $attrs = null)
    {
        $status = $model->save($validation, $attrs);
        if ($status) {
            BusinessFile::getInstance()->doUploadAndSave($model, self::$additionalThumbnails);
        }

        return $status;

    }

    public function changePassword(User $user, ObjectScalar $requestData)
    {
        $user->setAttributes($requestData->toArray());
        $user->scenario = User::SCENARIO_CHANGE_PASSWORD;
        if (!$user->validate(['password_hash', 'password_hash_repeat'])) {
            return false;
        }

        $user->setPassword($requestData['password_hash']);

        return $user->save();
    }

    /**
     * @param $id
     * @return User
     * @throws \yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        return User::findOneOrFail($id);
    }

    public function findRolesKeyValue($fields = ['uid', 'name'])
    {
        return Role::findKeyValue($fields)->toArray();
    }

    public function findPositionsKeyValue()
    {
        return User::findKeyValue(['position', 'position'])->toArray();
    }

    public function newModel()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;
        return $model;
    }
}
