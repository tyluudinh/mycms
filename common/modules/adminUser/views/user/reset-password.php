<?php

use yii\helpers\Html;
use common\core\web\mvc\form\BaseActiveForm;
/* @var $this yii\web\View */
/* @var $model common\modules\adminUser\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Reset Password For User: {username}', ['username' => $model->username]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="user-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = BaseActiveForm::begin(); ?>

    <?= $form->field($model, 'password_hash')->passwordInput(['rows' => 6,'value'=>'']) ?>

    <?= $form->field($model, 'password_hash_repeat')->passwordInput(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Reset Password'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BaseActiveForm::end(); ?>

</div>
