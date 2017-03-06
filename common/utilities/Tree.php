<?php
namespace common\utilities;

/**
 * Use when table have parent_id field 
 * 
 * Class Tree
 * @package common\utilities
 */
class Tree
{
    private static function _convertName2TreeField($depth)
    {
        return str_repeat('- ', 2 * $depth);
    }

    private static function _flatTreeSort($data, $parentID = 0, &$result = array(), &$depth = 0, &$path = '/')
    {
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parentID) {
                $value['depth'] = $depth;
                $value['path']  = $path;
                array_push($result, $value);
                unset($data[$key]);
                $oldParent = $parentID;
                $parentID  = $value['id'];
                $depth++;
                $path .= $parentID . '/';
                self::_flatTreeSort($data, $parentID, $result, $depth, $path);
                $parentID = $oldParent;
                $depth--;
                $path = @substr($path, 0, strrpos($path, '/', -2) + 1);
            }
        }

        return $result;
    }

    public static function getTreeReferenceData($data)
    {
        $options = array();
        $data    = self::_flatTreeSort($data);
        $depth   = -1;
        foreach ($data as $value) {
            if ($depth != -1) {
                if ($value['depth'] > $depth) {
                    continue;
                } else {
                    $depth = -1;
                }
            }
            $options[$value['id']] = self::_convertName2TreeField($value['depth']) . $value['name'];
        }

        return $options;
    }

}