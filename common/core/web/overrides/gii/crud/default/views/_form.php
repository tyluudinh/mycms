<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model          = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
echo "<?php\n";
?>

use common\helpers\Html;
use common\utilities\Common;
use common\core\web\mvc\form\BaseActiveForm;

/**
* @var $this common\core\web\mvc\View
* @var $model <?= ltrim($generator->modelClass, '\\') ?>
* @var $form common\core\web\mvc\form\BaseActiveForm
*/
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = BaseActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= "<?= " ?><?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))); ?><?= " ?>" ?></div>
                </div>
                <div class="panel-body">
<?php foreach ($generator->getColumnNames() as $attribute) {
                if ($attribute == 'status') {
                    echo "<?= " . "\$form->field(\$model, 'status')->dropDownList(Common::getStatusArr())" . " ?>\n\n";
                } else {
                    if (in_array($attribute, $safeAttributes)) {
                        echo "<?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
                    }
                }

} ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?>
        : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn
        btn-primary']) ?>
    </div>

    <?= "<?php " ?>BaseActiveForm::end(); ?>

</div>
