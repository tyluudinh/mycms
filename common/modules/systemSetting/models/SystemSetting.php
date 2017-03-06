<?php

namespace common\modules\systemSetting\models;

use common\core\web\mvc\BaseModel;
use Yii;

/**
 * This is the model class for table "system_settings".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string $type
 * @property string $explain
 * @property integer $status
 */
class SystemSetting extends BaseModel
{
    const TYPE_TECHNIQUE_SYSTEM = 'config';
    const TYPE_UPLOAD_FILES = 'upload_files';
    const KEY_TEXT_DESCRIPTION_OF_PAGE_ABOUT = 'text_description_of_page_about';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            [['key', 'value', 'type', 'explain'], 'string'],
            [['status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $arr =  [
            'key' => Yii::t('app','Key'),
            'value' => Yii::t('app','Value'),
            'status' => Yii::t('app','Status'),
            'type' => Yii::t('app','Type'),
        ];
        return array_merge($arr, parent::attributeLabels());
    }
}
