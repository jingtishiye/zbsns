<?php

!defined('DEBUG') AND exit('Access Denied.');


if($action == 'list'||empty($action)) {
$pagination = '';
$hot_tags = topiccate_find(array('status' => 1), array('num'=>-1), 1, 6);

$id = param(2,0);



$type = param(3,1);
$type = module_select($type);




$topiccate_info = topiccate_read($id);
empty($topiccate_info) AND message(-1, '话题不存在');
$sub_topiccatelist = db_find_all('topiccate',array('pid'=>$id));

$header['title'] = $topiccate_info['name'].'-'.$conf['sitename']; 
$header['keywords'] = ''; 
$header['description'] = $topiccate_info['description_fmt'];


if(!$type){
	//所有的模块都不满足
	
}else{
$page = param('page',1);
$idarr = andother_find_arr('tagsandother',$type,$id,'tid','did',true);

empty($idarr) AND $idarr = 0;

$where = array('status' => 1,'id'=>$idarr);
$order = array('settop'=>-1,'choice'=>-1,'id'=>-1);

$pagenum    = $conf['pagesize'];

if($type==1){
	$topicslist   = topic_find($where, $order, $page, $pagenum);

$totalnum   = topic_count($where);
$pagination = pagination(r_url('tags-'.$id.'-'.$type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);
}else{//这里放文档
	$topicslist   = doc_find($where, $order, $page, $pagenum);

    $totalnum   = doc_count($where);
    $pagination = pagination(r_url('tags-'.$id.'-'.$type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);
}
}







	
include $conf['view_path'].'tag-artlist.html';

}elseif($action == 'taglist'){
	$arr = array (
		'A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G',
		'H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M','N'=>'N',
		'O'=>'O','P'=>'P','Q'=>'Q','R'=>'R','S'=>'S','T'=>'T','U'=>'U',
		'V'=>'V','W'=>'W','X'=>'X','Y'=>'Y','Z'=>'Z'	
	);

	$totalnum   = topiccate_count(array('status'=>1));

    	$maxpage = ceil($totalnum/40);

        $maxpage = $maxpage==0?1:$maxpage;

    	

		$page = mt_rand(1,$maxpage);	
	   
		if($maxpage==1){
			$show=0;
		}else{
			$show=1;
		}



$topiccate_list = topiccate_find(array('status'=>1),array('num'=>-1),$page,40);

$tagarr = array();

foreach ($arr as $key => $value) {

	if($topiccate_list){
		foreach ($topiccate_list as $k => $v) {
		if($v['characters']==$value){
            $tagarr[$key][] = $v;
			
			
		}
		
	}
	}
	
	
}


include $conf['view_path'].'taglist.html';
}

?>