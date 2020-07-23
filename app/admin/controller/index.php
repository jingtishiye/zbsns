<?php

!defined('DEBUG') AND exit('Access Denied.');
include BASEPHP_FUNPATH . 'xn_zip.func.php';

if($action == 'login') {


	if($method == 'GET') {



		include ADMIN_PATH."view/login.html";

	} else if($method == 'POST') {

		$email = param('usermail');			// 邮箱或者用户名
		$password = param('password');
		empty($email) AND message('email', '邮箱为空');
		if(is_email($email)) {
			$_user = user_read_by_email($email);
			empty($_user) AND message('email', '邮箱不存在');
		} else {
			$_user = user_read_by_username($email);
			empty($_user) AND message('email', '用户名不存在');
		}

        $password = md5($password.$_user['salt']);

		!is_password($password) AND message('password', '密码长度不合法');

		$check = ($password == $_user['password']);
		
		!$check AND message('password', '密码错误');

		user_update($_user['id'], array('last_login_ip'=>$longip, 'last_login_time' =>$time , 'logins+'=>1));

		$uid = $_user['id'];

		$_SESSION['uid'] = $uid;

		admin_token_set();

		user_token_set($uid);

		xn_log('login successed. uid:'.$_user['id'], 'admin_login');

		message(0, '登录成功');

	}
} elseif ($action == 'update') {

 $s = http_post_curl('http://www.5isns.com/onlineupdate-up_ver',array('ver'=>$conf['version'],'domain'=>$_SERVER["SERVER_NAME"]));
      $result = xn_json_decode($s);

      if($result['code']==0){
       
$url = 'http://www.5isns.com/update/'.$result['verfile'];
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);//在需要用户检测的网页里需要增加下面两行

$contents = curl_exec($ch);

curl_close($ch);
 $tmpfile = $conf['upload_path'] . 'update/update.zip';

        
          
         file_put_contents($tmpfile, $contents) or message(-1, '写入文件失败');
      
       xn_unzip($tmpfile, './');
if(file_exists($conf['upload_path'] . 'update/data.sql')){
	$s = file_get_contents($conf['upload_path'] . 'update/data.sql');
if (strpos($s, "\xEF\xBB\xBF") === 0) { //\x表示16进制
     $s = substr($s, 3);
 }
	if($conf['db']["pdo_mysql"]["master"]["tablepre"]!='5isns_'){
		$s=str_replace('5isns_',$conf['db']["pdo_mysql"]["master"]["tablepre"],$s);
	}
	$s = str_replace(";\r\n", ";\n", $s);
	$arr = explode(";\n", $s);

	 foreach ($arr as $sql) {
		$sql = trim($sql);
		if(empty($sql)) continue;
		$arr = explode(";\n", $s);
		
		db_exec($sql) === FALSE AND message(-1, "sql: $sql, errno: $errno, errstr: $errstr");
	}

unlink($conf['upload_path'] . 'update/data.sql');
}


unlink($conf['upload_path'] . 'update/update.zip');

if(file_exists($conf['upload_path'] . 'update/conf.php')){
	$config = include  $conf['upload_path'] . 'update/conf.php';
    unlink($conf['upload_path'] . 'update/conf.php');
}

if(!empty($result['config'])){
	$config = $result['config'];
}
$config['version'] = $result['version'];

file_replace_var(DATA_PATH.'config/conf.default.php', $config);


cache_truncate();
$runtime = NULL;
rmdir_recusive($conf['tmp_path'], 1);
 message(0,  $result['mess']);

      }else{
        message(-1, $result['mess']);
      }
	
	


} elseif ($action == 'logout') {


	admin_token_clean();
    user_token_clear();
	message(0, '注销成功', array('url'=>r_url('index-login')));

} elseif ($action == 'phpinfo') {

	unset($_SERVER['conf']);
	unset($_SERVER['db']);
	unset($_SERVER['cache']);
	phpinfo();
	exit;

} else {


    $menulist = menu_find(array('pid'=>0,'is_hide'=>0),array('sort'=>-1),'',menu_count(array('pid'=>0,'is_hide'=>0)));
   


    $menulist = menu_getlevel($menulist,1,'');

	include ADMIN_PATH.'view/index.html';

}



?>
