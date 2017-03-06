<?php

use common\helpers\Html;
use common\core\web\mvc\form\BaseActiveForm;
use common\utilities\Common;
use \common\modules\systemSetting\models\SystemSetting;

/* @var $this \common\core\web\mvc\View */
/* @var $model common\modules\systemSetting\models\SystemSetting */
/* @var $form BaseActiveForm */
/* @var $types array */
?>

<div class="system-setting-form">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('app', 'System Setting Information'); ?></div>
                </div>
                <div class="panel-body">
                    <?php $form = BaseActiveForm::begin(); ?>

                    <?= $form->field($model, 'key')->textInput(['disabled' => true]) ?>

                    <?= $form->field($model, 'value')->textarea(['rows' => 6, 'class' => $model->key == SystemSetting::KEY_TEXT_DESCRIPTION_OF_PAGE_ABOUT ? 'ckeditor' : null]) ?>

                    <?= $form->field($model, 'type')->dropDownListWithPrompt($types); ?>

                    <?= $form->field($model, 'explain')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'status')->dropDownList(Common::getStatusArr()) ?>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>

                    <?php BaseActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
