<?php
/**
 * Created by dinhty.luu@gmail.com
 * Date: 20/10/2016
 * Time: 17:33
 */
use common\utilities\Common;

/**
 * @var $model \common\models\BaseModel
 * @var $title string
 * @var $className string
<<<<<<< HEAD
 * @var $dataProvider \yii\data\DataProviderInterface|null
 * @var $subTitle string
=======
 * @var $totalItems int
>>>>>>> develop
 */
?>
<header>
    <div class="cp-card-title">
        <div class="cp-typo-title"><?= $title ?></div>
        <?php if (isset($subTitle)): ?>
            <div class="cp-typo-text" style="font-style: italic;">
                <?= $subTitle ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($className)): ?>
        <div class='cp-btn-status'>
            <button type='button' class='cp-btn cp-btn-success btnChangeStatus'
                    data-status='<?= STATUS_ACTIVE ?>'
                    data-className='<?= $className ?>'><?= Common::getStrStatus(STATUS_ACTIVE) ?></button>
            <button type='button' class='cp-btn cp-btn-deactive btnChangeStatus'
                    data-status='<?= STATUS_HIDE ?>'
                    data-className='<?= $className ?>'><?= Common::getStrStatus(STATUS_HIDE) ?></button>
            <button type='button' class='cp-btn cp-btn-danger btnChangeStatus'
                    data-status='<?= STATUS_DISABLED ?>'
                    data-className='<?= $className ?>'><?= Common::getStrStatus(STATUS_DISABLED) ?></button>
        </div>
    <?php endif; ?>

    <div class="cp-card-config">
        <?php if (!empty($totalItems)): ?>
            <div class="dropdown export">
                <div class="dropdown-toggle" data-toggle="dropdown"><i class="cp-icn-export"></i> &nbsp;&nbsp;<i
                        class="cp-icn-caret-black"></i></div>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Excel</a>
                    <a class="dropdown-item" href="#">CSV</a>
                </div>
            </div>
            <div class="split"></div>
            <div class="total"><?= Yii::t('app', 'Total {total} items', ['total' => $totalItems ?: 0]); ?></div>
        <?php endif; ?>
    </div>
</header>
