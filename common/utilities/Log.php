<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 3/8/16
 * Time: 10:56 AM
 */

namespace common\utilities;


use common\modules\systemSetting\business\BusinessSystemSetting;
use common\Factory;
use yii\log\Logger;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\LogglyHandler;
use Monolog\Formatter\LogglyFormatter;

class Log
{
    public static function toSentry($type = Logger::LEVEL_ERROR, $data)
    {
        if (class_exists('\Raven_Client')) {
            $url = BusinessSystemSetting::getInstance()->getConfig('error_warning_service_sentry');
            if (!$url) {
                return false;
            }

            $client = new \Raven_Client($url);

            if ($type == Logger::LEVEL_ERROR &&  $data instanceof \Exception) {
                $client->getIdent($client->captureException($data));
            } else {
                if (is_string($data)) {
                    $client->getIdent($client->captureMessage($data));
                }
            }

            $error_handler = new \Raven_ErrorHandler($client);
            $error_handler->registerExceptionHandler();
            $error_handler->registerErrorHandler();
            $error_handler->registerShutdownFunction();
        }

    }

    public static function toLoggy($message, $category = 'application', $level = MonologLogger::INFO)
    {
        if (class_exists('Monolog\Logger')) {
            $monolog = new MonologLogger('cms');
            $monolog->pushHandler(new LogglyHandler(BusinessSystemSetting::getInstance()->getConfig('loggy_app_token') . "/tag/$category", $level));
            $isSent = $monolog->addWarning($message);
            return $isSent;
        }
        
        return false;   
    }

}