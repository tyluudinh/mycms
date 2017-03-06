<?php
/**
 * User: thangcest2
 * Date: 25/02/2016
 * Time: 15:08
 */

namespace common\core\oop;


interface ObjectInterface
{
    public function isEmpty();
    
    public function equals($o);

    public function toArray();

    public function getAttribute($data);

}