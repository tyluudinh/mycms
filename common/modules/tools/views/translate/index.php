<?php
/**
 * Created by TyLuuDinh.
 * Date: 28/09/2016
 * Time: 14:32
 */

use common\helpers\Html;
use common\core\web\mvc\form\BaseActiveForm;
use common\modules\tools\business\BusinessTranslate;


/* @var $this \common\core\web\mvc\View */
/* @var $form BaseActiveForm */

/**
 * @var $languages array
 * @var $translation array
 * @var $keyLanguage string
 * @var $form BaseActiveForm
 */



$this->title = Yii::t('app', 'Translate');
$this->params['breadcrumbs'][] = Yii::t('app', 'Tools');
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = Yii::t('app', 'English') . ' - ' . $languages[$keyLanguage];
?>

<div class="manage-language">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title panel-title-with-right-btn">
                <b><?= Yii::t('app', 'Options'); ?></b>
                <span class="pull-right"><?= \common\helpers\Html::updownBtn('language-option', false) ?></span>
            </h3>
        </div>
        <div class="panel-body" id="language-option">
            <?php $formImport = BaseActiveForm::beginMultipart(['action' => ['import', 'language' => $keyLanguage]]) ?>
            <label class="control-label"><?= Yii::t('app', 'Import File'); ?></label>
            <?= \kartik\file\FileInput::widget([
                'name' => 'Translate[import]',
                'id' => 'languageImport',
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                ],
                'pluginEvents' => [
                    'fileclear' => "function() { jQuery('#fileOflanguageImport').show(); }",
                    "change" => "function() { jQuery('#fileOflanguageImport').hide(); }",
                ],
                'options' => [
                    'accept' => '/*',
                ]
            ]); ?>
            <?= Html::a('<i class="glyphicon glyphicon-save"></i>' . Yii::t('app', 'Export'),
                ['export','language' => $keyLanguage],
                ['class' => 'btn btn-warning btnExport']
            ); ?>
            <?= Html::submitButton('<i class="glyphicon glyphicon-open"></i>' . Yii::t('app', 'Import'),
                ['class' => 'btn btn-primary btnImport']
            ); ?>
            <?php BaseActiveForm::end(); ?>
        </div>
    </div>
    <div>
        <?php $form = BaseActiveForm::begin(['action' => ['index', 'language' => $keyLanguage]]) ?>
        <div class="form-group">
            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-success btnSave']) ?>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">

                <thead>
                <tr>
                    <th width="50%"><?= Yii::t('app', 'English'); ?></th>
                    <th><?= $languages[$keyLanguage]; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($translation as $key => $value): ?>
                    <tr>
                        <td><?= $key; ?></td>
                        <td>
                            <?= Html::textInput('language[' . $key . ']', $value, ['class' => 'form-control', 'row' => 8]); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
        <?php BaseActiveForm::end(); ?>
    </div>
</div>

