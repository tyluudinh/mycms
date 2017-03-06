<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator \common\core\web\overrides\gii\generators\BaseCrudGenerator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= $generator->businessNamespace . '\\' . 'Business' . $modelClass ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use <?= $generator->modelClass ?>;

class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /** @var Business<?= $modelClass ?> */
    private $business;

    public function init()
    {
        $this->business = Business<?= $modelClass ?>::getInstance();
        parent::init();
    }

    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->business->findModel(<?= $actionParams ?>),
        ]);
    }

    public function actionCreate()
    {
        $model      = $this->business->newModel();
        $postObject = $this->getPostObject('<?= $modelClass ?>');
        if (!$postObject->isEmpty()) {
            $createStatus = $this->business->create($model, $postObject);
            if ($createStatus === true) {
                flassSuccess();

                return $this->redirect(['index']);
            } else {
                flassError();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model      = $this->business->findModel($id);
        $postObject = $this->getPostObject('<?= $modelClass ?>');
        if (!$postObject->isEmpty()) {
            $updateStatus = $this->business->update($model, $postObject);
            if ($updateStatus === true) {
                flassSuccess();

                return $this->redirect(['index']);
            } else {
                flassError();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->business->findModel($id)->delete();

        return $this->redirect(['index']);
    }

}