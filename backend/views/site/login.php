<?php

/* @var $this yii\web\View */
/* @var $form \common\core\web\mvc\form\BaseActiveForm */
/* @var $model \backend\models\LoginForm */

use yii\helpers\Html;
use common\core\web\mvc\form\BaseActiveForm;


$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?php $form = BaseActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Login', ['class' => 'btn btn-success btn-submit', 'value' => 'Login']) ?>
    </div>

    <?php BaseActiveForm::end(); ?>
</div>
