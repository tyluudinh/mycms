<?php

use common\helpers\Html;
use common\modules\systemSetting\models\SystemSetting;
use common\utilities\Common;
use common\modules\systemSetting\business\BusinessSystemSetting;

/* @var $this \common\core\web\mvc\View */
/* @var $searchModel common\modules\systemSetting\models\SystemSettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $types array */
/**
 * @var $models array
 * @var $cachedConfigs \common\core\oop\ObjectScalar
 */

$this->title = Yii::t('app', 'System Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-setting-index">
    <?php foreach ($models as $key => $values): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title panel-title-with-right-btn">
                    <b><?= BusinessSystemSetting::getTypes($key); ?></b>
                    <span class="pull-right"><?= \common\helpers\Html::updownBtn($key, false) ?></span>
                </h3>
            </div>
            <div class="panel-body" id="<?= $key ?>">
                <?= $this->render('_index_by_type', ['values' => $values]) ?>                
            </div>
        </div>
    <?php endforeach; ?>
</div>
