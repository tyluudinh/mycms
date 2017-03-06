<?php

/**
 * Created by TyLuuDinh.
 * Date: 28/09/2016
 * Time: 14:28
 */
namespace common\modules\tools\controllers;

use backend\controllers\BackendBaseController;
use common\modules\file\business\BusinessFile;
use common\modules\tools\business\BusinessTranslate;
use common\Factory;
use PHPExcel_Cell;
use PHPExcel;
use PHPExcel_IOFactory;
use common\utilities\Common;
use yii\base\ErrorException;
use yii\filters\VerbFilter;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class TranslateController extends BackendBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    private $tranlatePath;

    public function init()
    {
        $this->tranlatePath = BusinessTranslate::getTranslatePath();
        parent::init();
    }

    public function actionChangeLanguage()
    {
        $param = Factory::$app->request->queryParams;
        if (!empty($param['language'])) {
            $cookie = new Yii\web\Cookie([
                'name' => 'language',
                'value' => $param['language'],
            ]);
            Factory::$app->getResponse()->getCookies()->add($cookie);
            $referer = Factory::$app->request->headers['referer'] ?: Url::home(true);
            return $this->redirect($referer);
        }
        return $this->goHome();
    }

    public function actionIndex($language)
    {
        if ($language == BusinessTranslate::LANGUAGE_2_EN) {
            flassError(Yii::t('app', 'You are not allowed to access this page'));
            return $this->goHome();
        }
        $languagesArray = BusinessTranslate::getAppLanguages();
        if (!in_array($language, array_keys($languagesArray))) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $postData = $this->getPostObject('language')->getData();
        $file = $this->tranlatePath . $language . '/app.php';
        if (!file_exists($file)) {
            if (!copy($this->tranlatePath . 'en/app.php', $file)) {
                throw new ErrorException(Yii::t('app', 'Can not proceed to translate to this langguage'));
            };
        }
        $arrayTran = require($file);
        if (!empty($postData)) {
            Common::writeDataIntoFile($postData, $file);
            flassSuccess();
            $arrayTran = $postData;
        }
        $langEnTran = array_merge(
            require($this->tranlatePath . 'en/app.php'),
            $arrayTran
        );
        return $this->render('index', [
            'translation' => $langEnTran,
            'languages' => $languagesArray,
            'keyLanguage' => $language,
        ]);
    }

    public function actionExport($language)
    {
        $languagesArray = BusinessTranslate::getAppLanguages();
        $langEnTran = array_merge(
            require($this->tranlatePath . 'en/app.php'),
            require($this->tranlatePath . $language . '/app.php')
        );

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'English')
            ->setCellValue('B1', $languagesArray[$language]);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);

        $filename = Factory::$app->name . "-translate-en-to-" . $language . date('-Y-m-d-G-i-s') . ".xlsx";
        header('Content-Type: application/octet-stream; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $col = 2;
        foreach ($langEnTran as $key => $row) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $key);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $row);
            $col++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        Yii::$app->end();
    }

    public function actionImport($language)
    {
        if (empty($_FILES['Translate']['name']['import'])) {
            flassError(Yii::t('app', 'You haven\'t choose any file to import!'));
            return $this->redirect(['index', 'language' => $language]);
        } else {
            $standardWords = require($this->tranlatePath . 'en/app.php');
            $languagesArray = $languagesArray = BusinessTranslate::getAppLanguages();;
            $langEnTran = array();
            $filePathLoad = BusinessFile::getInstance()->importExcelFile('Translate', 'import');
            if (!empty($filePathLoad)) {

                $objPHPExcel = PHPExcel_IOFactory::load($filePathLoad);

                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();

                    for ($row = 2; $row <= $highestRow; ++$row) {
                        $key = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $value = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        if (isset($standardWords[$key])) {
                            $langEnTran[$key] = $value;
                        }
                    }
                }
                // remove file uploaded
                unlink($filePathLoad);
                $file = $this->tranlatePath . $language . '/app.php';
                Common::writeDataIntoFile($langEnTran, $file);
                flassSuccess();
            } else {
                flassError(Yii::t('app', 'Import file error'));
            }
            return $this->render('index', [
                'translation' => $langEnTran,
                'languages' => $languagesArray,
                'keyLanguage' => $language,
            ]);

        }
    }
}