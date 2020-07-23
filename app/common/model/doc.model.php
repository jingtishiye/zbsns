<?php

// ------------> 最原生的 CURD，无关联其他数据。

function doc__create($arr) {

	$r = db_create('doccon', $arr);

	return $r;
}

function doc__update($id, $arr) {

	$r = db_update('doccon', array('id'=>$id), $arr);

	return $r;
}

function doc__read($id) {

	$doc = db_find_one('doccon', array('id'=>$id));

	return $doc;
}

function doc__delete($id) {

	$r = db_delete('doccon', array('id'=>$id));

	return $r;
}

function doc__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$doclist = db_find('doccon', $cond, $orderby, $page, $pagesize, 'id');

	return $doclist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function doc_create($arr) {

	$r = doc__create($arr);


	return $r;
}

function doc_update($id, $arr) {

	$r = doc__update($id, $arr);


	return $r;
}

function doc_read($id) {


		$doc = doc__read($id);
		doc_format($doc);
		return $doc;


}

// 关联数据删除
function doc_delete($id) {
	

	$r = doc__delete($id);


	return $r;
}

function doc_find($cond = array(), $orderby = array('id'=>-1), $page = 1, $pagesize = 1000) {
	
	$doclist = doc__find($cond, $orderby, $page, $pagesize);
	if($doclist) foreach ($doclist as &$doc) doc_format($doc);
	
	return $doclist;
}

// ------------> 其他方法

function doc_format(&$doc) {
	global $conf;
	if(empty($doc)) return;
	
    
    $doc['create_time_fmt'] = humandate($doc['create_time']);
    $doc['ratystar'] = 'star-'.$doc['raty']*10;
   // $doc['user'] = user_read($doc['uid']);
    $doc['user'] = user_read_cache($doc['uid']);
    $doc['fileinfo'] = file_read($doc['fileid']);

    $doc['fileinfo']['onlyname'] = str_replace('.'.$doc['fileinfo']['ext'],'',$doc['fileinfo']['savename']);

    if(!empty($doc['keywords'])){
    	$keywords_arr = explode(',', $doc['keywords']);

    	$doc['keywords_arr'] = topiccate_find(array('name'=>$keywords_arr),array('num'=>-1),1,count($keywords_arr));
    	
    }


}

function doc_count($cond = array()) {

	$n = db_count('doccon', $cond);

	return $n;
}

function doc_maxid() {

	$n = db_maxid('doccon', 'id');

	return $n;
}
function doc_read_by_hash($hash) {

	$info = db_find_one('doccon', array('sha1'=>$hash));
	doc_format($info);

	return $info;
}


// views + 1
function doc_inc_views($id, $n = 1) {
	
	global $conf, $db;
	$tablepre = $db->tablepre;
	if(!$conf['update_views_on']) return TRUE;
	$sqladd = !in_array($conf['cache']['type'], array('mysql', 'pdo_mysql')) ? '' : ' LOW_PRIORITY';
	$r = db_exec("UPDATE$sqladd `{$tablepre}doccon` SET view=view+$n WHERE id='$id'");
	
	return $r;
}
?>