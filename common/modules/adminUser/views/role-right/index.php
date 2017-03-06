<?php
use yii\helpers\Html;
use common\core\web\mvc\form\BaseActiveForm;


$this->title = Yii::t('app', 'Roles Permission');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['/adminUser/role/index']];
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $role \common\modules\adminUser\models\Role
 * @var $rights array
 */

?>
<h3>
    <?= Yii::t('app', "Update Role Permission: <i>{roleName}</i>", ['roleName' => $role->name]); ?>
</h3>

<hr/>

<div class="posts form">

    <?php $form = BaseActiveForm::begin(); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Yii::t('app', 'Base permissions'); ?></b> | <a
                    class="btn btn-sm btn-primary checkAll"><?= Yii::t('app', 'Check all'); ?></a>
                <span class="pull-right"><?= \common\helpers\Html::updownBtn('mainc', false) ?></span>
            </h3>
        </div>
        <div class="panel-body" id="mainc">
            <?php foreach ($rights as $controller => $dataController): ?>
                <?php if ($controller == 'modules') continue; ?>
                <h4>
                    <b><?= $dataController['name'] ?></b>
                </h4>

                <?php foreach ($dataController['action'] as $action => $dataAction): ?>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <input type="checkbox" <?php if (isset($dataAction['checked'])) echo 'checked' ?>
                                   name="rights[<?php echo $controller ?>][<?php echo $action ?>]">

                            <?php echo $action ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endforeach; ?>
        </div>
    </div>

    <?php if (isset($rights['modules']) && is_array($rights['modules'])): ?>
        <?php foreach ($rights['modules'] as $moduleName => $moduleControllers): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span style="color: red"><?= Yii::t('app', 'Module'); ?></span>: <b><?= $moduleName ?></b> | <a
                            class="btn btn-sm btn-primary checkAll"><?= Yii::t('app', 'Check all'); ?></a>
                        <span class="pull-right"><?= \common\helpers\Html::updownBtn("module{$moduleName}", false) ?></span>
                    </h3>
                </div>
                <div class="panel-body" id="module<?= $moduleName ?>">
                    <?php if ($moduleControllers): ?>
                        <?php foreach ($moduleControllers as $controller => $dataController): ?>
                            <div style="margin-left: 50px;">
                                <h5><b><?= $dataController['name'] ?></b></h5>

                                <?php foreach ($dataController['action'] as $action => $dataAction): ?>
                                    <div>
                                        <div class="checkbox">
                                            <input
                                                type="checkbox" <?php if (isset($dataAction['checked'])) echo 'checked' ?>
                                                name="rights[modules][<?= $moduleName ?>][<?php echo $controller ?>][<?php echo $action ?>]">
                                            <?php echo $action ?>
                                        </div>

                                    </div>
                                <?php endforeach; ?>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php
    echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-large btn-primary']);
    BaseActiveForm::end();
    ?>
</div>

<script>
    $('body')
        .on('click', '.checkAll', function () {
            var t = $(this);
            t.parents('.panel-heading').siblings('.panel-body').find('input').prop('checked', true);
            t.addClass('uncheckAll').removeClass('checkAll').text('<?= Yii::t('app', 'Uncheck All') ?>');
        })
        .on('click', '.uncheckAll', function () {
            var t = $(this);
            t.parents('.panel-heading').siblings('.panel-body').find('input').prop('checked', false);
            t.addClass('checkAll').removeClass('uncheckAll').text('<?= Yii::t('app', 'Check All') ?>');
        })
</script>