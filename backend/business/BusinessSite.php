<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 8/9/16
 * Time: 9:50 AM
 */

namespace backend\business;


use common\business\BaseBusinessPublisher;
use common\core\oop\ObjectScalar;
use common\modules\adminUser\models\User;
use common\modules\file\business\BusinessFile;

class BusinessSite extends BaseBusinessPublisher
{
    private static $_instance;
    
    public static function getInstance() 
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    
    }

    public function updateProfile(User $user, ObjectScalar $post)
    {
        $user->setAttributes($post->toArray());
        $status = $user->save();
        if ($status) {
            BusinessFile::getInstance()->doUploadAndSave($user);
            return $status;
        }
        
        return false;

    }

}