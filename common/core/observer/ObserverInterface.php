<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/25/15
 * Time: 3:00 PM
 */

namespace common\core\observer;


interface ObserverInterface
{
    /**
     * @param Publisher $publisher
     */
    public function update($publisher);

}