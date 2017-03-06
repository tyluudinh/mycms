<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/9/16
 * Time: 4:16 PM
 */

namespace common\modules\adminUser\components;

use yii\web\User;

/**
 * @property \common\modules\adminUser\models\User $identity
 * Class AdminUserComponent
 * @package common\modules\adminUser\components
 */
class AdminUserComponent extends User
{
    /**
     * @param bool $autoRenew
     * @return \common\modules\adminUser\models\User
     */
    public function getIdentity($autoRenew = true)
    {
        return parent::getIdentity();    
    }


}