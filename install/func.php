<?php
function form_select($name, $arr, $checked = 0, $id = TRUE) {
	if(empty($arr)) return '';
	$idadd = $id === TRUE ? "id=\"$name\"" : ($id ? "id=\"$id\"" : '');
	$s = "<select name=\"$name\" class=\"form-control\" $idadd> \r\n";
	$s .= form_options($arr, $checked);
	$s .= "</select> \r\n";
	return $s;
}
function form_options($arr, $checked = 0) {
	$s = '';
	foreach((array)$arr as $k=>$v) {
		$add = $k == $checked ? ' selected="selected"' : '';
		$s .= "<option value=\"$k\"$add>$v</option> \r\n";
	}
	return $s;
}
function message($code, $message, $extra = array()) {
	global $ajax, $header, $conf;
	
	$arr = $extra;
	$arr['code'] = $code.'';
	$arr['message'] = $message;
	$header['title'] = $conf['sitename'];

	
	// 防止 message 本身出现错误死循环
	static $called = FALSE;
	$called ? exit(xn_json_encode($arr)) : $called = TRUE;
	echo xn_json_encode($arr);
	
	
	exit;
}
function get_env(&$env, &$write) {
	$env['os']['name'] = '系统';
	$env['os']['must'] = TRUE;
	$env['os']['current'] = PHP_OS;
	$env['os']['need'] = '类 UNIX';
	$env['os']['status'] = 1;
	// glob gzip
	//$env['os']['disable'] = 1;
	
	$env['php_version']['name'] = 'PHP 版本';
	$env['php_version']['must'] = TRUE;
	$env['php_version']['current'] = PHP_VERSION;
	$env['php_version']['need'] = '5.0';
	$env['php_version']['status'] = version_compare(PHP_VERSION , '5') > 0;

	// 目录可写
	$writedir = array(
		'../data/config/',
		'../data/log/',
		'../data/tmp/',
		'../upload/',
		'../plugins/'
	);

	$write = array();
	foreach($writedir as &$dir) {
		$write[$dir] = xn_is_writable('./'.$dir);
	}
}

function install_sql_file($sqlfile,$tablepre) {
	global $errno, $errstr;
	$s = file_get_contents($sqlfile);

	if($tablepre!='5isns_'){
		$s=str_replace('5isns_',$tablepre,$s);
	}
	$s = str_replace(";\r\n", ";\n", $s);
	$arr = explode(";\n", $s);

	 foreach ($arr as $sql) {
		$sql = trim($sql);
		if(empty($sql)) continue;
		$arr = explode(";\n", $s);
		db_exec($sql) === FALSE AND message(-1, "sql: $sql, errno: $errno, errstr: $errstr");
	}
}



?>