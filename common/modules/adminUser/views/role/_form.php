<?php

use yii\helpers\Html;
use common\core\web\mvc\form\BaseActiveForm;

/* @var $this \common\core\web\mvc\View */
/* @var $model common\modules\adminUser\models\Role */
/* @var $form BaseActiveForm */
?>

<div class="role-form">

    <?php $form = BaseActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('app', 'Role Information'); ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'name')->textInput() ?>

                    <?= $form->field($model, 'status')->dropDownList(\common\utilities\Common::getStatusArr()) ?>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php BaseActiveForm::end(); ?>

</div>
