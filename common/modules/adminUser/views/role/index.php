<?php

use yii\helpers\Html;
use common\core\web\mvc\grid\BaseGridView;
use common\modules\adminUser\models\Role;
use common\core\web\mvc\grid\BaseActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Admin Role'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= BaseGridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'name:ntext',
            'status:status',

            [
                'class'    => BaseActionColumn::class,
                'template' => ' {rights} {view} {update} {delete}',
                'buttons'  => [
                    'rights' => function ($url, Role $model) {
                        return Html::a('permission', \yii\helpers\Url::to([
                            '/adminUser/role-right/index',
                            'role_id' => $model->id
                        ]), [
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
