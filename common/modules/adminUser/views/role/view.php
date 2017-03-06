<?php

use yii\helpers\Html;
use common\core\web\mvc\widget\BaseDetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\adminUser\models\Role */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger btnDelete']) ?>
    </p>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('app', 'Role Information'); ?></div>
                </div>
                <div class="panel-body">
                    <?= BaseDetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'name:ntext',
                            'status:status',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
