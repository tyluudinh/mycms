<?php

use yii\helpers\Html;

/* @var $this \common\core\web\mvc\View */
/* @var $model common\modules\systemSetting\models\SystemSetting */

$this->title = Yii::t('app', 'Update System Setting: {key}', ['key' => $model->key]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->key, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="system-setting-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if($model->type == $model::TYPE_UPLOAD_FILES) :?>
        <?= $this->render('_form_for_upload_files', [
            'model' => $model,
        ]) ?>
    <?php else:?>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    <?php endif;?>

</div>
