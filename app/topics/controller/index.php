<?php
!defined('DEBUG') AND exit('Access Denied.');

$hot_topiclist_6 = topic_find(array('status' => 1,'type'=>2), array('view'=>-1), 1, 6);

$hot_tags = topiccate_find(array('status' => 1), array('num'=>-1), 1, 6);
$type = param(3,1);
$page = param('page',1);

$where = array('status' => 1,'type'=>2);
if($type==1){
$order = array('settop'=>-1,'id'=>-1);
}elseif($type==2){
$order = array('settop'=>-1,'choice'=>-1);
}else{
$order = array('settop'=>-1,'view'=>-1);
}
$pagenum    = $conf['pagesize'];
$topicslist   = topic_find($where, $order, $page, $pagenum);
$totalnum   = topic_count($where);
$pagination = pagination(r_url('thread-list-'.$type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);



include $conf['module_view_path'].'thread-list.html';	



?>