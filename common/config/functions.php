<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 4/20/16
 * Time: 9:52 AM
 */

if (!function_exists('adminuser')) {
    function adminuser($autoRenew = false) {
        return \common\Factory::$app->user->getIdentity($autoRenew);
    }
}

if (!function_exists('flassSuccess')) {
    function flassSuccess($succ = false) {
        if ($succ) {
            return \common\Factory::$app->session->setFlash('success', $succ);
        }
        return \common\Factory::$app->session->setFlash('success', Yii::t('app', 'Your data is saved successfully.'));
    }
}

if (!function_exists('flassError')) {
    function flassError($err = false) {
        if ($err) {
            return \common\Factory::$app->session->setFlash('error', $err);

        }
        return \common\Factory::$app->session->setFlash('error', Yii::t('app', 'Cannot save your data.'));
    }
}
if (!function_exists('flassWarning')) {
    function flassWarning($warning = false) {
        if ($warning) {
            return \common\Factory::$app->session->setFlash('warning', $warning);

        }
        return \common\Factory::$app->session->setFlash('warning', Yii::t('app', 'Warning your server!'));
    }
}

function fileuploaded() {

}

//var_dump and die()
if (!function_exists('dd')) {
    function dd($var) {
        $backtrace = debug_backtrace();
        $fileinfo = null;
        if (!empty($backtrace[0]) && is_array($backtrace[0])) {
            $fileinfo = $backtrace[0]['file'] . ":" . $backtrace[0]['line'];
        }
        echo "Debug at: $fileinfo</br>";
        var_dump($var);
        die;
    }
}

if (!function_exists('pd')) {
    function pd($var) {
        $backtrace = debug_backtrace();
        $fileinfo = null;
        if (!empty($backtrace[0]) && is_array($backtrace[0])) {
            $fileinfo = $backtrace[0]['file'] . ":" . $backtrace[0]['line'];
        }
        echo "Debug at: $fileinfo</br>";
        echo "<pre>";
        print_r($var);
        die;
    }
}
