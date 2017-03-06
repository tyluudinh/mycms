<?php

namespace common\utilities;

class Dir
{
    /**
     * create dir and sub dir
     *
     * @param string $dir
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public static function mkdirs($dir, $mode = 0777, $recursive = true)
    {
        if (is_null($dir) || $dir === "") {
            return false;
        }

        if (is_dir($dir) || $dir === "/") {
            return false;
        }

        if (!self::mkdirs(dirname($dir), $mode, $recursive)) {
            mkdir($dir, $mode);
        }

        return false;
    }

}