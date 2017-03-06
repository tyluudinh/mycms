<?php
/**
 * Created by TyLuuDinh.
 * Date: 24/08/2016
 * Time: 14:17
 */
use common\utilities\Common;
use yii\helpers\Url;

?>
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
