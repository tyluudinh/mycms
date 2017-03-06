<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/6/16
 * Time: 2:12 PM
 */

/**
 * @var $sourceModel \common\core\web\mvc\BaseModel
 * @var $formName string
 * @var $attr string
 * @var $options array
 * @var $uniqueId string
 */

$inputId = $formName . "-$attr" . "-" . $uniqueId;
$name = $formName . "[$attr]";
?>

<br/>
<div id="fileView<?= $inputId ?>" style="max-width: 400px" class="file-upload-area">
    <?php if (!isset($options['multiple']) || !($options['multiple']) && !empty($sourceModel->{$attr})): ?>
        <?= \common\Factory::$app->formatter->asImage($sourceModel->{$attr}, ['class' => 'having']); ?>
        <?php if ($sourceModel->{$attr} !== null): ?>
            <?= \common\Factory::$app->formatter->asImage(null, ['class' => 'nothing hidden']) ?>
            <a class="mark-file-as-deleted text-danger"><i class="fa fa-close"></i></a>
        <?php endif; ?>
        <input type="hidden" class="deleting-images" name="deletingFiles[<?= $formName ?>][<?= $attr ?>]" value="0">
    <?php endif; ?>
</div>
<div class="clearfix"></div>
<br/>
<div id="fileOf<?= $inputId ?>" style="max-width: 400px">

</div>
<br/>

<div style="max-width: 400px;">
    <label for=""><?= isset($options['label']) ? $options['label'] : $sourceModel->attributeLabels()[$attr] ?></label>
    <?= \kartik\file\FileInput::widget([
        'name' => $name,
        'id' => $inputId,
        'pluginOptions' => [
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
        ],
        'pluginEvents' => [
            'fileclear' => "function() { jQuery('#fileOf$inputId').show(); }",
            "change" => "function() { jQuery('#fileOf$inputId').hide(); }",
        ],
        'options' => $options
    ]); ?>

</div>

<br/>
