<?php

!defined('DEBUG') AND exit('Forbidden');


define('MODEL_PATH', APP_PATH.$module.DS.'model'.DS);

// 可以合并成一个文件，加快速度
$include_model_files = array (
	PUBLIC_MODEL_PATH.'attach.model.php',
	PUBLIC_MODEL_PATH.'check.model.php',
    PUBLIC_MODEL_PATH.'menu.model.php',
	PUBLIC_MODEL_PATH.'kv.model.php',
	PUBLIC_MODEL_PATH.'queue.model.php',
	PUBLIC_MODEL_PATH.'form.model.php',
	PUBLIC_MODEL_PATH.'misc.model.php',
	PUBLIC_MODEL_PATH.'session.model.php',

	PUBLIC_MODEL_PATH.'user.model.php',
	PUBLIC_MODEL_PATH.'topiccate.model.php',
	PUBLIC_MODEL_PATH.'topicclass.model.php',

    PUBLIC_MODEL_PATH.'topic.model.php',
    PUBLIC_MODEL_PATH.'nav.model.php',
    PUBLIC_MODEL_PATH.'comment.model.php',
    PUBLIC_MODEL_PATH.'doc.model.php',
    PUBLIC_MODEL_PATH.'docclass.model.php',
    PUBLIC_MODEL_PATH.'pointnote.model.php',

);


if(DEBUG) {
	foreach ($include_model_files as $model_files) {
		include $model_files;
	}
} else {
	
	$model_min_file = $conf['tmp_path'].'index.model.min.php';
	$isfile = is_file($model_min_file);
	if(!$isfile) {
		$s = '';
		foreach($include_model_files as $model_files) {
			
			// 压缩后不利于调试，有时候碰到未结束的 php 标签，会暴 500 错误
			//$s .= php_strip_whitespace(_include($model_files));

			$t = file_get_contents($model_files);
			$t = trim($t);
			$t = ltrim($t, '<?php');
			$t = rtrim($t, '?>');
			$s .= "<?php\r\n".$t."\r\n?>";
            
		}
		
		$r = file_put_contents($model_min_file, $s);
		unset($s);
	}
	include $model_min_file;
}

$sid = sess_start();


$uid = intval(_SESSION('uid'));

empty($uid) AND $uid = user_token_get() AND $_SESSION['uid'] = $uid;

$user = user_read($uid);

if($uid>0){
	$user = up_usergrade($user);
	
	$userqx = user_qx_cache($uid);
	

}

if($module!='admin'){
$browser = get__browser();

//index是默认的模块目录
$browser_name = 'pc';
if(is_dir(TEM_PATH.$browser['device'].$module.'/')){
$browser_name = $browser['device'];
}



$conf['view_dir'] = $conf['base_web_url'].'template'.DS.$browser_name.DS;

$conf['view_path'] = TEM_PATH.$browser_name.DS;


$conf['module_view_dir'] = $conf['base_web_url'].'app'.DS.$module.DS.$conf['view_url'].$browser_name.DS;


$conf['module_view_path'] = APP_PATH.$module.DS.$conf['view_url'].$browser_name.DS;



$user_mess_count = user_mess_count($uid);

$nav_top_all = nav_top();//头部导航

$nav_top_count = count($nav_top_all);

if($nav_top_count>3){
   $nav_top = array_slice($nav_top_all,0,3);

   array_splice($nav_top_all,0,3);

   $nav_top_end = $nav_top_all;
	
}else{
	$nav_top = $nav_top_all;
}

$nav_bottom = nav_bottom();



$plugin->run('spider','getspider');
// 头部 header.inc.htm 
$header = array(
	'title'=>$conf['sitename'],
	'keywords'=>'5isns,内容付费系统,文库源码,知识付费,php,免费开源,内容管理系统源码,源码', 
	'description'=>strip_tags($conf['sitebrief']),
);
}else{

$conf['view_dir'] = $conf['base_web_url'].'app'.DS.'admin'.DS.'view'.DS;
$conf['view_path'] = APP_PATH.'admin'.DS.'view'.DS;

}

$conf['public_dir'] = $conf['base_web_url'].'public'.DS;




$extra_file = APP_PATH.$module.DS.'extra.php';
if(is_file($extra_file)){
   include $extra_file;
}




$route = param(1, 'index');


if(!defined('SKIP_ROUTE')) {
	$action = param(2);
	
	is_numeric($action) AND $action = '';
	
    $controller_file = APP_PATH.$module.DS.'controller'.DS.$route.'.php';
    $index_controller_file = APP_PATH.'index'.DS.'controller'.DS.$route.'.php';
	if(is_file($controller_file)){
        include $controller_file;
	}elseif(is_file($index_controller_file)){

		include $index_controller_file;
	}elseif(is_file(PUBLIC_CON_PATH.$route.'.php')){
		include PUBLIC_CON_PATH.$route.'.php';
	}else{

		message(-1, '无该模块或者无此文件');
	}
	

}



?>