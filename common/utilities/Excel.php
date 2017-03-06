<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 3/9/16
 * Time: 2:46 PM
 */

namespace common\utilities;

use common\components\Upload;
use common\Factory;
use yii\base\Exception;
use yii\base\InvalidCallException;

require APPROOT . '/common/libs/phpoffice/phpexcel/Classes/PHPExcel.php';

class Excel
{
    private static $_instance;

    public static function getInstance()
    {
        ini_set('memory_limit', '512M');
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    public static function readOneSheet($filePath)
    {
        try {
            $objReader = \PHPExcel_IOFactory::createReaderForFile($filePath);
            /**
             * @var $objReader \PHPExcel_Reader_Abstract
             */
            $objReader->setReadDataOnly(true);
            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel  = $objReader->load($filePath);
            $objWorksheet = $objPHPExcel->setActiveSheetIndex();

            return $objWorksheet->toArray();
        } catch (Exception $e) {
            die($e->getMessage());
        }

    }

    public static function readAllSheet($filePath)
    {
        try {
            ini_set('memory_limit', '512M');
            $objReader = \PHPExcel_IOFactory::createReaderForFile($filePath);
            /**
             * @var $objReader \PHPExcel_Reader_Abstract
             */
            $objReader->setReadDataOnly(true);
            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($filePath);
            $sheets      = $objPHPExcel->getAllSheets();
            $sheetArr    = [];
            foreach ($sheets as $sheetIndex => $s) {
                $sheetArr[$sheetIndex] = $s->toArray();
            }

            return $sheetArr;

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //For exporting

    /**
     * @var \PHPExcel
     */
    private $phpExcel;
    /**
     * @var \PHPExcel_Writer_IWriter
     */
    private $writer;

    public function createWriter($params = array('Title' => 'Office 2006 - 2014 XLSX Document'), $writerType = 'Excel2007')
    {

        $this->phpExcel = new \PHPExcel();

        foreach ($params as $key => $value) {
            $func = 'set' . $key;
            $this->phpExcel->getProperties()->$func($value);
        }

        $this->writer = \PHPExcel_IOFactory::createWriter($this->phpExcel, $writerType);

        return $this;
    }

    /**
     * Input is like:
     * [
     *      [0 => 'header1', 1 => 'header2'],
     *      [0 => 'value1 as header1', 1 => 'value1 as header2']
     *      [0 => 'value2 as header1', 1 => 'value2 as header2']
     *      [0 => 'value3 as header1', 1 => 'value3 as header2']
     * ]
     *
     * @param $data
     * @param int $fromRow
     * @param string $fromCol
     * @return $this
     */
    public function writeFromArray($data, $fromRow = 1, $fromCol = 'A')
    {
        if (empty($this->phpExcel)) {
            $this->createWriter();
        }

        $col = $fromCol;
        foreach ($data as $row) {
            foreach ($row as $val) {
                $this->phpExcel->getActiveSheet()->setCellValue($col . $fromRow, $val);
                if (is_numeric($val)) {
                    $this->phpExcel->getActiveSheet()->getStyle($col . $fromRow)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
                }
                $col++;
            }
            $col = $fromCol;
            $fromRow++;
        }

        return $this;
    }

    public function setAutoWidth($header)
    {
        $cdFrom = 'A';
        for ($i = 0; $i < count($header); $i++) {
            $this->phpExcel->getActiveSheet()->getColumnDimension($cdFrom)->setAutoSize(true);
            $cdFrom++;
        }
        return $this;
    }

    public function send2Browser($params = ['filename' => ''])
    {
        if (empty($this->writer)) {
            throw new InvalidCallException("data is not yet set.");
        }

        if (empty($params['filename'])) {
            $params['filename'] = 'export_' . date('Y_m_d_H_i_s') . '.xls';
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/octet-stream; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$params['filename']}\"");
        header("Content-Transfer-Encoding: binary ");
        $this->writer->save('php://output');
        exit;
    }

    public function saveToFile($params = ['filename' => ''])
    {
        if (empty($this->writer)) {
            throw new InvalidCallException("data is not yet set.");
        }
        if (empty($params['filename'])) {
            $params['filename'] = 'export_' . date('Y_m_d_H_i_s') . '.xls';
        }

        $targetDir = \Yii::getAlias('@backend/runtime/export/excel');
        if (!is_dir($targetDir)) {
            Upload::mkdirs($targetDir);
        }

        $fileToSave = $targetDir . '/' . $params['filename'];
        $this->writer->save($fileToSave);

        return $fileToSave;
    }

}