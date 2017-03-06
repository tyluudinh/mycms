<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 2/27/16
 * Time: 12:35 PM
 */

namespace common\core\oop;


interface IteratorInterface extends \Iterator
{
    /**
     * @return ObjectInterface
     */
    public function next();

    /**
     * @return void
     */
    public function remove();

}