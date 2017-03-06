<?php
/**
 * Created by dinhty.luu@gmail.com
 * Date: 23/10/2016
 * Time: 22:25
 */

namespace backend\widgets;

use Yii;
use common\helpers\Html;
use common\Factory;


class Alert extends \yii\bootstrap\Widget
{
    public $additionalErrorClass;

    public $alertTypes = [
        'error' => 'toast toast-error',
        'info' => 'toast toast-info',
        'success' => 'toast toast-success',
        'warning' => 'toast toast-warning'
    ];

    public $colorType = [
        'error' => 'cp-icn-alert-error',
        'danger' => 'cp-icn-alert-error',
        'success' => 'cp-icn-alert-success',
        'info' => 'cp-icn-alert-success',
        'warning' => 'cp-icn-alert-warning'
    ];

    public $closeButton = [
        'class' => 'cp-btn-close',
        'data-dismiss' => 'alert',
        'type' => 'button',
    ];

    public function renderMessage($type, $message)
    {
        /**
         * <div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
         * <div class="toast toast-success" style="display: block;">
         * <div class="toast-message">Inconceivable!</div>
         * </div>
         * </div>
         * **/
        Factory::$app->controller->addJsParams([
            'toast' => [
                'type' => $type,
                'message' => $message
            ]
        ]);
        return Html::beginTag('div', ['id' => 'toast-container', 'class' => 'toast-top-right', 'aria-live' => "polite" ,'role' => "alert"]) . "\n" .
                    Html::beginTag('div', ['class' => $this->alertTypes[$type], 'display' => 'block']) . "\n" .
                        Html::beginTag('div', ['class' => "toast-message"]) . "\n" .
                            $message .
                        Html::endTag('div').
                    Html::endTag('div').
                Html::endTag('div');
    }

    public function init()
    {
        $this->alertTypes['error'] .= ' ' . $this->additionalErrorClass;
        parent::init();
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array)$data;
                foreach ($data as $i => $message) {
                    /* initialize css class for each alert box */
//                    $this->options['class'] = $this->alertTypes[$type] . ' cp-alert';
//
//                    /* assign unique id to each alert box */
                    $this->options['id'] = 'toast-component';

                    $message = $this->renderMessage($type, $message);

                    echo \yii\bootstrap\Alert::widget([
                        'body' => $message,
                        'options' => $this->options,
                    ]);
                }

                $session->removeFlash($type);
            }
        }
    }


}