<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\adminUser\models\Role;
use common\modules\file\widgets\FileUploadWidget;
use \yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DateTimePicker;
use common\core\web\mvc\form\BaseActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\adminUser\models\User */
/* @var $form BaseActiveForm */
/**
 * @var $roles array
 */
?>

<div class="card user-form">
    <div class="card-header">
        <?= $this->title; ?>
    </div>

    <div class="card-body">
        <?php $form = BaseActiveForm::beginMultipart(); ?>

        <?= $form->field($model, 'email')->textInput(['rows' => 6]) ?>

        <?php if ($model->isNewRecord): ?>
            <?= $form->field($model, 'password_hash')->passwordInput(['rows' => 6]) ?>
            <?= $form->field($model, 'password_hash_repeat')->passwordInput(['rows' => 6]) ?>
        <?php endif; ?>

        <?= FileUploadWidget::widget([
            'form' => $form,
            'sourceModel' => $model,
            'attr' => 'avatar',
            'options' => [
                'accept' => 'image/*',
            ]
        ]) ?>

        <?= $form->field($model, 'role_id')->dropDownList($roles, ['class' => 'select2', 'prompt' => Yii::t('app', 'Select one')]) ?>

        <?= $form->field($model, 'position')->textInput(['rows' => 6]) ?>

        <?= $form->field($model, 'dob')->dateInput(); ?>

        <?= $form->field($model, 'fullname')->textInput(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->dropDownList(\common\utilities\Common::getStatusArr()) ?>

        <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php BaseActiveForm::end(); ?>

    </div>

</div>
