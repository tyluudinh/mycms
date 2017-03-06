<?php

namespace common\modules\adminUser\controllers;

use backend\controllers\BackendBaseController;
use common\modules\adminUser\models\Role;
use common\modules\adminUser\models\UserSearch;
use Yii;
use common\modules\adminUser\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\modules\adminUser\business\BusinessAdminUser;

class UserController extends BackendBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @var BusinessAdminUser
     */
    protected $business;

    public function init()
    {
        $this->business = BusinessAdminUser::getInstance();
        $this->category = 'adminUser';
        parent::init();
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $roles        = $this->business->findRolesKeyValue();
        $positions    = $this->business->findPositionsKeyValue();

        return $this->render('index', compact('searchModel', 'dataProvider', 'roles', 'positions'));
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->business->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model      = $this->business->newModel();
        $postObject = $this->getPostObject('User');
        if (!$postObject->isEmpty()) {
            $createStatus = $this->business->create($model, $postObject);
            if ($createStatus === true) {
                flassSuccess();

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                flassError();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $this->business->findRolesKeyValue(),
        ]);
    }

    public function actionUpdate($id)
    {
        $model      = $this->business->findModel($id);
        $postObject = $this->getPostObject('User');
        if (!$postObject->isEmpty()) {
            $updateStatus = $this->business->update($model, $postObject);
            if ($updateStatus === true) {
                flassSuccess();

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                flassError();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $this->business->findRolesKeyValue(),
        ]);
    }

    public function actionResetPassword($id)
    {
        $model      = $this->business->findModel($id);
        $postObject = $this->getPostObject('User');

        if (!$postObject->isEmpty()) {
            $changeStatus = $this->business->changePassword($model, $postObject);
            if ($changeStatus) {
                flassSuccess(Yii::t('app', 'Changed password successfully'));

                return $this->redirect(['index']);
            }
            flassError();
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);

    }

    public function actionDelete($id)
    {
        $this->business->findModel($id)->delete();

        return $this->redirect(['index']);
    }

}
