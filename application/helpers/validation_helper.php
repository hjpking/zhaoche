<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Evan Hou
 * Date: 12-6-1
 * Time: 上午8:51
 * wunxin E-commerce management system
 */

/**
 * 检查用户名
 *
 * 检测用户名长度是否为大于6或小于32
 * 并检测用户名是否为邮箱地址。
 *
 * @access	public
 * @param	string	用户名
 * @return	boolean
 */
if (!function_exists('is_username')) {
    function is_username($username)
    {
        if (length_limit($username, 6, 32)) {
            //return filter_var($username, FILTER_VALIDATE_EMAIL);
            return true;
        }
        return false;
    }
}

/**
 * 检查字符串长度
 *
 * 检测字符串长度是否为大于6或小于32
 *
 * @access	public
 * @param	string	用户名
 * @param	string	最小长度
 * @param	string	最大长度
 * @return	boolean
 */
if (!function_exists('length_limit')) {
    function length_limit($str, $min, $max)
    {
        $len = strlen($str);
        if ($len < $min || $len > $max) return false;
        return true;
    }
}

/**
 * 检查URL字符串是否为URL
 *
 * 检查字符串是否为URL
 *
 * @access	public
 * @param	string	URL地址
 * @return	boolean
 */
if (!function_exists('is_url')) {
    function is_url($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}

if (!function_exists('is_mobile')) {
    function is_mobile($mobile)
    {
        if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$mobile) ) {
            return true;
        }

        return false;
    }
}