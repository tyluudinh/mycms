<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 1/7/16
 * Time: 11:09 AM
 */

namespace common\utilities;


class Paging
{
    public static function toOffset($page, $limit){
        return $limit * ($page - 1);
    }
}