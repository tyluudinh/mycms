<?php
/**
 * Created by PhpStorm.
 * User: tyluu
 * Date: 08/08/2016
 * Time: 17:47
 */

namespace common\core\web\mvc\grid;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use Yii;

class BaseActionColumn extends ActionColumn
{
    public function init()
    {
        $this->header = Yii::t('app', 'Actions');
        $this->headerOptions = ['class' => 'action-column', 'label' => Yii::t('app', 'Actions')];
//
//        $this->buttons['delete'] = function ($url, $model, $key) {
//            $options = array_merge([
//                'title' => Yii::t('app', 'Delete'),
//                'aria-label' => Yii::t('app', 'Delete'),
//                'class' => 'btnDelete',
//                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
//            ], $this->buttonOptions);
//            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
//        };
        parent::init();

    }
    
}