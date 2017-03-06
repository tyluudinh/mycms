<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 3/7/16
 * Time: 1:21 PM
 */

namespace common\core\web;


use common\utilities\Log;
use yii\log\Logger;

class ErrorHandler extends \yii\web\ErrorHandler
{
    protected function renderException($exception)
    {
        if (YII_ENV_PROD) {
            Log::toSentry(Logger::LEVEL_ERROR, $exception);
        }
        parent::renderException($exception);

    }


}