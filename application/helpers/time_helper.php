<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-7-29
 * Time: 上午3:43
 * To change this template use File | Settings | File Templates.
 */
/**
 * 设置用户登陆cookie
 *
 * 设置用户登陆cookie
 *
 * @access	public
 * @param	string	用户信息
 * @param	string	过期时间
 * @return	void
 */
if (!function_exists('timeDiff')) {
    function timeDiff($begin_time, $end_time)
    {
         if($begin_time < $end_time) {
            $startTime = $begin_time;
            $endTime = $end_time;
         } else {
             $startTime = $end_time;
             $endTime = $begin_time;
         }

         $timeDiff = $endTime - $startTime;
         $days = intval($timeDiff / 86400);
         $remain = $timeDiff % 86400;
         $hours = intval($remain / 3600);
         $remain = $remain % 3600;
         $mins = intval($remain / 60);
         $secs = $remain % 60;

         $res = array('day' => $days, 'hour' => $hours, 'min' => $mins, 'sec' => $secs);
         return $res;
    }
}