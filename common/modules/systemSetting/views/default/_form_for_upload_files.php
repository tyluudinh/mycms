<?php
/**
 * Created by PhpStorm.
 * User: tyluu
 * Date: 10/11/2016
 * Time: 16:54
 */
use common\helpers\Html;
use common\core\web\mvc\form\BaseActiveForm;
use common\utilities\Common;

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
                    <?php $form = BaseActiveForm::beginMultipart(); ?>

                    <?= $form->field($model, 'key')->textInput(['disabled' => true]) ?>

                    <?= $form->field($model, 'value')->textInput(['value' => $model->value ? $model->value : 'The link download' ]) ?>

                    <?= $form->field($model, 'type')->dropDownListWithPrompt($types,['disabled' => true]); ?>

                    <?= $form->field($model, 'explain')->textarea(['rows' => 6]) ?>

                    <?= Html::label(Yii::t('app', 'File Upload')) ; ?>
                    <?= \kartik\file\FileInput::widget([
                        'name' => "SystemSetting[files]",
                        'id'   => 'system-files',
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => false,
                        ],
                        'pluginEvents' => [
                            'fileclear' => "function() { jQuery('#fileOfsystem-files').show(); }",
                            "change" => "function() { jQuery('#fileOfsystem-files').hide(); }",
                        ],
                        'options' => [
                            'accept' => '*',
                        ]
                    ]); ?>

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