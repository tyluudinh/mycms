<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 11/1/16
 * Time: 3:32 PM
 */

namespace common\modules\adminUser\console;

use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class GenerateController extends Controller
{
    /**
     * @var \yii\gii\Module
     */
    public $module;

    public function actionIndex()
    {
        $this->run('/help', ['adminUser']);
    }
    
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
        
        $oldRoleRights = require $roleRightsFile;
        if ($rights != $oldRoleRights) {
            file_put_contents($roleRightsFile, "<?php\nreturn " . str_replace('(int) ', '', var_export($rights, true)) . ';');
            echo $this->ansiFormat(Yii::t('app', 'Generate new rights successfully'), Console::FG_RED) . PHP_EOL;
        } else {
            echo $this->ansiFormat(Yii::t('app', 'There are no new function to update'), Console::FG_GREEN) . PHP_EOL;
        }
        
        return 0;
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