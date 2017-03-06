<?php
/**
 * CreatedBy: thang.tran@tiki.vn
 * Date: 1/18/16
 * Time: 2:20 PM
 */

namespace common\utilities;

class Number
{
    const CURRENCY_VND = 'vnd';
    
    public static function moneyPattern()
    {
        return "/^[0-9,]+$/";
    }

    public static function moneyToDouble($money)
    {
        $money = str_replace(',', '', $money);
        return doubleval($money);
    }

    public static function convertPhone($strPhone)
    {
        $phone = str_replace(array("+", " "), "", $strPhone);
        $fistNumber = $phone[0];
        if($fistNumber === '0'){
            $phone = '84'.substr($phone, 1, strlen($phone)-1);
        }
        return $phone;
    }

}