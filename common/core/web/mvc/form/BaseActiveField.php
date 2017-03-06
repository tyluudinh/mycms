<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/14/16
 * Time: 3:39 PM
 */

namespace common\core\web\mvc\form;


use common\core\web\BaseFormatter;
use common\helpers\DateFormat;
use common\helpers\Html;
use kartik\widgets\ActiveField;

class BaseActiveField extends ActiveField
{
    public function fileInputWithPreview($options = [])
    {
        return $this->fileInput($options);
    }

    public function datetimeInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        if (strpos('datetimepicker', $options['class']) === false) {
            $options['class'] = $options['class'] . ' datetimepicker';
            if ($time = strtotime($this->model->{$this->attribute})) {
                $options['value'] = date(BaseFormatter::PHP_TIME_FORMAT, $time);
            }
        }

        $input = '<div class="input-append" style="position: relative">';
        $input .= $this->textInput($options);
        $input .= '</div>';
        return $input;
    }

    public function timeInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        if (strpos('timepicker', $options['class']) === false) {
            $options['class'] = $options['class'] . ' timepicker';
            if ($time = strtotime($this->model->{$this->attribute})) {
                $options['value'] = date(BaseFormatter::PHP_TIME_FORMAT, $time);
            }
        }
        return $this->textInput($options);
    }

    public function dateInput($options = [])
    {
        $formName = $this->model->formName() . '['.$this->attribute.']';
        $dob = $this->model->{$this->attribute};
        $year = $day = $month = null;
        if (!empty($dob)) {
            $dob = strtotime($dob);
            $year = (int) date('Y', $dob);
            $month = (int) date('m', $dob);
            $day = (int) date('d', $dob);
        }
        $selectOptionsDate = Html::dropDownList($formName . '[date]', $day, DateFormat::dates(), ['style' => 'width: 30%; background-position-x: 91%;', 'class' => 'select2 form-control']);
        $selectOptionsMonth = Html::dropDownList($formName . '[month]', $month, DateFormat::months(), ['style' => 'width: 30%; background-position-x: 91%;', 'class' => 'select2 form-control']);
        $selectOptionsYear = Html::dropDownList($formName . '[year]', $year, DateFormat::years(false, (int)date('Y') + 24), ['style' => 'width: 30%; background-position-x: 91%;', 'class' => 'select2 form-control']);

        $html = '<div class="cp-label" style="text-transform: uppercase; padding-top: 20px; padding-bottom: 6px;">' . $this->model->getAttributeLabel($this->attribute) . '</div>' .
            '<div class="cp-date" style="display: -webkit-flex;
    display: flex;
    -webkit-justify-content: space-between;
    justify-content: space-between;">' .
            $selectOptionsDate . $selectOptionsMonth . $selectOptionsYear .
            '</div>';
        return $html;
    }

    public function dropDownListWithPrompt($items = [], $options = []){
        $defaultOptions = [
            'prompt' => \Yii::t('app', 'Select one'),
            'class' => 'select2',
        ];
        $options += $defaultOptions;
        return parent::dropDownList($items, $options);
        
    }
    public function checkbox($options = [], $enclosedByLabel = true)
    {
        if (!isset($options['label'])){
            $id = Html::getInputId($this->model, $this->attribute);
            if (isset($options['id'])){
                $id = $options['id'];
            }
            $options['label'] = Html::label(Html::encode($this->model->getAttributeLabel($this->attribute)), $id);
        }
        return parent::checkbox($options, $enclosedByLabel);
    }

}