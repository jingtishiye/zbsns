<?php

class plugin{

private $_listener = array();

public function __construct()
{

$hasactiveplugins = db_find_all('plugins',array('status'=>1),'','name');


if($hasactiveplugins)
{
foreach($hasactiveplugins as $k => $v)
{

$pluginfile = PLUGIN_PATH.$v['name'].'/'.$v['name'].'.php';

if(file_exists($pluginfile))
{
include_once($pluginfile);
$classname = $v['name']; 

if(class_exists($classname))
{
  
  $this->_listener[$classname] = new $classname();
  
}

}
}
}
}

public function installAddon($info){


$info['status']=1;
$info['create_time']=time();

$plugin_info = db_find_one('plugins',array('name'=>$info['name']));
if($plugin_info){
	message(-1, '插件名有冲突'); 
}
$r = db_insert('plugins',$info);

if($r){
	$this->exec_sql($info['name']);


}else{
	message(-1, '该插件安装失败'); 
}




}
public function uninstallAddon($info){

	$this->exec_sql($info['name'],false);

}
public function exec_sql($name,$install=true){

if($install){
$sql_file = PLUGIN_PATH.$name . '/install.sql';
}else{
$sql_file = PLUGIN_PATH.$name . '/uninstall.sql';
}
$conf = include WWWROOT.'/data/config/db.php';

	if(file_exists($sql_file)){
	$s = file_get_contents($sql_file);
if (strpos($s, "\xEF\xBB\xBF") === 0) {
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


}
}


public function run($name,$method,$data=array()) 
{
$result = '';

$plugin_info = db_find_one('plugins',array('name'=>$name,'status'=>1));
if($plugin_info){
if(method_exists($name,$method))
{

$result .= $this->_listener[$name]->$method($data);
}

return $result;
}else{
	return false;
}



}
}
?>