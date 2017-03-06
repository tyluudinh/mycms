<?php

use yii\helpers\Html;


/* @var $this \common\core\web\mvc\View */
/* @var $model common\modules\adminUser\models\Role */

$this->title = Yii::t('app', 'Create Admin Role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form') ?>

</div>
