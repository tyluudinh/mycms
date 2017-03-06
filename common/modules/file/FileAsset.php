<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/21/16
 * Time: 6:53 PM
 */

namespace common\modules\file;


use yii\web\AssetBundle;
use yii\web\YiiAsset;

class FileAsset extends AssetBundle
{
    public $sourcePath = '@common/modules/file/assets';
    
    public $css = [
    ];
    
    public $js = [
        'module-file.js',
    ];
    
    public $depends = [
        YiiAsset::class,
    ];
    
}