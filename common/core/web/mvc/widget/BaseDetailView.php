<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/16/16
 * Time: 2:50 PM
 */

namespace common\core\web\mvc\widget;

use common\core\web\mvc\BaseModel;
use yii\base\InvalidParamException;
use yii\widgets\DetailView;

class BaseDetailView extends DetailView
{
    protected function normalizeAttributes() {
        $labels = $this->model->attributeLabels();
        foreach ($this->attributes as $field => &$attribute) {
            if (is_array($attribute) && !isset($attribute['label'])) {
                if ($this->model instanceof BaseModel) {
                    if (!isset($labels[$field])) {
                        throw new InvalidParamException("Label '$field' must be defined in " . get_class($this->model) . "::attributeLabels()");
                    }
                }
                $attribute['label'] = $labels[$field];
                $attribute['format'] = 'raw';
            }
            if (is_string($attribute)) {
                if (in_array($attribute, ['updated_by', 'created_by'])) {
                    $attribute = $attribute. ':staff';
                }

                if (in_array($attribute, ['updated_at', 'created_at'])) {
                    $attribute = $attribute. ':datetime';
                }
                
                if ($attribute === 'status') {
                    $attribute = $attribute. ':status';
                }
            }
            
        }

        return parent::normalizeAttributes();
    }


}