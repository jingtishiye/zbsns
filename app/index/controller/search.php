<?php
!defined('DEBUG') AND exit('Access Denied.');
$type = param('type',1);
$keyword = param('keyword');
$page = param('page',1);
$pagenum    = $conf['pagesize'];

$order = array('id'=>'-1');

$type = module_select($type);




if($type==1){//文章帖子
$str = db_concat_field(array('title','keywords'));
$where = array('status' => 1,$str=>array('LIKE'=>$keyword));
$search_list_topic   = topic_find($where, $order, $page, $pagenum);

$totalnum   = db_count('topic',$where);
}elseif($type==2){//文档
$where = array('status' => 1,'title'=>array('LIKE'=>$keyword));
$search_list_topic   = doc_find($where, $order, $page, $pagenum);
$totalnum   = db_count('doccon',$where);
}else{//用户
$str = db_concat_field(array('nickname','statusdes','description'));

$where = array('status' => array('>'=>0),$str=>array('LIKE'=>$keyword));
$search_list_user   = user_find($where, $order, $page, $pagenum);

$totalnum   = db_count('user',$where);
}






$pagination = pagination(r_url('search', array('page' => 'pagenum','type'=>$type,'keyword'=>$keyword)), $totalnum, $page, $pagenum);


include $conf['view_path'].'search.html';	



?>