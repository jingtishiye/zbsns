<?php

!defined('DEBUG') AND exit('Access Denied.');
define('ADMIN_PATH', APP_PATH.'admin'.DS);
include MODEL_PATH.'admin.func.php';

if(DEBUG < 3) {
   if(empty($user)||$user['is_inside'] != 1) {

		setcookie('5isns_sid', '', $time - 86400);
		
		$_REQUEST[1] = 'index';
		$_REQUEST[2] = 'login';
	}
	
	admin_token_check();

}

$header = array(
	'title'=>'招标信息后台管理',
	'keywords'=>'', 
	'description'=>strip_tags($conf['sitebrief'])
);


?>