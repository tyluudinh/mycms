<?php

namespace common\modules\systemSetting\controllers;

use backend\controllers\BackendBaseController;
use common\modules\systemSetting\business\BusinessSystemSetting;
use common\utilities\ArraySimple;
use Yii;
use common\modules\systemSetting\models\SystemSetting;
use yii\web\NotFoundHttpException;

class DefaultController extends BackendBaseController
{
    /**
     * @var BusinessSystemSetting
     */
    private $business;

    public function init()
    {
        $this->business = BusinessSystemSetting::getInstance();
        $this->category = 'systemSetting';
        parent::init();
    }

    public function actionIndex()
    {
        $models = SystemSetting::find()->all();
        $models = ArraySimple::makeKeyPath($models, null, 'type');
        $cachedConfigs = $this->business->getAll();

        return $this->render('index', [
            'types' => $this->business->getTypes(),
            'models' => $models,
            'cachedConfigs' => $cachedConfigs,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $postObject = $this->getPostObject('SystemSetting');
        if (!empty($postObject->toArray())) {
            if (BusinessSystemSetting::save($model, $postObject)) {
                flassSuccess();
            }
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
            'types' => $this->business->getTypes(),
        ]);
    }
    
    /**
     * @param integer $id
     * @return SystemSetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        return SystemSetting::findOneOrFail($id);
    }
}
