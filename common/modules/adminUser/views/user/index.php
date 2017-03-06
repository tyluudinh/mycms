<?php

use common\helpers\Html;
use common\core\web\mvc\grid\BaseGridView;
use common\modules\adminUser\models\User;
use yii\widgets\Pjax;
use common\utilities\Common;
use common\core\web\mvc\grid\BaseActionColumn;


/* @var $this \common\core\web\mvc\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\modules\adminUser\models\UserSearch */

/**
 * @var $roles array
 * @var $positions array
 */

$this->title                   = Yii::t('app', 'Admin Users');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Create Admin User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php Pjax::begin(); ?>
    <?= BaseGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'email',
            'username',
            'fullname',
            'role_id'  => [
                'attribute' => 'role_id',
                'format'    => 'raw',
                'value'     => function (User $model) {
                    return $model->role ? $model->role->name : "";
                },
                'filter'    => Html::activeDropDownList($searchModel, 'role_id', $roles, [
                    'class'  => 'form-control',
                    'prompt' => 'All'
                ]),
            ],
            'position' => [
                'attribute' => 'position',
                'format'    => 'raw',
                'filter'    => Html::activeDropDownList($searchModel, 'position', $positions, [
                    'class'  => 'form-control',
                    'prompt' => 'All'
                ]),
            ],
            'dob:date',
            'status'   => [
                'attribute' => 'status',
                'value'     => function (User $model) {
                    return Common::getStrStatus($model->status);
                },
                'filter'    => Html::activeDropDownList($searchModel, 'status', Common::getStatusArr(), [
                    'class'  => 'form-control',
                    'prompt' => 'All'
                ]),
            ],

            [
                'class'    => BaseActionColumn::class,
                'template' => '{password} {view} {update} {delete}',
                'buttons'  => [
                    'password' => function ($url, User $model) {
                        return Html::a('<span class="fa fa-fw fa-key"></span>', [
                            'reset-password',
                            'id' => $model->id
                        ], [
                            'title' => 'Reset Password',
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>