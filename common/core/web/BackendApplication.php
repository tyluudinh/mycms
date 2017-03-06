<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 3/10/16
 * Time: 10:39 AM
 */

namespace common\core\web;
use backend\controllers\BackendBaseController;
use common\modules\adminUser\components\AdminUserComponent;

/**
 * @property BaseFormatter $formatter
 * @property AdminUserComponent $user
 * @property BackendBaseController $controller
 * @property \common\core\web\mvc\View $view
 *
 * Class Application
 * @package common\core\web
 */


class BackendApplication extends Application
{
    public $name = 'My - CMS';

    public $version = '1.0';

    public function getAppMessages()
    {
        $arrMessages = [];
        return array_merge($arrMessages, parent::getAppMessages());

    }

}
