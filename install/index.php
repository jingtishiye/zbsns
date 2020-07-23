<?php

define('DEBUG', 2);
define('ROOT_PATH', substr(dirname(__FILE__), 0, -7));
define('INSTALL_PATH', dirname(__FILE__).'/');



$conf = (include ROOT_PATH.'data/config/conf.default.php');//得到默认配置
$conf_db = (include ROOT_PATH.'data/config/db.default.php');//得到默认配置
$conf = array_merge($conf,$conf_db);

$conf['log_path'] = ROOT_PATH.$conf['log_path']; 
$conf['tmp_path'] = ROOT_PATH.$conf['tmp_path']; 


include ROOT_PATH.'basephp/base.php';

include INSTALL_PATH.'func.php';

$action = param('action');

is_file(ROOT_PATH.'/data/config/db.php') AND DEBUG != 2 AND message(0, jump('安装向导', '../'));



// 第一步，阅读
if(empty($action)) {

	include INSTALL_PATH."view/htm/index.htm";
	
} elseif($action == 'env') {
	
	if($method == 'GET') {
		$succeed = 1;
		$env = $write = array();
		get_env($env, $write);
		include INSTALL_PATH."view/htm/env.htm";
	} else {
	
	}
	
} elseif($action == 'db') {
	
	if($method == 'GET') {

        

		$succeed = 1;
		$mysql_support = function_exists('mysql_connect');
		$pdo_mysql_support = extension_loaded('pdo_mysql');
		$myisam_support = extension_loaded('pdo_mysql');
		$innodb_support = extension_loaded('pdo_mysql');
		
		(!$mysql_support && !$pdo_mysql_support) AND message(-1, 'evn_not_support_php_mysql');

		include INSTALL_PATH."view/htm/db.htm";
		
	} else {
		
		$type = param('type','pdo_mysql');	
		$engine = param('engine','InnoDB');	
		$host = param('host','127.0.0.1');	
		$name = param('name','5isns');
		$tablepre = param('tablepre','5isns_');
		$user = param('user','root');
		$password = param('password', '', FALSE);
		$force = param('force');
		
		$adminemail = param('adminemail');
		$adminuser = param('adminuser');
		$adminpass = param('adminpass');
		
		empty($host) AND message('host', '数据库地址为空');
		empty($name) AND message('name', '数据库名为空');
		empty($user) AND message('user', '数据库用户为空');
		empty($adminpass) AND message('adminpass', '管理员密码未输入');

		empty($adminemail) AND message('adminemail', '用户名为空');
		
		
		
		// 设置超时尽量短一些
		//set_time_limit(60);
		ini_set('mysql.connect_timeout',  5);
		ini_set('default_socket_timeout', 5); 

		$conf['db']['type'] = $type;	
		$conf['db']['mysql']['master']['host'] = $host;
		$conf['db']['mysql']['master']['name'] = $name;
		$conf['db']['mysql']['master']['user'] = $user;
		$conf['db']['mysql']['master']['password'] = $password;
		$conf['db']['mysql']['master']['tablepre'] = $tablepre;
		$conf['db']['mysql']['master']['engine'] = $engine;
		$conf['db']['pdo_mysql']['master']['host'] = $host;
		$conf['db']['pdo_mysql']['master']['name'] = $name;
		$conf['db']['pdo_mysql']['master']['user'] = $user;
		$conf['db']['pdo_mysql']['master']['password'] = $password;
		$conf['db']['pdo_mysql']['master']['tablepre'] = $tablepre;
		$conf['db']['pdo_mysql']['master']['engine'] = $engine;
		
		$_SERVER['db'] = $db = db_new($conf['db']);
		// 此处可能报错
		$r = db_connect($db);
		
		if($r === FALSE) {
			if($errno == 1049 || $errno == 1045) {
				if($type == 'mysql') {
					mysql_query("CREATE DATABASE $name");
					$r = db_connect($db);
				} elseif($type == 'pdo_mysql') {
					if(strpos(':', $host) !== FALSE) {
						$arr = explode(':', $host);
						$host = $arr[0];
						$port = $arr[1];
					} else {
						//$host = $host;
						$port = 3306;
					}
					try {
						$attr = array(
							PDO::ATTR_TIMEOUT => 5,
							//PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
						);
						$link = new PDO("mysql:host=$host;port=$port", $user, $password, $attr);
						$r = $link->exec("CREATE DATABASE `$name`");
						if($r === FALSE) {
							$error = $link->errorInfo();
							$errno = $error[1];
							$errstr = $error[2];
						}
					} catch (PDOException $e) {
						$errno = $e->getCode();
						$errstr = $e->getMessage();
					}
				}
			}
			if($r === FALSE) {
				message(-1, "$errstr (errno: $errno)");
				
			}
		}
		
		$conf['cache']['mysql']['db'] = $db; // 这里直接传 $db，复用 $db；如果传配置文件，会产生新链接。
		
		if(empty($cache)){
			$_SERVER['cache'] = $cache = !empty($conf['cache']) ? cache_new($conf['cache']) : NULL;
		}
		
		
		
		// 设置引擎的类型
		if($engine == 'innodb') {
			$db->innodb_first = TRUE;
		} else {
			$db->innodb_first = FALSE;
		}
		
		// 连接成功以后，开始建表，导数据。
		
		install_sql_file(INSTALL_PATH.'install.sql',$tablepre);
		
		// 初始化
		copy(ROOT_PATH.'data/config/db.default.php', ROOT_PATH.'data/config/db.php');
		
		// 管理员密码
		$salt = xn_rand(16);
		$password = md5($adminpass.$salt);

		$update = array('username'=>$adminuser, 'usermail'=>$adminemail, 'password'=>$password, 'salt'=>$salt, 'regtime'=>$time, 'userip'=>$longip);

		db_update('user', array('id'=>1), $update);
		
		$replace = array();
		$replace['db'] = $conf['db'];
		$replace['auth_key'] = xn_rand(64);
		$replace['installed'] = 1;
		file_replace_var(ROOT_PATH.'data/config/db.php', $replace);
		
		
		xn_mkdir(ROOT_PATH.'upload/pic', 0777);
		xn_mkdir(ROOT_PATH.'upload/attach', 0777);
		xn_mkdir(ROOT_PATH.'upload/avatar', 0777);

		
		message(0, jump('恭喜您！该系统已经成功安装！', '../'));
	}
} 
 

?>
