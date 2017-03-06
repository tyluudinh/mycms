<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/10/16
 * Time: 9:57 AM
 */

namespace common\modules\adminUser\controllers;


use backend\controllers\BackendBaseController;
use common\modules\adminUser\models\Role;
use common\modules\adminUser\models\RoleRight;
use common\Factory;

class RoleRightController extends BackendBaseController
{
    public function init()
    {
        $this->category = 'adminUser';
        parent::init();
    }

    public function actionIndex($role_id = 0)
    {
        $roleRights = require APPROOT . '/common/modules/adminUser/config/rights.php';
        if (Factory::$app->request->post()) {
            $datas      = Factory::$app->request->post();
            $owner      = isset($datas['owner']) ? $datas['owner'] : array();
            $rightsPost = isset($datas['rights']) ? $datas['rights'] : array();
            RoleRight::deleteAll(array('role_id' => $role_id), array(), true);
            if ($rightsPost) {
                foreach ($rightsPost as $controller => $actionData) {
                    if ($controller == 'modules') continue;
                    if ($actionData) {
                        foreach ($actionData as $action => $isOn) {
                            $saveData               = array();
                            $saveData['controller'] = $controller;
                            $saveData['action']     = $action;
                            $saveData['role_id']    = $role_id;

                            if (isset($owner[$controller][$action])) {
                                $saveData['is_owner']                               = 1;
                                $rights[$controller]['action'][$action]['is_owner'] = 1;
                            }
                            $rights[$controller]['action'][$action]['checked'] = 1;
                            $model                                             = new RoleRight();
                            $model->load(array('RoleRight' => $saveData));
                            $model->save();
                        }
                    }

                }

                if (isset($rightsPost['modules'])) {
                    foreach ($rightsPost['modules'] as $module => $moduleControllers) {
                        foreach ($moduleControllers as $controller => $actionData) {
                            if ($actionData) {
                                foreach ($actionData as $action => $isOn) {
                                    $saveData               = array();
                                    $saveData['module']     = $module;
                                    $saveData['controller'] = $controller;
                                    $saveData['action']     = $action;
                                    $saveData['role_id']    = $role_id;

                                    if (isset($owner[$controller][$action])) {
                                        $saveData['is_owner']                               = 1;
                                        $rights[$controller]['action'][$action]['is_owner'] = 1;
                                    }
                                    $rights[$controller]['action'][$action]['checked'] = 1;
                                    $model                                             = new RoleRight();
                                    $model->load(array('RoleRight' => $saveData));
                                    $model->save();
                                }
                            }

                        }
                    }

                }

                flassSuccess();

                return $this->refresh();

            }
        } else {
            $rightsDbs        = RoleRight::findAll(array('role_id' => $role_id));
            $rightsDbFormated = [];
            if ($rightsDbs) {
                foreach ($rightsDbs as $key => $obj) {
                    if ($obj->module) {
                        $rightsDbFormated['modules'][$obj->module][$obj->controller][$obj->action] = $obj;
                    } else {
                        $rightsDbFormated[$obj->controller][$obj->action] = $obj;
                    }
                }
            }
            foreach ($roleRights as $controller => $actionData) {
                if ($controller == 'modules') continue;
                if ($actionData) {
                    foreach ($actionData['action'] as $action => $a) {
                        if (isset($rightsDbFormated[$controller][$action])) {
                            $roleRights[$controller]['action'][$action]['checked'] = 1;
                        }
                    }
                }

            }

            if (isset($roleRights['modules']) && is_array($roleRights['modules'])) {
                foreach ($roleRights['modules'] as $module => $moduleControllers) {
                    if ($moduleControllers) {
                        foreach ($moduleControllers as $controller => $actionData) {
                            if ($actionData) {
                                foreach ($actionData['action'] as $action => $a) {
                                    if (isset($rightsDbFormated['modules'][$module][$controller][$action])) {
                                        $roleRights['modules'][$module][$controller]['action'][$action]['checked'] = 1;
                                    }

                                }
                            }

                        }

                    }
                }

            }
        }
        $role = Role::findOneOrFail($role_id);

        return $this->render('index', [
            'rights' => $roleRights,
            'role'   => $role
        ]);


    }


    /**
     * generete structure
     */
    public function actionGenerate()
    {
        $path            = APPROOT . '/backend/controllers/';
        $rights          = [];
        $controllerFiles = scandir($path);
        $this->_generateRoleRiles($rights, $controllerFiles, $path);

        $moduleFolders = scandir(APPROOT . '/common/modules/');
        foreach ($moduleFolders as $moduleFolder) {
            if (!in_array($moduleFolder, ['.', '..']) && is_dir(APPROOT . '/common/modules/' . $moduleFolder)) {
                $modulePath = APPROOT . "/common/modules/$moduleFolder/controllers/";
                if (is_dir($modulePath)) {
                    $moduleControllerFiles = scandir($modulePath);
                    $this->_generateRoleRiles($rights['modules'][$moduleFolder], $moduleControllerFiles, $modulePath);
                }
            }
        }

        $roleRightsFile = APPROOT . '/common/modules/adminUser/config/rights.php';
        file_put_contents($roleRightsFile, "<?php\nreturn " . str_replace('(int) ', '', var_export($rights, true)) . ';');
        die("Generate new right successfully");
    }

    private function _generateRoleRiles(&$rights, $files, $path)
    {
        foreach ($files as $file) {
            if (is_file($path . $file)) {
                if (strpos($file, 'Controller.php') !== false) {
                    $str = file_get_contents($path . $file);
                    preg_match_all('/public\s+function\s+(.+)\s*\(/', $str, $funcs);
                    if (count($funcs[1]) == 0) {
                        continue;
                    }
                    $controller = str_replace('.php', '', $file);
                    if (!in_array($controller, array('BackendBaseController'))) {
                        if (!isset($rights[$controller]['name'])) {
                            $rights[$controller]['name'] = str_replace('Controller', '', $controller);
                        }
                        foreach ($funcs[1] as $func) {
                            if (in_array($func, array(
                                'init',
                                'beforeAction',
                                'afterAction',
                                'setVars',
                                'behaviors',
                                'actions',
                                'beforeFilter',
                                'beforeRender',
                                'afterFilter',
                                'actionLogin',
                                'actionLogout',
                                'ActionDenied'
                            ))) {
                                continue;
                            }
                            if (!isset($rights[$controller]['action'][$func])) {
                                //$func = strtolower($func);
                                $func                                         = str_replace('action', '', $func);
                                $rights[$controller]['action'][$func]['name'] = $func;
                            }
                        }
                    }
                }
            }
        }

    }

}