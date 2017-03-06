<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 10/12/16
 * Time: 5:15 PM
 */
use common\modules\systemSetting\models\SystemSetting;
use common\utilities\Common;
use common\helpers\Html;

/**
 * @var $values array
 * @var $models array
 * @var $cachedConfigs \common\core\oop\ObjectScalar
 * @var $ref string
 */

?>

<table class="table table-hover table-bordered systemTable">
    <thead>
    <tr>
        <th><?= SystemSetting::labelOf('type'); ?></th>
        <th><?= SystemSetting::labelOf('key'); ?></th>
        <th><?= SystemSetting::labelOf('value'); ?></th>
        <th><?= SystemSetting::labelOf('explain'); ?></th>
        <th><?= SystemSetting::labelOf('status'); ?></th>
        <th><?= Yii::t('app', 'Action'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($values as $value): ?>
        <tr class="<?= $value['value'] !== $cachedConfigs[$value['key']]['value'] ? 'danger' : null ?>">
            <td><?= $value['type'] ?: Yii::t('app', 'No Type'); ?></td>
            <td><?= $value['key']; ?></td>
            <td><?= \common\Factory::$app->formatter->asJsonTable($value['value']); ?></td>
            <td><?= $value['explain']; ?></td>
            <td><?= Common::getStrStatus($value['status']); ?></td>
            <td>
                <?=
                Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['/systemSetting/default/update', 'id' => $value['id'], 'ref' => isset($ref) ? $ref : null],
                    ['title' => Yii::t('app', 'Update')]);; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>