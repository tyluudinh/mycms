<?php

/**
 * Created by thangcest2@gmail.com
 * Date 12/17/15
 * Time 2:59 AM
 */
namespace common\utilities;

use common\core\oop\ObjectScalar;
use common\Factory;
use yii\base\ErrorException;

class ArraySimple
{
    /**
     * Input Sample
     * array
     * 0 =>
     * [
     * 'key' => 123
     * 'value' => abc
     * ]
     *
     * 1 =>
     * [
     * 'key' => 456
     * 'value' => def
     * ]
     * Out Sample
     * [123 => abc, 456 => def]
     *
     *
     * @param $rows array of object which implemented \ArrayAccess or array, and have valid key
     * @param $fields array of key => value without duplicating $key
     * @param $asObject bool
     * @return array|ObjectScalar
     */
    public static function keyValue($rows, $fields, $asObject = true)
    {
        $data = [];
        foreach ($rows as $row) {
            $data[$row[$fields[0]]] = $row[$fields[1]];
        }

        if ($asObject) {
            return (new ObjectScalar())->setData($data);
        }
        return $data;
    }


    /**
     * Input Sample
    [
    0 => [
    'id' => 2,
    'name' => 'abc',
    ],
    1 => [
    'id' => 3,
    'name' => 'def',
    ]
    ]

     * Output Sample
    [
    2 => [
    'id' => 2,
    'name' => 'abc',
    ],
    3 => [
    'id' => 3,
    'name' => 'def',
    ]
    ]

     * With $group, Input samle
    [
    0 => [
    'id' => 1,
    'cate_id' => 10,
    'name' => 'abc',
    ],
    1 => [
    'id' => 2,
    'cate_id' => 10,
    'name' => 'def',
    ],
    2 => [
    'id' => 3,
    'cate_id' => 5,
    'name' => 'ghi',
    ]
    ]
     * Output sample
    [
    5 => [
    0 => [
    'id' => 3,
    'cate_id' => 5,
    'name' => 'ghi',
    ],
    10 => [
    0 => [
    'id' => 1,
    'cate_id' => 10,
    'name' => 'abc',
    ],
    1 => [
    'id' => 2,
    'cate_id' => 10,
    'name' => 'abc',
    ],
    ],
    ],

     *
     *
     *
     *
     *
     *
     * Replace key of values in $data by value of each element at the $fieldToBeKey
     *
     * @param $data array | array object, object must be instance of \ArrayAccess
     * @param $fieldToBeKey string
     * @param $group string
     * @param $emptyValue string
     * @return array
     */
    public static function makeKeyPath($data, $fieldToBeKey, $group = null, $emptyValue = 'no_value')
    {
        if (!empty($group)) {
            $r = [];
            foreach ($data as $d) {
                if ($fieldToBeKey == null) {
                    $valueOfGroupField = $d[$group] ?: $emptyValue;
                    $r[$valueOfGroupField][] = $d;
                } else {
                    $r[$d[$group]][$d[$fieldToBeKey]] = $d;
                }
            }

        } else {
            $r = [];
            foreach ($data as $d) {
                $r[$d[$fieldToBeKey]] = $d;
            }
        }

        return $r;
    }

    public static function makeKeyPathRecursive($data, $fieldToBeKeys = [], $emptyValue = 'no_value')
    {
        $rd = [];
        $firstKey = array_shift($fieldToBeKeys);
        if (count($fieldToBeKeys) == 0) {
            foreach ($data as $d) {
                $valueOfFirstKey = $d[$firstKey] ?: $emptyValue;
                $rd[$valueOfFirstKey][] = $d;
            }
        } else {
            $valueOfCurrentKeys = [];
            foreach ($data as $d) {
                $valueOfFirstKey = $d[$firstKey] ?: $emptyValue;
                $rd[$valueOfFirstKey][] = $d;
                if (!in_array($valueOfFirstKey, $valueOfCurrentKeys)) {
                    $valueOfCurrentKeys[] = $valueOfFirstKey;
                }
            }
            foreach ($valueOfCurrentKeys as $valueOfCurrentKey) {
                $rd[$valueOfCurrentKey] = self::makeKeyPathRecursive($rd[$valueOfCurrentKey], $fieldToBeKeys);
            }
        }
        return $rd;
    }

    /**
     * Input Sample
    [
    0 => [
    'id' => 2,
    ],
    1 => [
    'id' => 3,
    ]
    ]

     * Output Sample [2,3]
     *
     * @param $data
     * @param $field
     * @param $forceString
     * @return array
     */
    public static function extractByField($data, $field, $forceString = false)
    {
        $in = [];
        foreach ($data as $d) {
            if ($forceString) {
                $in[] = (string) $d[$field];
            } else {
                $in[] = $d[$field];
            }
        }
        return $in;
    }

    public static function random($arr)
    {
        return $arr[array_rand($arr)];
    }

    public static function getLike($arr, $likeString = '')
    {
        $d = [];
        foreach ($arr as $k => $v) {
            if (strpos($k, $likeString) !== false) {
                $d[$k] = $v;
            }
        }
        return Factory::createObject($d);

    }

    public static function getSubArrayByKeys($arr, $keys)
    {
        $d = [];
        foreach ($arr as $k => $v) {
            if (in_array($k, $keys)) {
                $d[$k] = $v;
            }
        }
        return Factory::createObject($d);

    }

    public static function getSubArrayByColumn($arr, $column, $columnValue = '')
    {
        $d = [];
        foreach ($arr as $k => $v) {
            if (!isset($v[$column])) {
                throw new ErrorException("The key '$column' does not existed in input array");
            }
            if ($v[$column] == $columnValue) {
                $d[$k] = $v;
            }
        }
        return Factory::createObject($d);
    }

    public static function sortByColumn($arr, $column)
    {
        $arr = array_values($arr);
        foreach ($arr as $index => $value) {
            if (!isset($value[$column])) {
                unset($arr[$index]);
            }
        }
        $arr = array_values($arr);
        for ($i = 0; $i < count($arr) - 1; $i++) {
            for ($j = $i + 1; $j < count($arr); $j++) {
                if ($arr[$j][$column] <= $arr[$i]{$column}) {
                    $t = $arr[$j];
                    $arr[$j] = $arr[$i];
                    $arr[$i] = $t;
                }
            }
        }
        return $arr;
    }

}