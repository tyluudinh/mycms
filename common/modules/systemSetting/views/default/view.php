<?php

use common\helpers\Html;
use common\core\web\mvc\widget\BaseDetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\systemSetting\models\SystemSetting */

$this->title = $model->key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-setting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btnDelete',
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('app', 'System Setting Information'); ?></div>
                </div>
                <div class="panel-body">
                    <?= BaseDetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'key:ntext',
                            'value:ntext',
                            'type',
                            'explain:ntext',
                            'status',
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
