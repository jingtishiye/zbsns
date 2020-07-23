<?php
!defined('DEBUG') AND exit('Access Denied.');




$type = param(2,1);
$page = param('page',1);

if($conf['no_img_index']==1) {
$where = array('status' => 1,'img_num'=>array('>'=>0),'type'=>2);//有图片的首页
}else{
$where = array('status' => 1,'type'=>2);//无图片
}

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
$pagination = pagination(r_url('index-'.$type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);


$tj_topiclist_6 = cache_get('tj_topiclist_6');
if($tj_topiclist_6 === NULL) {
		$tj_topiclist_6 = topic_find(array('status' => 1,'type'=>2), array('choice'=>-1,'view'=>-1), 1, 6);
		cache_set('tj_topiclist_6', $tj_topiclist_6, 60);
}


$rz_userlist_3 = cache_get('rz_userlist_3');
if($rz_userlist_3 === NULL) {
		$rz_userlist_3 = user_find(array('status' => 1,'rz'=>array('>'=>0)), '', 1, 3);
		cache_set('rz_userlist_3', $rz_userlist_3, 60);
}

//$yylist = db_find('addons',array('status'=>1),array('id'=>-1), 1, 8);
include $conf['view_path'].'index.html';	



?>