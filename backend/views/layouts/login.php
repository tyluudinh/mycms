<?php

/* @var $this \common\core\web\mvc\View */
/* @var $content string */
/* @var $model \backend\models\LoginForm */

use common\helpers\Html;
use common\Factory;

\backend\assets\LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link rel="shortcut icon" href="<?= \common\Factory::$app->getHomeUrl() ?>favicon.ico?v=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>var PARAMS = <?php echo json_encode(Factory::$app->view->jsConstants) ?></script>
</head>
<body>
<?php $this->beginBody() ?>
<div class="app app-default">
    <div class="app-container app-login">
        <div class="flex-center">
            <div class="app-header"></div>
            <div class="app-body">
                <div class="loader-container text-center">
                    <div class="icon">
                        <div class="sk-folding-cube">
                            <div class="sk-cube1 sk-cube"></div>
                            <div class="sk-cube2 sk-cube"></div>
                            <div class="sk-cube4 sk-cube"></div>
                            <div class="sk-cube3 sk-cube"></div>
                        </div>
                    </div>
                    <div class="title">Logging in...</div>
                </div>
                <div class="app-block">
                    <div class="app-form">
                        <div class="form-header">
                            <div class="app-brand">
                                <?= Html::img(\common\Factory::$app->homeUrl . 'images/8-Light.png', ['class' => 'lotto-logo']); ?>
                            </div>
                        </div>
                        <?= $content; ?>
                        <!---->
                        <!--                        <div class="form-line">-->
                        <!--                            <div class="title">OR</div>-->
                        <!--                        </div>-->
                        <!--                        <div class="form-footer">-->
                        <!--                            <button type="button" class="btn btn-default btn-sm btn-social __facebook">-->
                        <!--                                <div class="info">-->
                        <!--                                    <i class="icon fa fa-facebook-official" aria-hidden="true"></i>-->
                        <!--                                    <span class="title">Login with Facebook</span>-->
                        <!--                                </div>-->
                        <!--                            </button>-->
                        <!--                        </div>-->
                    </div>
                </div>
            </div>
            <div class="app-footer">
            </div>
        </div>
    </div>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
