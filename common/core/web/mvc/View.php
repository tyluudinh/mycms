<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 1/19/16
 * Time: 9:33 AM
 */

namespace common\core\web\mvc;


class View extends \yii\web\View
{
    public $jsConstants = [];
    
    public $globalParams = [];

    public $cdnLink = null;

    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function render($view, $params = [], $context = null)
    {
        if (empty($this->globalParams)) {
            $this->globalParams = $params;
        }

        $params = $params + $this->globalParams;
        return parent::render($view, $params, $context);
    }

}