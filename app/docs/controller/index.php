<?php
!defined('DEBUG') AND exit('Access Denied.');






$hot_doclist_6 = doc_find(array('status' => 1), array('view'=>-1), 1, 6);

$hot_tags = topiccate_find(array('status' => 1), array('num'=>-1), 1, 6);
$type = param(3,1);
$page = param('page',1);

$where = array('status' => 1);
if($type==1){
$order = array('settop'=>-1,'id'=>-1);
}elseif($type==2){
$order = array('settop'=>-1,'choice'=>-1);
}else{
$order = array('settop'=>-1,'view'=>-1);
}
$pagenum    = $conf['pagesize'];
$docslist   = doc_find($where, $order, $page, $pagenum);

$totalnum   = doc_count($where);
$pagination = pagination(r_url('doc-list-'.$type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);

include $conf['module_view_path'].'doc-list.html';	



?>