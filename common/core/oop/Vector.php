<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/25/15
 * Time: 3:07 PM
 */

namespace common\core\oop;


use common\core\oop\exceptions\IllegalStateException;

class Vector implements CollectionInterface
{
    /**
     * @var ObjectInterface[]
     */
    protected $_elementData = [];

    /**
     * @param int
     */
    protected $_elementCount;

    /**
     * @var ObjectInterface[] $obs
     */
    public function __construct($obs = [])
    {
        $this->_elementData = $obs;
        $this->_elementCount = count($obs);
    }

    /**
     * @return int
     */
    public function size()
    {
        return $this->_elementCount;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->_elementCount == 0;
    }

    /**
     * @param ObjectInterface $o
     * @return bool
     */
    public function contains($o)
    {
        return $this->indexOf($o, 0) > 0;
    }

    /**
     * @param ObjectInterface $o
     * @param int $index
     * @return int
     */
    public function indexOf($o, $index = 0) {
        if ($o == null) {
            for ($i = $index; $i < $this->_elementCount; $i++) {
                if ($this->_elementData[$i] == null) return $i;
            }
        }

        for ($i = $index; $i < $this->_elementCount; $i++) {
            if ($o->equals($this->_elementData[$i])) return $i;
        }

        return -1;
    }

    /**
     * @param ObjectInterface $o
     */
    public function addElement($o) {
        $this->_elementData[$this->_elementCount++] = $o;
    }

    /**
     * @param ObjectInterface $o
     * @param $index
     */
    public function insertElementAt($o, $index)
    {
        for ($i = $this->_elementCount; $i > $index+1; $i--) {
            $this->_elementData[$i] = $this->_elementData[$i-1];
        }
        $this->_elementData[$index] = $o;
        $this->_elementCount++;
    }

    /**
     * @param int $index
     */
    public function removeElementAt($index)
    {
        if ($index >= $this->_elementCount) {
            throw new \OutOfBoundsException($index . " index >= " . $this->_elementCount);
        } else if ($index < 0) {
            throw new \OutOfBoundsException("$index index < 0");
        }

        for ($i = $index+1; $i < $this->_elementCount; $i++) {
            $this->_elementData[$i-1] = $this->_elementData[$i];
        }

        $this->_elementCount--;
        unset($this->_elementData[$this->_elementCount]);

    }

    /**
     * @param ObjectInterface $o
     * @return bool
     */
    public function removeElement($o)
    {
        $i = $this->indexOf($o);
        if ($i >= 0) {
            $this->removeElementAt($i);
            return true;
        }

        return false;
    }

    /**
     * @throws \OutOfBoundsException
     * @param int $i
     * @return ObjectInterface
     */
    public function elementAt($i)
    {
        if ($i >= $this->_elementCount) {
            throw new \OutOfBoundsException($i . " >= " . $this->_elementCount);
        }

        return $this->_elementData[$i];
    }

    /**
     * @param int $i
     * @return bool
     */
    public function hasElementAt($i)
    {
        return isset($this->_elementData[$i]);
    }

    /**
     * @throws \LengthException
     * @return ObjectInterface
     */
    public function firstElement()
    {
        if ($this->_elementCount == 0) {
            throw new \LengthException(0);
        }
        return $this->_elementData[0];

    }

    /**
     * @throws \LengthException
     * @return ObjectInterface
     */
    public function lastElement()
    {
        if ($this->_elementCount == 0) {
            throw new \LengthException(0);
        }
        return $this->_elementData[$this->_elementCount-1];
    }

    /**
     * @param $recursive bool
     * @return array
     * convert all elements in Vector to array
     */
    public function toArray($recursive = false)
    {
        foreach ($this->_elementData as $i => $o) {
            if ($recursive) {
                $this->_elementData[$i] = $o->toArray();
            } else {
                $this->_elementData[$i] = $o;
            }
        }
        return $this->_elementData;

    }

    public function getIterator()
    {
        return new VectorIterator($this);
    }

}

class VectorIterator implements IteratorInterface {
    /**
     * @var Vector
     */
    private $_items;

    private $_pos = 0;

    private $_lastRes = -1;

    /**
     * @param Vector $items
     */
    public function __construct(Vector $items)
    {
        $this->_items = $items;
    }

    public function next()
    {
        $item = $this->_items->elementAt($this->_pos);
        $this->_lastRes = $this->_pos;
        $this->_pos++;
        return $item;
    }

    public function key()
    {
        return $this->_pos;
    }

    public function current()
    {
        return $this->_items->elementAt($this->_pos);
    }

    public function valid()
    {
        return $this->_items->hasElementAt($this->_pos);
    }

    public function rewind()
    {
        $this->_pos = 0;
        $this->_lastRes = -1;
    }

    public function remove()
    {
        if ($this->_lastRes === -1) {
            throw new IllegalStateException("Last result in Vector Iterator === -1");
        }

        $this->_items->removeElementAt($this->_lastRes);
        $this->_pos = $this->_lastRes;
        $this->_lastRes = -1;

    }


}