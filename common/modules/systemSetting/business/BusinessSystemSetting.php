<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/14/16
 * Time: 2:19 PM
 */

namespace common\modules\systemSetting\business;

use common\core\oop\ObjectScalar;
use common\modules\systemSetting\models\SystemSetting;
use common\Factory;
use common\utilities\ArraySimple;
use Yii;
use yii\caching\FileCache;
use common\utilities\Dir;
use yii\web\UploadedFile;

class BusinessSystemSetting
{
    /**
     * @var ObjectScalar
     */
    private $data;

    private static $_instance;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public static function getTypes($k = null)
    {
        $types = [
            SystemSetting::TYPE_TECHNIQUE_SYSTEM => Yii::t('app', 'Technique/System'),
            SystemSetting::TYPE_UPLOAD_FILES => Yii::t('app', 'Upload Files'),
        ];
        if ($k !== null) {
            if (isset($types[$k])) {
                return $types[$k];
            }
            return Yii::t('app', 'No Type');
        }

        return $types;
    }

    /**
     * cache system setting object and alias accross the system
     */
    public function sync()
    {
        if ($this->data === null) {
            $data = Factory::$app->cache->get('systemSetting');
            if (empty($data)) {
                $d = SystemSetting::find()->asArray()->all();
                $d = ArraySimple::makeKeyPath($d, 'key');
                Factory::$app->cache->set('systemSetting', $d);
                $data = $d;
            }
            $this->data = Factory::createObject($data, true);
        }
    }

    public static function save(SystemSetting $setting, ObjectScalar $postObject)
    {
        $attrs = $postObject->toArray();
        if (!empty($_FILES[$setting->formName()]['name']['files'])) {
            $cdnDir = Yii::getAlias('@static');
            $cdnLink = Factory::$app->params['cdn_link'];
            $linkPath = $cdnDir . '/' . $setting->formName() . '/files';
            if (!is_dir($linkPath)){
                Dir::mkdirs($linkPath);
            }
            // Remove old file
            $oldFile = $cdnDir . str_replace($cdnLink, '', $setting->value);
            if (file_exists($oldFile) && !empty($setting->value)){
                unlink($oldFile);
            }
            $fileName = $_FILES[$setting->formName()]['name']['files'];
            $value = UploadedFile::getInstance($setting, 'files')->saveAs($linkPath . '/' . $fileName);
            if ($value) {
                $attrs['value'] = $cdnLink . '/' . $setting->formName() . '/files/' . $fileName;
            }
        }
        $setting->setAttributes($attrs);
        $s = $setting->save(true, null);
        if ($s) {
            $cache = Factory::$app->cache;
            if ($cache instanceof FileCache) {
                if(is_dir(APPROOT . '/backend/runtime/cache')){
                    $cache->cachePath = APPROOT . '/backend/runtime/cache';
                    \Yii::$app->cache->flush();
                }
                if(is_dir(APPROOT . '/frontend/runtime/cache')){
                    $cache->cachePath = APPROOT . '/frontend/runtime/cache';
                    \Yii::$app->cache->flush();
                }
            }
        }
        return $s;
    }

    public function getAll()
    {
        $this->sync();
        return $this->data;
    }
    
    public function getConfig($key, $conditions = [])
    {
        if ($conditions) {
            $conditions['key'] = $key;
            $d = SystemSetting::find()->andWhere($conditions)->select(['value'])->oneOrNew();
            return $d['value'];
        }
        $this->sync();
        return $this->data[$key]['value'];
    }


    public function getMultipleConfig($configs = [])
    {
        $this->sync();
        $d = [];
        foreach ($configs as $configKey) {
            $d[$configKey] = $this->data[$configKey]['value'];
        }
        return Factory::createObject($d);
    }

    public function getConfigsByType($type = '')
    {
        $this->sync();
        $d = [];
        foreach ($this->data->toArray() as $config) {
            if ($config['type'] == $type) {
                $d[$config['key']] = $config['value'];
            }
        }
        return Factory::createObject($d);
    }

}