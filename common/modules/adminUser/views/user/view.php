<?php

use yii\helpers\Html;
use common\core\web\mvc\widget\BaseDetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\adminUser\models\User */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="card">
        <div class="card-body app-heading">
            <img class="profile-img" src="../assets/images/profile.png">
            <div class="app-title">
                <div class="title"><span class="highlight">Scott White</span></div>
                <div class="description">Frontend Developer</div>
            </div>
            <p>
                <?= Html::a('Update', ['update', 'id' => $model->uid], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->uid], ['class' => 'btn btn-danger btnDelete']) ?>
            </p>
        </div>
        <div class="card-body app-heading">
            <?= BaseDetailView::widget([
                'model' => $model,
                'attributes' => [
                    'role_id' => ['value' => $model->role ? $model->role->name : null],
                    'position:raw',
                    'fullname:raw',
                    'email:email',
                    'username:raw',
                    'dob:datetime',
                    'status',
                    'position',
                    'desc:ntext',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                ],
            ]) ?>
        </div>
    </div>
</div>
