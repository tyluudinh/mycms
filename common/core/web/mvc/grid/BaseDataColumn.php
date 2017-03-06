<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/7/16
 * Time: 11:06 AM
 */

namespace common\core\web\mvc\grid;


use common\core\web\mvc\BaseModel;
use common\Factory;
use common\helpers\Html;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class BaseDataColumn extends DataColumn
{
    public function getDataCellValue($model, $key, $index)
    {
        /**@var $model BaseModel */
        $queryParams = Factory::$app->request->getQueryParams();
        $formName = $model->formName();
        $text = null;
        if ($this->value !== null) {
            if (is_string($this->value)) {
                $text = ArrayHelper::getValue($model, $this->value);
            } else {
                $text = call_user_func($this->value, $model, $key, $index, $this);
            }
        } elseif ($this->attribute !== null) {
            $text = ArrayHelper::getValue($model, $this->attribute);
        }

        if ($queryParams && strpos($this->format, 'image') === false && in_array($this->attribute, $model::getStringFields())) {
            if (isset($queryParams['search-whatever']) && $queryParams['search-whatever']) {
                $this->format = 'raw';
                $text = Html::nestHtmlTagToText($text, $queryParams['search-whatever'], 'span', ['style' => 'background-color: #870854']);
            }

            if (isset($queryParams[$formName]) && isset($queryParams[$formName][$this->attribute])) {
                $this->format = 'raw';
                $text = Html::nestHtmlTagToText($text, $queryParams[$formName][$this->attribute]);
            }
        }

        return $text;
    }

}