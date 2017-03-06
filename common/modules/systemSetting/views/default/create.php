<?php

use common\helpers\Html;


/* @var $this \common\core\web\mvc\View */
/* @var $model common\modules\systemSetting\models\SystemSetting */

$this->title = Yii::t('app', 'Create System Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
