<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/25/15
 * Time: 3:08 PM
 */

namespace common\core\observer;


use common\core\oop\ObjectScalar;
use common\core\oop\Vector;

/**
 * Class Publisher
 * @package common\core\observer
 */

class Publisher
{
    /**
     * @var bool
     */
    protected $_change = false;

    /**
     * @var Vector | ObserverInterface[]
     */
    protected $_observers;

    /**
     * init observers with zero member
     */
    public function __construct()
    {
        $this->_observers = new Vector([]);
    }

    /**
     * @param ObserverInterface | ObjectScalar $o
     */
    public function addObserver(ObserverInterface $o) {
        if (!$this->_observers->contains($o)) {
            $this->_observers->addElement($o);
        }
    }

    /**
     * @param ObserverInterface | ObjectScalar $o
     */
    public function removeObserver(ObserverInterface $o) {
        $this->_observers->removeElement($o);
    }

    /**
     * @return $this
     */
    public function setChange() {
        $this->_change = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasChange() {
        return $this->_change === true;
    }

    public function clearChange()
    {
        $this->_change = false;
    }

    /**
     * @return void
     */
    public function notifyObservers() {
        if (!$this->_change) {
            return;
        }

        $this->clearChange();
        $size = $this->_observers->size();
        for ($i = 0; $i < $size; $i++) {
            $observer = $this->_observers->elementAt($i);
            if ($observer instanceof ObserverInterface) {
                $observer->update($this);
            }
        }
    }

    public function getAllObservers()
    {
        return $this->_observers;
    }

}