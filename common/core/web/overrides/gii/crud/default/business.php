<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \common\core\web\overrides\gii\generators\BaseCrudGenerator */

$modelClass = StringHelper::basename($generator->modelClass);

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= $generator->businessNamespace ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
use common\business\BaseBusinessPublisher;
use common\core\oop\ObjectScalar;

class <?= $generator->businessClassShortName ?> extends <?= "BaseBusinessPublisher" . "\n" ?>
{
    private static $_instance;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function create(<?= $modelClass ?> $model, ObjectScalar $requestData)
    {
        $model->setAttributes($requestData->toArray());
        
        return $this->save($model);
    }
    
    public function update(<?= $modelClass ?> $model, ObjectScalar $requestData)
    {
        $model->setAttributes($requestData->toArray());
        
        return $this->save($model);
    }
    
    public function save(<?= $modelClass ?> $model)
    {
        $status = $model->save($model);
        //uncomment if upload file
        if ($status) {
            BusinessFile::getInstance()->doUploadAndSave($model);
        }
        return $status;
    }

    public function newModel()
    {
        return new <?= $modelClass ?>();
    }

    /**
    * @param $id
    * @return <?= $modelClass . "\n" ?>
    * @throws \yii\web\NotFoundHttpException
    */
    public function findModel($id)
    {
        $model = <?= $modelClass ?>::findOneOrFail($id);

        return $model;
    }


}