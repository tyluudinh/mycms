<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/10/16
 * Time: 8:43 AM
 */

namespace common\controllers; 

use common\Factory;
use yii\web\Controller;
use yii\web\Response;

class CommonBaseController extends Controller
{
    public $category;

    public function init()
    {
        if ($this->category === null) {
            $this->category = $this->id;
        }
        parent::init();
    }

    public function addJsParams($params)
    {
        foreach ($params as $key => $value) {
            Factory::$app->view->jsConstants[$key] = $value;
        }
        return $this;
    }

    public function getPostObject($path = '')
    {
        if (empty($path)) {
            $post = \Yii::$app->request->post();
        } else {
            $post = \Yii::$app->request->post($path);
        }
        if (!empty($post) && is_array($post)){
            foreach ($post as $key => $value){
                if(is_array($value)){
                    if(isset($value['date']) && isset($value['month']) && isset($value['year'])){
                        $post[$key] = $value['date'] . '/' . $value['month'] . '/' . $value['year'];
                    }
                }
            }
        }

        return Factory::createObject($post);
    }
    /**
     * Enable auto render
     *
     * @required the view must be under view folder which is mapped with controller
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return string
     */
    public function afterAction($action, $result)
    {
        if (empty($result)) {
            return $this->render($action->id, $this->actionParams);
        }

        return parent::afterAction($action, $result);
    }

    /**
     * Set variables to view
     * @param $vars
     */
    public function setVars($vars)
    {
        $this->actionParams += $vars;
    }

    public function setVar($key, $var)
    {
        $this->actionParams[$key] = $var;
        return $this;
    }

    public function responseJson()
    {
        $r = Factory::$app->response;
        $r->format = Response::FORMAT_JSON;
        $r->data = $this->actionParams;
        $r->send();
        exit;
    }

    public function redirect($url, $statusCode = 302)
    {
        $referer = Factory::$app->request->get('ref');
        if ($referer) {
            return parent::redirect($referer, $statusCode);
        }
        return parent::redirect($url, $statusCode);

    }

}