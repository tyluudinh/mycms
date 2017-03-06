<?php

/**
 * Created by thangcest2@gmail.com
 * Date 12/12/15
 * Time 3:22 PM
 */

namespace common\core\web;
use Yii;

/**
 * @property \common\core\web\http\Request $request
 *
 * Class Application
 * @package common\core\web
 */
class Application extends \yii\web\Application
{
//    public $language = 'vi-VN';

    public function getHomeUrl()
    {
        $u = parent::getHomeUrl();
        if (substr($u, -1) !== '/') {
            $u .= '/';
        }
        return $u;
    }

    public function __construct($config)
    {
        parent::__construct($config);

    }
    public function getAppMessages() {
        return [
            'successUpdate' => Yii::t('app','You updated successfully %s items.'),
            'errorChoose' => Yii::t('app','You haven\'t choose any item yet!'),
            'confirmActionText' => Yii::t('app','Are you sure want to %s checked items?'),
            'confirmDeleteText' => Yii::t('app','Are you sure you want to delete this item?'),
            'submit' => Yii::t('app','Submit'),
            'close' => Yii::t('app','Close'),
            'updating' => Yii::t('app','Updating'),
            'deleting' => Yii::t('app', 'Deleting'),
            'delete' => Yii::t('app', 'Delete'),
            'confirmSubmitForm' => Yii::t('app', 'Are you sure want to submit your data ?')
        ];
    }

}