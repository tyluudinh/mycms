<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 8/4/16
 * Time: 2:08 PM
 */

namespace common\utilities;


class DateTime
{
    public static function dateDiff($timestamp1, $timestamp2)
    {
        $datetime1 = new \DateTime($timestamp1);
        $datetime2 = new \DateTime($timestamp2);
        $interval = $datetime1->diff($datetime2);
        return (int)$interval->format('%a') + 1;
    }

    public static function datetimeDiff($timestamp1, $timestamp2 = null)
    {
        if ($timestamp1 != null) {
            $datetime1 = new \DateTime($timestamp1);
            $datetime2 = new \DateTime($timestamp2 ?: 'now');

            $interval = $datetime1->diff($datetime2);
            $day = (int)$interval->format('%d');
            $hour = (int)$interval->format('%h');
            $minute = (int)$interval->format('%i');
            $output = null;
            if ($day > 1) {
                $output = $output . $day . ' ' . \Yii::t('app', 'days') . ' ';
            }
            if ($day == 1) {
                $output = $output . $day . ' ' . \Yii::t('app', 'day') . ' ';
            }
            if ($hour > 1) {
                $output = $output . $hour . ' ' . \Yii::t('app', 'hours') . ' ';
            }
            if ($hour == 1) {
                $output = $output . $hour . ' ' . \Yii::t('app', 'hour') . ' ';
            }
            if ($minute > 1) {
                $output = $output . $minute . ' ' . \Yii::t('app', 'minutes') . ' ';
            }
            if ($minute == 1) {
                $output = $output . $minute . ' ' . \Yii::t('app', 'minute') . ' ';
            }

            return $output;
        }

        return null;
    }


}