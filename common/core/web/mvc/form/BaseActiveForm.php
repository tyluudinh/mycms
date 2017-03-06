<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/14/16
 * Time: 3:34 PM
 */

namespace common\core\web\mvc\form;
use kartik\form\ActiveForm;

/**
 * Class BaseActiveForm
 * @package common\core\web\mvc\form
 * 
 * @method BaseActiveField field($model, $attribute, $options = [])
 */

class BaseActiveForm extends ActiveForm
{
    public $fieldConfig = [
        'class' => BaseActiveField::class,
    ];

    public static function begin($configs = [])
    {
        return parent::begin($configs);
    }
    
    public $currencyTemplate = '{label}
   <div class="col-sm-12 input-group bootstrap-touchspin">
       {input}
       <span class="input-group-addon bootstrap-touchspin-postfix">$</span>
   </div>
       {error}{hint}';
    
    /**
     * @param array $configs
     * @return $this
     */
    public static function beginMultipart($configs = [])
    {
        if (!isset($configs['options'])) {
            $configs['options'] = [
                'enctype'        => 'multipart/form-data',
            ];
        } else {
            $configs['options']['enctype'] = 'multipart/form-data';
        }
        return static::begin($configs);

    }


}