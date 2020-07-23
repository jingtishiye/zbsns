<?php

// ------------> 最原生的 CURD，无关联其他数据。

function topic__create($arr) {

	$r = db_create('topic', $arr);

	return $r;
}

function topic__update($id, $arr) {

	$r = db_update('topic', array('id'=>$id), $arr);

	return $r;
}

function topic__read($id) {

	$topic = db_find_one('topic', array('id'=>$id));

	return $topic;
}

function topic__delete($id) {

	$r = db_delete('topic', array('id'=>$id));

	return $r;
}

function topic__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$topiclist = db_find('topic', $cond, $orderby, $page, $pagesize, 'id');

	return $topiclist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function topic_create($arr) {

	$r = topic__create($arr);


	return $r;
}

function topic_update($id, $arr) {

	$r = topic__update($id, $arr);


	return $r;
}

function topic_read($id) {

		$topic = topic__read($id);
		topic_format($topic);
		return $topic;


}

// 关联数据删除
function topic_delete($id) {
	

	$r = topic__delete($id);


	return $r;
}

function topic_find($cond = array(), $orderby = array('id'=>-1), $page = 1, $pagesize = 1000) {
	
	$topiclist = topic__find($cond, $orderby, $page, $pagesize);
	if($topiclist) foreach ($topiclist as &$topic) topic_format($topic);
	
	return $topiclist;
}

// ------------> 其他方法

function topic_format(&$topic) {
	global $conf;
	if(empty($topic)) return;
	
    
    $topic['create_time_fmt'] = humandate($topic['create_time']);
    //$topic['user'] = user_read($topic['uid']);
    $topic['user'] = user_read_cache($topic['uid']);
    if(!empty($topic['keywords'])){
    	$keywords_arr = explode(',', $topic['keywords']);

    	$topic['keywords_arr'] = topiccate_find(array('name'=>$keywords_arr),array('num'=>-1),1,count($keywords_arr));
 
    	
    }
    if($topic['file_num']>0){
    	$topic['filelistarr'] = file_find(array('id'=>explode(',',$topic['filelist'])),array('score'=>-1),1,$topic['file_num']);
    	
    }


}

function topic_count($cond = array()) {

	$n = db_count('topic', $cond);

	return $n;
}

function topic_maxid() {

	$n = db_maxid('topic', 'id');

	return $n;
}


// views + 1
function topic_inc_views($id, $n = 1) {
	
	global $conf, $db;
	$tablepre = $db->tablepre;
	if(!$conf['update_views_on']) return TRUE;
	$sqladd = !in_array($conf['cache']['type'], array('mysql', 'pdo_mysql')) ? '' : ' LOW_PRIORITY';
	$r = db_exec("UPDATE$sqladd `{$tablepre}topic` SET view=view+$n WHERE id='$id'");
	
	return $r;
}
?>