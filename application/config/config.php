<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
$config['base_url']	    = 'http://yongche.aayongche.com/';
$config['admin_url']	= 'http://yongche.aayongche.com/';
$config['static_url']	= 'http://yongche.aayongche.com/';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']		= TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']	= 'c';
$config['function_trigger']		= 'm';
$config['directory_trigger']	= 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 3;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = 'zhaoche';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'		= the name you want for the cookie
| 'sess_expiration'			= the number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'sess_expire_on_close'	= Whether to cause the session to expire automatically
|   when the browser window is closed
| 'sess_encrypt_cookie'		= Whether to encrypt the cookie
| 'sess_use_database'		= Whether to save the session data to a database
| 'sess_table_name'			= The name of the session database table
| 'sess_match_ip'			= Whether to match the user's IP address when reading the session data
| 'sess_match_useragent'	= Whether to match the User Agent when reading the session data
| 'sess_time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']		= 'ci_session';
$config['sess_expiration']		= 7200;
$config['sess_expire_on_close']	= FALSE;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
| 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
|
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['cookie_secure']	= FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips'] = '';

//用户绑定状态
$config['binding_type'] = array(
    0 => '未绑定',
    1 => '支付宝',
    2 => '银行卡',
);

//用户状态
$config['user_status'] = array(
    '0' => '黑名单',
    '1' => '白名单',
);

//用户性别
$config['user_sex'] = array(
    '0' => '男',
    '1' => '女',
);

//消息类别
$config['message_type'] = array(
    '0' => '系统推送消息',
    '1' => '手机短信',
);

//功能模块列表 -- 权限
$config['view_nav'] = array(
    '1' => array('title' => '司机', 'links' => array(
        '10' => array('title' => '司机管理', 'url' => 'chauffeur/index/0'),
        '11' => array('title' => '司机回收站', 'url' => 'chauffeur/index/1'),
        '12' => array('title' => '城市管理', 'url' => 'city/index'),
        '13' => array('title' => '常用地址', 'url' => 'city/useful_index'),
        '14' => array('title' => '城市机场', 'url' => 'city/airport_index'),
    )),
    '2' => array('title' => '用户', 'links' => array(
        '20' => array('title' => '用户管理', 'url' => 'user/index/0'),
        '21' => array('title' => '用户回收站', 'url' => 'user/index/1'),
        '22' => array('title' => '用户发票', 'url' => 'user/invoice_index'),
    )),
    '3' => array('title' => '订单/充值', 'links' => array(
        '30' => array('title' => '订单管理', 'url' => 'order/index'),
        '31' => array('title' => '充值管理', 'url' => 'pay/index'),
        '32' => array('title' => '给用户充值', 'url' => 'pay/beUserPay'),
        '33' => array('title' => '给用户充值记录', 'url' => 'pay/payLog'),
    )),
    '4' => array('title' => '车辆/计费规则', 'links' => array(
        '40' => array('title' => '服务类型管理', 'url' => 'car/service_type_index'),
        '41' => array('title' => '车辆级别管理', 'url' => 'car/car_level_index'),
        '42' => array('title' => '车辆管理', 'url' => 'car/index'),
        '43' => array('title' => '计费规则管理', 'url' => 'rule/index'),
    )),
    '5' => array('title' => '投诉建议', 'links' => array(
        '50' => array('title' => '投诉建议管理', 'url' => 'feedback/index'),
    )),
    '6' => array('title' => '消息推送', 'links' => array(
        '60' => array('title' => '消息分类管理', 'url' => 'message/category_index'),
        '61' => array('title' => '消息管理', 'url' => 'message/index/0'),
        '64' => array('title' => '消息回收站', 'url' => 'message/index/1'),
        '62' => array('title' => '消息推送管理', 'url' => 'message/push'),
        '63' => array('title' => '消息推送记录', 'url' => 'message/sendRecord'),
    )),
    '7' => array('title' => '员工', 'links' => array(
        '70' => array('title' => '员工管理', 'url' => 'staff/index/0'),
        '71' => array('title' => '员工回收站', 'url' => 'staff/index/1'),
        '72' => array('title' => '部门管理', 'url' => 'staff/department_index'),
    )),
    '8' => array('title' => '系统', 'links' => array(
        '80' => array('title' => '个人信息管理', 'url' => 'system/profile'),
        '81' => array('title' => '修改密码', 'url' => 'system/reset_password'),
        '82' => array('title' => '优惠卡', 'url' => 'system/card_index'),
    )),
);


//订单状态
$config['order_status'] = array(
    '0' => '初始',
    '1' => '已完成',
    '2' => '已取消',
    '3' => '司机已接单',
    '4' => '服务开始',
    '5' => '服务结束',
    '6' => '车辆已出发',
    '7' => '车辆已到，等待上车',
);

//充值方式
$config['pay_type'] = array(
    '1' => '支付宝',
    '2' => '银行卡',
);

//充值状态
$config['pay_status'] = array(
    '0' => '初始',
    '1' => '成功',
    '2' => '失败',
    '3' => '签名错误',
);

//是否需要发票状态
$config['is_post'] = array(
    '0' => '不需要',
    '1' => '需要',
);

//充值发票邮寄方式
$config['post_mode'] = array(
    '0' => '默认',
    '1' => '快递',
    '2' => '平邮',
);

//充值发票邮寄状态
$config['post_status'] = array(
    '0' => '未寄',
    '1' => '已寄',
);

//是否为车型
$config['is_car_model'] = array(
    '0' => '否',
    '1' => '是',
);

//投诉与建议分类
$config['feedback_category'] = array(
    '1' => '投诉',
    '2' => '建议',
);

//用户类型
$config['user_type'] = array(
    '1' => '用户',
    '2' => '司机',
);

//投诉建议处理状态
$config['process_status'] = array(
    '0' => '未处理',
    '1' => '已处理',
);

$config['pay_channel'] = array(
    '1' => 'alipay',
    '2' => 'unionpay',
);

$config['color'] = array(
    '1' => array('name' => '红色', 'code' => '#FF0000;', 'font_code' => '#ffffff;'),
    '2' => array('name' => '黑色', 'code' => '#000000;', 'font_code' => '#ffffff;'),
    '3' => array('name' => '蓝色', 'code' => '#0000FF;', 'font_code' => '#ffffff;'),
    '4' => array('name' => '黄色', 'code' => '#FFFF00;', 'font_code' => '#000000;'),
    '5' => array('name' => '银色', 'code' => '#E6E8FA;', 'font_code' => '#000000;'),
    '6' => array('name' => '棕色', 'code' => '#A67D3D;', 'font_code' => '#ffffff;'),
    '7' => array('name' => '紫色', 'code' => '#CC33CC;', 'font_code' => '#ffffff;'),
    '8' => array('name' => '白色', 'code' => '#ffffff;', 'font_code' => '#000000;'),
    '9' => array('name' => '绿色', 'code' => '#00FF00;', 'font_code' => '#000000;'),
);

//支付宝配置信息
define('ALIPAY_PARTNER', '2088901264408851');//合作伙伴ID
define('ALIPAY_SELLER', 'meiyi@meiyiad.com');//签约支付宝账号或卖家支付宝帐户
define('ALIPAY_NOTIFY_URL', $config['base_url'].'api/pay/payBack');//异步返回消息通知页面，用于告知商户订单状态
define('ALIPAY_CALL_BACK_URL', $config['base_url'].'api/pay/aliPayCallbackUrl');//同步返回消息通知页面，用于提示商户订单状态
define('ALIPAY_INPUT_CHARSET', 'utf-8');//字符编码格式


//银联配置信息
define('UNIONPAY_SUBMIT_URL', 'http://211.154.166.219/qzjy/MerOrderAction/deal.action');//前置请求地址
define('UNIONPAY_UPOMP_PUBLIC_KEY', '');//前置密钥
define('UNIONPAY_NOTIFY_PUBLIC_KEY', APPPATH."key/unionpay/union_public_key.cer");//前置密钥
define('UNIONPAY_MY_ID', '808080201300485');//商户ID
define('UNIONPAY_MY_NAME', '北京车族网际科技有限公司');//商户名称
define('UNIONPAY_MY_PUBLIC_KEY', APPPATH.'key/unionpay/898000000000002.cer');//商户公钥
define('UNIONPAY_MY_PRIVATE_KEY', APPPATH.'key/unionpay_test/898000000000002.p12');//商户私钥
define('UNIONPAY_MY_PRIKEY_PASSWORD', 'CPS12345');//商户私钥密码
define('UNIONPAY_NOTIFY_URL', $config['base_url'].'api/pay/payBack');//回调地址


//应用名称
define('APP_NAME', '智通招车');

//订单超时时间
define('ORDER_TIMEOUT', 1800);


//司机到达用户目的地，超时时候。
define('CHAUFFEUR_TIMEOUT', 1800);

//司机到达用户目的地，用户上车超时时间
define('CHAUFFEUR_USER_TRAIN_TIMEOUT', 1800);


/* End of file config.php */
/* Location: ./application/config/config.php */
