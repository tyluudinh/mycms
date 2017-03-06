<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/14/16
 * Time: 2:07 PM
 */

namespace common\core\httpApi;


use common\core\oop\ObjectScalar;

interface ServiceHttpResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @return ObjectScalar
     */
    public function getHeaders();

    /**
     * @return ObjectScalar
     */
    public function getBody();
    
}