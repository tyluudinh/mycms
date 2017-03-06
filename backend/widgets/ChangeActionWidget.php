<?php

namespace backend\widgets;
use yii\base\Widget;

/**
 * Created by TyLuuDinh.
 * Date: 23/08/2016
 * Time: 18:22
 */
class ChangeActionWidget extends Widget
{
    public $className;
    public function run()
    {
        return $this->render('change_action_widget',[
            'className' => $this->className,
        ]);
    }
}