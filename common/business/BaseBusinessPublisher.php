<?php

/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/10/16
 * Time: 10:04 AM
 *
 * For the business want to implement observer design pattern
 *
 */

namespace common\business;

use common\core\observer\Publisher;

class BaseBusinessPublisher extends Publisher
{
    public function stateChanged()
    {
        $this->setChange();
        $this->notifyObservers();
    }

}