<?php

namespace common\utilities;

use common\core\oop\ObjectScalar;
use common\Factory;
use yii;

class Common
{
    public static function getGenders($k = false)
    {
        $d = [
            'male' => Yii::t('app', 'Male'),
            'female' => Yii::t('app', 'Female'),
        ];
        if ($k === false || !isset($d[$k])) {
            return $d;
        }

        return $d[$k];
    }

    public static function getBooleans($k = false)
    {
        $d = [
            0 => Yii::t('app', 'No'),
            1 => Yii::t('app', 'Yes'),
        ];
        if ($k === false || !isset($d[$k])) {
            return $d;
        }

        return $d[$k];
    }

    /**
     * @param string $jsonText
     * @param $keyLabels ObjectScalar | array
     * @return string
     */
    public static function jsonToDebug($jsonText = '', $keyLabels = [])
    {
        $arr = json_decode($jsonText, true);
        $html = "";
        if ($arr && is_array($arr)) {
            $html .= self::arrayToHtmlTableRecursive($arr, $keyLabels);
        } else {
            return mb_strimwidth($jsonText, 0, 50, '...');
        }

        return $html;
    }

    public static function arrayToHtmlTableRecursive($arr, $keyLabels)
    {
        $str = "<table class='table table-bordered'><tbody>";
        foreach ($arr as $key => $val) {
            if (is_string($key)) {
                $key = (isset($keyLabels[$key]) && !empty($keyLabels[$key])) ? $keyLabels[$key] : $key;    
            }
            
            if (is_string($val)) {
                $val = (isset($keyLabels[$val]) && !empty($keyLabels[$val])) ? $keyLabels[$val] : $val;
            }
            $str .= "<tr>";
            $str .= "<td>{$key}</td>";
            $str .= "<td>";
            if (is_array($val)) {
                if (!empty($val)) {
                    $str .= self::arrayToHtmlTableRecursive($val, $keyLabels);
                }
            } else {
                $str .= "<strong>{$val}</strong>";
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";

        return $str;
    }

    /**
     * strip vietnamese charaters
     * @param $strInput
     * @param string $replaceSpace
     * @param string $code
     * @param bool|false $stripSpace
     * @return string $stripped_str: string after strip
     */
    public static function stripViet($strInput, $replaceSpace = '', $code = "utf-8", $stripSpace = false)
    {
        $stripped_str = $strInput;
        $vietU = array();
        $vietL = array();

        if (strtolower($code) == "utf-8") {
            $i = 0;
            $vietU[$i++] = array(
                'A',
                array(
                    "/Á/",
                    "/À/",
                    "/Ả/",
                    "/Ã/",
                    "/Ạ/",
                    "/Ă/",
                    "/Ắ/",
                    "/Ằ/",
                    "/Ẳ/",
                    "/Ẵ/",
                    "/Ặ/",
                    "/Â/",
                    "/Ấ/",
                    "/Ầ/",
                    "/Ẩ/",
                    "/Ẫ/",
                    "/Ậ/"
                )
            );
            $vietU[$i++] = array(
                'O',
                array(
                    "/Ó/",
                    "/Ò/",
                    "/Ỏ/",
                    "/Õ/",
                    "/Ọ/",
                    "/Ô/",
                    "/Ố/",
                    "/Ồ/",
                    "/Ổ/",
                    "/Ộ/",
                    "/Ơ/",
                    "/Ớ/",
                    "/Ờ/",
                    "/Ớ/",
                    "/Ở/",
                    "/Ỡ/",
                    "/Ợ/"
                )
            );
            $vietU[$i++] = array(
                'E',
                array("/É/", "/È/", "/Ẻ/", "/Ẽ/", "/Ẹ/", "/Ê/", "/Ế/", "/Ề/", "/Ể/", "/Ễ/", "/Ệ/")
            );
            $vietU[$i++] = array(
                'U',
                array("/Ú/", "/Ù/", "/Ủ/", "/Ũ/", "/Ụ/", "/Ư/", "/Ứ/", "/Ừ/", "/Ử/", "/Ữ/", "/Ự/")
            );
            $vietU[$i++] = array('I', array("/Í/", "/Ì/", "/Ỉ/", "/Ĩ/", "/Ị/"));
            $vietU[$i++] = array('Y', array("/Ý/", "/Ỳ/", "/Ỷ/", "/Ỹ/", "/Ỵ/"));
            $vietU[$i++] = array('D', array("/Đ/"));
            unset($i);
            $i = 0;
            $vietL[$i++] = array(
                'a',
                array(
                    "/á/",
                    "/à/",
                    "/ả/",
                    "/ã/",
                    "/ạ/",
                    "/ă/",
                    "/ắ/",
                    "/ằ/",
                    "/ẳ/",
                    "/ẵ/",
                    "/ặ/",
                    "/â/",
                    "/ấ/",
                    "/ầ/",
                    "/ẩ/",
                    "/ẫ/",
                    "/ậ/"
                )
            );
            $vietL[$i++] = array(
                'o',
                array(
                    "/ó/",
                    "/ò/",
                    "/ỏ/",
                    "/õ/",
                    "/ọ/",
                    "/ô/",
                    "/ố/",
                    "/ồ/",
                    "/ổ/",
                    "/ỗ/",
                    "/ộ/",
                    "/ơ/",
                    "/ớ/",
                    "/ờ/",
                    "/ở/",
                    "/ỡ/",
                    "/ợ/"
                )
            );
            $vietL[$i++] = array(
                'e',
                array("/é/", "/è/", "/ẻ/", "/ẽ/", "/ẹ/", "/ê/", "/ế/", "/ề/", "/ể/", "/ễ/", "/ệ/")
            );
            $vietL[$i++] = array(
                'u',
                array("/ú/", "/ù/", "/ủ/", "/ũ/", "/ụ/", "/ư/", "/ứ/", "/ừ/", "/ử/", "/ữ/", "/ự/")
            );
            $vietL[$i++] = array('i', array("/í/", "/ì/", "/ỉ/", "/ĩ/", "/ị/"));
            $vietL[$i++] = array('y', array("/ý/", "/ỳ/", "/ỷ/", "/ỹ/", "/ỵ/"));
            $vietL[$i++] = array('d', array("/đ/"));
            unset($i);
        }
        for ($i = 0; $i < count($vietL); $i++) {
            $stripped_str = preg_replace($vietL[$i][1], $vietL[$i][0], $stripped_str);
            $stripped_str = preg_replace($vietU[$i][1], $vietU[$i][0], $stripped_str);
        }
        if ($stripSpace) {
            $stripped_str = str_replace(' ', '', $stripped_str);
        }
        if ($replaceSpace) {
            return $stripped_str = preg_replace(array('[^[^a-zA-Z0-9]+|[^a-zA-Z0-9]+$]', '[[^a-zA-Z0-9\-]+]'), array(
                '',
                $replaceSpace
            ), $stripped_str);
        }

        return $stripped_str;
    }

    public static function buildQueryString($params = array(), $reset = false)
    {
        $ret = '';
        if (is_array($params)) {
            if ($reset) {
                $ret = http_build_query($params);
            } else {
                $query_data = array();
                parse_str($_SERVER['QUERY_STRING'], $query_data);
                foreach ($params as $pKey => $pVal) {
                    unset($query_data[$pKey]);
                }
                foreach ($params as $pKey => $pVal) {
                    if ($pVal !== NULL) {
                        $query_data[$pKey] = $pVal;
                    }
                }
                $ret = http_build_query($query_data);
            }
        }
        if ($ret) {
            $ret = '?' . $ret;
        }

        return $ret;
    }

    /**
     * @param int $status
     * @return string $strStatus: Status after parse to string
     */
    public static function getStrStatus($status)
    {
        $statusList = self::getStatusArr();
        if (isset($statusList[$status])) {
            return $statusList[$status];
        }

        return '';
    }

    public static function getStatusArr()
    {
        $statusArr = [
            STATUS_ACTIVE => Yii::t("app", "Active"),
            STATUS_HIDE => Yii::t("app", "Deactive"),
        ];

        return $statusArr;
    }

    public static function getStrIsRead($is_read)
    {
        $strIsRead = Yii::t("app", "Not Yet");
        if ($is_read) {
            $strIsRead = Yii::t("app", "Read");
        }

        return $strIsRead;
    }
    
    /**
     * using form redirect
     * @param $targetURL
     * @param array $dataArray
     */
    public static function redirectByForm($targetURL, $dataArray = array())
    {
        echo '<html><body onload="document.forms[0].submit()">';
        echo '<form id="_form" method="POST" action="' . htmlentities($targetURL) . '">';
        self::writeHiddenFormFields($dataArray);
        echo '</form>';
        echo '</body></html>';
        exit;
    }

    public static function writeHiddenFormFields($dataArray)
    {
        if (is_array($dataArray) && !empty($dataArray)) {
            foreach ($dataArray as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $tmpId = $name . '_' . $k;
                        $tmpName = $name . '[' . $k . ']';
                        echo '<input type="hidden" name="' . htmlentities($tmpName) . '" id="' . htmlentities($tmpId) . '" value="' . htmlentities($v) . '" />' . "";
                    }
                } else {
                    echo '<input type="hidden" name="' . htmlentities($name) . '" id="' . htmlentities($name) . '" value="' . htmlentities($value) . '" />' . "";
                }

            }
        }
    }

    public static function getClassName($fullClassName)
    {
        $ret = '';
        if ($fullClassName) {
            $tmpArr = explode('\\', $fullClassName);
            $ret = end($tmpArr);
        }

        return $ret;
    }

    public static function getPostData($url, $params = array())
    {
        try {
            $paramQuery = http_build_query($params);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramQuery);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            if (curl_errno($ch)) {
                echo "\nURL: " . $url . " Error is : " . curl_error($ch) . "\n";
            }
            curl_close($ch);

            return $content;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getPostDataXml($url, $xmlParam)
    {
        try {
            $header = array(
                "Content-type: text/xml",
                "Content-length: " . strlen($xmlParam),
                "Connection: close"
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlParam);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, NULL);
            $content = curl_exec($ch);
            if (curl_errno($ch)) {
                return false;
            }
            curl_close($ch);
            if ($content) {
                $content = html_entity_decode($content);
                $content = simplexml_load_string($content);
            }

            return $content;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function vnNumberFormat($number, $decimal = 2)
    {
        $number = floatval($number);

        return number_format($number, $decimal, '.', ',');
    }

    public static function vnNumberFormatSuffix($number, $decimal = 2, $suffix = '')
    {
        $number = floatval($number);

        return number_format($number, $decimal, ',', '.') . ' ' . $suffix;
    }

    public static function unsetAutoFields(&$data, $moreFields = [])
    {
        unset($data['status']);
        unset($data['created_by']);
        unset($data['updated_by']);
        unset($data['updated_at']);
        if ($moreFields) {
            foreach ($moreFields as $f) {
                unset($data[$f]);
            }
        }
    }

    public static function subString($string)
    {
        if (strlen($string) > 50) {
            return mb_substr($string, 0, 49) . ' ...';
        }

        return $string;
    }


    public static function getTempFolder()
    {
        return ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
    }

    public static function generateRandomString($length = 7)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function writeDataIntoFile($postData, $file){
        if(file_exists($file)){
            $arr = "\n return [";
            foreach ($postData as $key => $value) {
                $key = addslashes($key);
                $value = addslashes($value);
                $arr .= "\n'$key' => '$value',\n";
            }
            file_put_contents($file, '<?php '.$arr .'];');
        }
    }
}