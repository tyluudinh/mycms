<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/10/16
 * Time: 11:55 AM
 */

namespace common\core\web\mvc\grid;


use common\utilities\Common;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\grid\GridView;
use common\helpers\Html;

class BaseGridView extends GridView
{
    public $dataColumnClass = BaseDataColumn::class;
    
    public static function widget($config = [])
    {
        if (!isset($config['tableOptions'])) {
            $config['tableOptions'] = ['class' => 'table table-hover'];
        }

        $dataProvider = $config['dataProvider'];
        /**@var $dataProvider ActiveDataProvider */
        $dataProvider->pagination = ['pageSize' => ITEM_PER_PAGE];

        if (!isset($config['sorter'])) {
            $attrsToSort = [];
            foreach ($config['columns'] as $i => $c) {
                if (is_string($c)) {
                    $attrsToSort[] = $c;
                } else if (is_string($i)) {
                    $attrsToSort[] = $c;
                }
            }
            $config['sorter'] = [
                'attributes' => $attrsToSort,
            ];
        }

        return parent::widget($config);
    }

    public function renderFilters()
    {
        if ($this->filterModel !== null) {
            $cells = [];
            foreach ($this->columns as $column) {
                /* @var $column DataColumn */
                if (property_exists($column, 'format')) {
                    if (strpos($column->format, 'image') !== false) {
                        $cells[] = Html::tag('td');
                    } elseif ($column->format == 'boolean') {
                        $filter = Html::activeDropDownList($this->filterModel, $column->attribute, Common::getBooleans(), [
                            'class' => 'form-control',
                            'prompt' => \Yii::t('app', 'All'),
                        ]);
                        $cells[] = Html::tag('td', $filter);
                    } else {
                        $cells[] = $column->renderFilterCell();
                    }
                } else {
                    $cells[] = $column->renderFilterCell();
                }
            }

            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }

}