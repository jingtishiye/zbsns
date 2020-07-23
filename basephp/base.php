<?php
define('DS', '/');
!defined('DEBUG') AND define('DEBUG', 1); // 0: 关闭 1: 开发 2: 调试
!defined('BASEPHP_PATH') AND define('BASEPHP_PATH', dirname(__FILE__).'/');
!defined('BASEPHP_FUNPATH') AND define('BASEPHP_FUNPATH', dirname(__FILE__).'/func/');
!defined('BASEPHP_CLASSPATH') AND define('BASEPHP_CLASSPATH', dirname(__FILE__).'/class/');
!defined('APP_PATH') AND define('APP_PATH', substr(dirname(__FILE__), 0, -7).'app/');
!defined('ROOT_PATH') AND define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
!defined('EXTEND_PATH') AND define('EXTEND_PATH', ROOT_PATH.'extend' . DS);
!defined('PUBLIC_MODEL_PATH') AND define('PUBLIC_MODEL_PATH', APP_PATH.'common/model/');
!defined('PUBLIC_COMMON_PATH') AND define('PUBLIC_COMMON_PATH', APP_PATH.'common/');
!defined('PUBLIC_CON_PATH') AND define('PUBLIC_CON_PATH', APP_PATH.'common/controller/');
!defined('DATA_PATH') AND define('DATA_PATH', ROOT_PATH.'data/');
define('PLUGIN_PATH', ROOT_PATH.'plugins/');


function_exists('ini_set') AND ini_set('display_errors', DEBUG ? '1' : '0');
error_reporting(DEBUG ? E_ALL : 0);

$get_magic_quotes_gpc = get_magic_quotes_gpc();
/*用于PHP 指令 magic_quotes_gpc为true，对所有的 GET、POST 和 COOKIE 数据自动运行 addslashes()。
使用 get_magic_quotes_gpc() 进行检测为假时使用addslashes函数*/

$starttime = microtime(1);
$time = time();

// 头部，判断是否运行在命令行下
define('IN_CMD', !empty($_SERVER['SHELL']) || empty($_SERVER['REMOTE_ADDR']));
//REMOTE_ADDR浏览当前页面的用户的 IP 地址,REQUEST_URI 用来指定要访问的页面

if(IN_CMD) {
	!isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] = '';
	!isset($_SERVER['REQUEST_URI']) AND $_SERVER['REQUEST_URI'] = '';
	!isset($_SERVER['REQUEST_METHOD']) AND $_SERVER['REQUEST_METHOD'] = 'GET';
} else {
	header("Content-type: text/html; charset=utf-8");
}



empty($conf) AND $conf = array('db'=>array(), 'cache'=>array(), 'tmp_path'=>'./', 'log_path'=>'./', 'timezone'=>'Asia/Shanghai');
empty($conf['tmp_path']) AND $conf['tmp_path'] = ini_get('upload_tmp_dir');
empty($conf['log_path']) AND $conf['log_path'] = './'; 


$include_fun_files = array (
	BASEPHP_CLASSPATH.'db_pdo_mysql.class.php',
	BASEPHP_CLASSPATH.'plugin.class.php',
    BASEPHP_FUNPATH.'db.func.php',
    BASEPHP_FUNPATH.'cache.func.php',
    BASEPHP_FUNPATH.'image.func.php',
    BASEPHP_FUNPATH.'array.func.php',
    BASEPHP_FUNPATH.'xn_encrypt.func.php',
    BASEPHP_FUNPATH.'misc.func.php',
    BASEPHP_FUNPATH.'xn_send_mail.func.php'
);


if(DEBUG) {
	
	foreach ($include_fun_files as $fun_files) {
		include $fun_files;
	}
} else {
	
	$fun_min_file = $conf['tmp_path'].'fun.min.php';
	$isfile = is_file($fun_min_file);
	if(!$isfile) {
		$s = '';
		foreach($include_fun_files as $fun_files) {
			
			// 压缩后不利于调试，有时候碰到未结束的 php 标签，会暴 500 错误
			//$s .= php_strip_whitespace($fun_files);

			$t = file_get_contents($fun_files);
			$t = trim($t);
			$t = ltrim($t, '<?php');
			$t = rtrim($t, '?>');
			$s .= "<?php\r\n".$t."\r\n?>";
            
		}
		
		$r = file_put_contents($fun_min_file, $s);
		unset($s);
	}
	include $fun_min_file;
}

// 转换为绝对路径，防止被包含时出错。
substr($conf['log_path'], 0, 2) == './' AND $conf['log_path'] = ROOT_PATH.substr($conf['log_path'], 2); 
substr($conf['tmp_path'], 0, 2) == './' AND $conf['tmp_path'] = ROOT_PATH.substr($conf['tmp_path'], 2); 
substr($conf['upload_path'], 0, 2) == './' AND $conf['upload_path'] = ROOT_PATH.substr($conf['upload_path'], 2); 


$ip = ip();
$longip = ip2long($ip);
$longip < 0 AND $longip = sprintf("%u", $longip); // fix 32 位 OS 下溢出的问题,%u - 不包含正负号的十进制数（大于等于 0）
$useragent = _SERVER('HTTP_USER_AGENT');

// 全局的错误，非多线程下很方便。
$errno = 0;
$errstr = ''; 
!DEBUG AND  set_error_handler('my_error_handler',-1);
DEBUG AND set_error_handler('error_handle', -1);
empty($conf['timezone']) AND $conf['timezone'] = 'Asia/Shanghai';
date_default_timezone_set($conf['timezone']);

// 超级全局变量,方便url重写识别
!empty($_SERVER['HTTP_X_REWRITE_URL']) AND $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
!isset($_SERVER['REQUEST_URI']) AND $_SERVER['REQUEST_URI'] = '';
$_SERVER['REQUEST_URI'] = str_replace('/index.php', '/', $_SERVER['REQUEST_URI']); 
//$_SERVER['REQUEST_URI'] = str_replace('/admin.php', '/', $_SERVER['REQUEST_URI']);


// IP 地址
!isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] = '';
!isset($_SERVER['SERVER_ADDR']) AND $_SERVER['SERVER_ADDR'] = '';

//判断得到是否ajax请求
$ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(trim($_SERVER['HTTP_X_REQUESTED_WITH'])) == 'xmlhttprequest') || param('ajax');
$method = $_SERVER['REQUEST_METHOD'];



// 保存到超级全局变量，防止冲突被覆盖。
$_SERVER['starttime'] = $starttime;
$_SERVER['time'] = $time;
$_SERVER['ip'] = $ip;
$_SERVER['longip'] = $longip;
$_SERVER['useragent'] = $useragent;
$_SERVER['conf'] = $conf;
$_SERVER['errno'] = $errno;
$_SERVER['errstr'] = $errstr;
$_SERVER['method'] = $method;
$_SERVER['ajax'] = $ajax;
$_SERVER['get_magic_quotes_gpc'] = $get_magic_quotes_gpc;



$_REQUEST = array_merge($_COOKIE, $_POST, $_GET, xn_url_parse($_SERVER['REQUEST_URI']));


// 初始化 db cache，这里并没有连接，在获取数据的时候会自动连接。
$db = !empty($conf['db']) ? db_new($conf['db']) : NULL;

$conf['cache']['mysql']['db'] = $db; // 这里直接传 $db，复用 $db；如果传配置文件，会产生新链接。
$cache = !empty($conf['cache']) ? cache_new($conf['cache']) : NULL;
unset($conf['cache']['mysql']['db']); // 用完清除，防止保存到配置文件



$_SERVER['db'] = $db;
$_SERVER['cache'] = $cache; 
$plugin = new plugin();


?>