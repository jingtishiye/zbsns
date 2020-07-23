<?php

!defined('DEBUG') AND exit('Access Denied.');


if($action == 'url') {

$plugins_name = param(3);
$func = param('func');
$plugin->run($plugins_name,$func);


}else{
	
}


?>