<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\adminUser\models\User */

$this->title = Yii::t('app', 'Create Admin User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form') ?>

</div>
