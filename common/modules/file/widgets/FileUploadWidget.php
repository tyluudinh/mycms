<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/6/16
 * Time: 2:11 PM
 */

namespace common\modules\file\widgets;

use common\core\web\mvc\BaseModel;
use common\models\ProductColorPreviewImage;
use yii\base\Widget;

class FileUploadWidget extends Widget
{
    public $form;

    /**
     * @var BaseModel
     */
    public $sourceModel;
    
    public $attr;

    public $options = [];
    
    public function run()
    {
        if (!is_object($this->sourceModel)) {
            $this->sourceModel = (new $this->sourceModel);
        }

        return $this->render('file_upload_widget', [
            'formName' => $this->sourceModel->formName(),
            'sourceModel' => $this->sourceModel,
            'attr' => $this->attr,
            'options' => $this->options,
            'uniqueId' => $this->sourceModel->isNewRecord ? 'tobeReplaced' : $this->sourceModel->primaryKey,
        ]);
    }

}