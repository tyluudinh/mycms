<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 8/22/16
 * Time: 10:45 AM
 */

namespace common\core\web\overrides\gii\generators;

use Yii;
use yii\gii\CodeFile;
use yii\gii\generators\crud\Generator;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;

class BaseCrudGenerator extends Generator
{
    public $businessClassShortName;
    public $businessNamespace;

    public function formView()
    {

        $class = get_parent_class();
        $class = new \ReflectionClass($class);

        return dirname($class->getFileName()) . '/form.php';
    }

    public function generate()
    {
        $controllerPath = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')));
        $controllerFile = $controllerPath . '.php';

        $businessDir = dirname(dirname($controllerPath)) . '/business';
        FileHelper::createDirectory($businessDir);

        $temp = explode('\\', $this->controllerClass);
        unset($temp[count($temp) - 1]);
        unset($temp[count($temp) - 1]);

        $this->businessNamespace = implode('\\', $temp) . '\\' . 'business';
        $this->businessClassShortName = "Business" . StringHelper::basename($this->modelClass);
        $businessFile = $businessDir . "/" . $this->businessClassShortName . '.php';
        
        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
            new CodeFile($businessFile, $this->render('business.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = APPROOT .'/'. $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;
    }

}