<?php

use yii\helpers\Html;

/* @var $this \common\core\web\mvc\View */
/* @var $model common\modules\adminUser\models\Role */

$this->title = Yii::t('app', 'Update Admin Role: {modelName}', ['modelName' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="role-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form') ?>

</div>
