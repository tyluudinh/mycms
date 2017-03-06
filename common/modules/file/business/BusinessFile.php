<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/14/16
 * Time: 4:03 PM
 */

namespace common\modules\file\business;

use common\core\web\mvc\BaseModel;
use common\Factory;
use common\utilities\Dir;
use yii\web\UploadedFile;
use \Yii;

class BusinessFile
{
    public static function getStorageDomain()
    {
        return Factory::$app->params['cdn_link'];
    }

    public static $inputRules = [
        'image' => ['png', 'jpeg', 'jpg', 'gif'],
        'pdf' => ['pdf'],
        'excel' => ['xls', 'xlsx'],
    ];

    private static $_instance;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function doUploadAndSave(BaseModel $model, $addionalThumbnailSizes = [], $fileNameHint = [])
    {
        $modelShortName = $model->formName();
        $modelPrimaryValue = $model->primaryKey;

        $postDeletingData = Factory::$app->request->post('deletingFiles');
        if (isset($postDeletingData[$modelShortName]) && is_array($postDeletingData[$modelShortName])) {
            foreach ($postDeletingData[$modelShortName] as $attr => $isDeleted) {
                if ($isDeleted != '0') {
                    $this->removeHardDiskStorage($model->{$attr}, $addionalThumbnailSizes);
                    $model->{$attr} = null;
                }
            }
            $model->save();
        }

        if (isset($_FILES[$modelShortName])) {
            foreach ($_FILES[$modelShortName]['name'] as $attr => $file) {
                $uploadedFile = UploadedFile::getInstance($model, $attr);
                if ($uploadedFile && $uploadedFile->error == UPLOAD_ERR_OK) {
                    $this->removeHardDiskStorage($model->{$attr}, $addionalThumbnailSizes);
                    $relativeDir = $modelShortName . '/' . $attr;
                    $fileNameHintForAttr = uniqid();
                    if (isset($fileNameHint[$attr])) {
                        $fileNameHintForAttr = str_replace(" ", "-", $fileNameHint[$attr]);
                    }
                    $fileName = "{$modelPrimaryValue}-$fileNameHintForAttr.{$uploadedFile->extension}";

                    if (isset($addionalThumbnailSizes[$attr])) {
                        $filePath = $this->doUploadOneFileForOneAttr($uploadedFile, $relativeDir, $fileName, $addionalThumbnailSizes[$attr]);
                    } else {
                        $filePath = $this->doUploadOneFileForOneAttr($uploadedFile, $relativeDir, $fileName);
                    }
                    if ($filePath) {
                        $model->{$attr} = $filePath;
                    }
                }
            }

        }

        return $model->save();
    }

    public function doUploadOneFileForOneAttr(UploadedFile $uploadedFile, $relativeDir, $fileName, $additionalThumbnailOfAttr = [])
    {
        $diskDir = Yii::getAlias('@static') . '/' . $relativeDir;
        $absPath = $diskDir . '/' . $fileName;
        $relativePath = $relativeDir . '/' . $fileName;

        Dir::mkdirs($diskDir);
        $status = $uploadedFile->saveAs($absPath);
        if ($status) {
            if (in_array($uploadedFile->extension, self::$inputRules['image'])) {
                $imageMagickPath = Factory::$app->params['image_magic'];
                $additionalThumbnailOfAttr[] = THUMBNAIL_SIZE_200x200;
                foreach ($additionalThumbnailOfAttr as $size) {
                    $thumbDir = Yii::getAlias('@static') . '/' . $size;
                    $relativeThumbDir = $thumbDir . '/' . $relativeDir;
                    Dir::mkdirs($relativeThumbDir);
                    $cmd = $imageMagickPath . " " . $absPath . " -resize " . $size . "^ -gravity center " . $thumbDir . '/' . $relativePath;
                    shell_exec($cmd);
                }
            }

            return $relativePath;
        }

        return false;
    }

    public function removeHardDiskStorage($fileAttrRelativePath, $thumbSizes = [])
    {
        if (empty($fileAttrRelativePath)) {
            return true;
        }

        $dir = Yii::getAlias('@static');
        $attrFileAbsPath = $dir . '/' . $fileAttrRelativePath;
        if (file_exists($attrFileAbsPath)) {
            unlink($attrFileAbsPath);
            $thumbSizes[] = THUMBNAIL_SIZE_200x200;
            foreach ($thumbSizes as $thumbSize) {
                $attrThumbnailFileAbsPath = $dir . $thumbSize . '/' . $fileAttrRelativePath;
                if (is_file($attrThumbnailFileAbsPath)) {
                    unlink($attrThumbnailFileAbsPath);
                }
            }
        }

        return true;
    }

    public function importExcelFile($name, $attr)
    {
        $fileName = $_FILES[$name]['name'][$attr];
        $tmpName = $_FILES[$name]['tmp_name'][$attr];
        $size = $_FILES[$name]['size'][$attr];
        $fileNameArrs = explode('.', $fileName);
        if ((count($fileNameArrs) > 1)) {
            $fileExt = end($fileNameArrs);
            if(empty($fileName || empty($tmpName) || empty($fileExt || !($size > 0)))){
                return null;
            }
            if (in_array($fileExt, self::$inputRules['excel'])) {
                $diskDir = \Yii::getAlias('@app/runtime/data/excel/') . $name . '/' . $attr;
                Dir::mkdirs($diskDir);
                $absPath = $diskDir . '/' . $fileName;
                $status = move_uploaded_file($tmpName, $absPath);
                if ($status) {
                    return $absPath;
                }
            }
        }
        return null;
    }

}
