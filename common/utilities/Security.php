<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 2/22/16
 * Time: 2:05 PM
 */

namespace common\utilities;


use common\core\oop\exceptions\FileNotFoundException;
use common\Factory;

class Security
{
    /**
     * @var \Crypt_RSA
     */
    protected static $_rsa;

    /**
     * @return \Crypt_RSA
     */
    public static function getRsa()
    {
        if (self::$_rsa === null) {
            if (!is_dir(APPROOT.'/vendor/phpseclib')) {
                throw new FileNotFoundException("Required 'phpseclib' is not yet installed.");
            }
            set_include_path(APPROOT.'/vendor/phpseclib');
            include 'Crypt/RSA.php';
            self::$_rsa = new \Crypt_RSA();
        }
        return self::$_rsa;

    }

    public static function verifyRsa($private = '', $public = '')
    {
        $rsa = self::getRsa();

        $rsa->loadKey($private);
        $rsa->setPublicKey();
        $publickey = $rsa->getPublicKey();
        return $publickey === $public;

    }

    public static function encrypt($publicKey, $text = '')
    {
        $rsa = self::getRsa();
        $rsa->loadKey(trim($publicKey));
        return $rsa->encrypt($text);
    }

    public static function decrypt($privateKey, $encryptedText)
    {
        $rsa = self::getRsa();
        $rsa->loadKey($privateKey);
        return $rsa->decrypt($encryptedText);
    }


    public static function passwordEncrypt($plainPassword)
    {
        $publicKey = Factory::$app->params['systemRsa']['publicKey'];
        return base64_encode(self::encrypt($publicKey, $plainPassword));
    }

    public static function passwordDecrypt($hashPassword)
    {
        if ($hashPassword) {
            $privateKey = Factory::$app->params['systemRsa']['privateKey'];
            return self::decrypt($privateKey, base64_decode($hashPassword));
        }
    }

}