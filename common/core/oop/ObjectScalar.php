<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/12/15
 * Time: 5:13 PM
 */

namespace common\core\oop;

class ObjectScalar implements \ArrayAccess, ObjectInterface
{
    protected $_data = [];

    public function asJson()
    {
        return json_encode($this->_data);
    }

    public function getAttrs()
    {
        return array_keys($this->_data);
    }

    public function getAttribute($name)
    {
        return $this->getData($name);
    }

    /**
     * @param null $key
     * @param null $data
     * @param bool $recursive
     * @return ObjectScalar
     */
    public function setData($key = null, $data = null, $recursive = false)
    {
        if (is_array($key)) {
            if ($recursive) {
                foreach ($key as $i => $v) {
                    if (is_array($v)) {
                        $key[$i] = (new self)->setData($v);
                    }
                }
            }

            $this->_data = $key;
        } else if (!is_object($key) && !empty($key)) {
            $this->_data[$key] = $data;
        }

        return $this;
    }

    public function isEmpty()
    {
        return empty($this->_data);
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function getData($key = null)
    {
        if (empty($key)) {
            return $this->_data;
        } else if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return null;

    }

    public function equals($o)
    {
        return ($this == $o);
    }

    public function __toString() {
        return get_class($this);
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function __get($name) {
        $name = lcfirst($name);

        if ($name == 'data') {
            return $this->_data;
        }

        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        return null;

    }

    public function __call($name, $arguments = []) {
        switch (substr($name, 0, 3)) {
            case 'get' :
                $v = ucfirst(substr($name,3));
                return isset($this->_data[$v]) ? $this->_data[$v] : null;
            case 'set' :
                $v = lcfirst(substr($name,3));
                $this->_data[$v] = empty($arguments) ? null : $arguments[0];
                return;
            case 'has' :
                return array_key_exists(lcfirst(substr($name,3)), $this->_data);
        }
    }

    public function __unset($name) {
        unset($this->_data[$name]);
    }

    public function __isset($name) {
        return isset($this->_data[$name]);
    }

    public static function __callStatic($name, $arguments) {}

    public function toArray()
    {
        return $this->_data;
    }

    /**
     * Convert object to array recursively
     *
     * @return array
     */
    public function toArrayRecursively()
    {
        foreach ($this->_data as $i => &$attr) {
            if ($attr instanceof $this) {
                $this->_data[$i] = $attr->toArrayRecursively();
            }
        }
        return (array) $this->_data;

    }

    /**
     * Implementation of ArrayAccess::offsetSet()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetset.php
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    /**
     * Implementation of ArrayAccess::offsetExists()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetexists.php
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * Implementation of ArrayAccess::offsetUnset()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetunset.php
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    /**
     * Implementation of ArrayAccess::offsetGet()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetget.php
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }

    public function __destruct() {
        unset($this);
    }

}