<?php
/**
 * Created by dinhty.luu@gmail.com
 * Date: 20/10/2016
 * Time: 17:32
 */

namespace backend\widgets;


use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;

/**
 * Class HeaderForIndexPage
 * @package common\core\web\mvc\widget
 */
class HeaderForIndexPage extends Widget
{
    public $className;
    public $title;


    /**
     * @var DataProviderInterface
     */
    public $subTitle;
    public $totalItems;

    public function run()
    {
        return $this->render('header_for_index_page',[
            'className' => $this->className,
            'title' => $this->title,
            'subTitle' => $this->subTitle,
            'totalItems' => $this->totalItems,
        ]);
    }
}