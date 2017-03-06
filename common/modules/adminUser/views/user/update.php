<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\adminUser\models\User */

$this->title = Yii::t('app', 'Update User: {username}', ['username' => $model->username]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <?= $this->render('_form') ?>

</div>
