<?php

!defined('DEBUG') AND exit('Access Denied.');

$modulelist = array(1=>'topics',2=>'docs');
;
$n = array_intersect($modulelist,$conf['module_arr']);

foreach ($modulelist as $key => $value) {
	if(in_array($value,$conf['module_arr'])){
        $type = $key;
		break;
	}
}
dump($type);