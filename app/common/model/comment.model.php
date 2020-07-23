<?php

// ------------> 最原生的 CURD，无关联其他数据。

function comment__create($arr) {

	$r = db_create('comment', $arr);

	return $r;
}

function comment__update($id, $arr) {

	$r = db_update('comment', array('id'=>$id), $arr);

	return $r;
}

function comment__read($id) {

	$comment = db_find_one('comment', array('id'=>$id));
    comment_format($comment);
	return $comment;
}

function comment__delete($id) {

	$r = db_delete('comment', array('id'=>$id));

	return $r;
}

function comment__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$commentlist = db_find('comment', $cond, $orderby, $page, $pagesize, 'id');

	return $commentlist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。


function comment_find($cond = array(), $orderby = array('id'=>-1), $page = 1, $pagesize = 1000) {
	
	$commentlist = comment__find($cond, $orderby, $page, $pagesize);
	if($commentlist) foreach ($commentlist as &$comment) comment_format($comment);
	
	return $commentlist;
}

// ------------> 其他方法

function comment_format(&$comment) {
	global $conf;
	if(empty($comment)) return;
	$child_comments = db_find_all('comment',array('pid'=>$comment['id'],'status'=>1));
	if($child_comments){
		foreach ($child_comments as $key=>$vo){
			$child_comments[$key]['userinfo'] = user_read($vo['uid']);
			 $child_comments[$key]['create_time_fmt'] = humandate($vo['create_time']);
		}
	}
	

	$comment['child'] = $child_comments;
    $comment['create_time_fmt'] = humandate($comment['create_time']);
    $comment['userinfo'] = user_read($comment['uid']);
}

function comment_count($cond = array()) {

	$n = db_count('comment', $cond);

	return $n;
}

function comment_maxid() {

	$n = db_maxid('comment', 'id');

	return $n;
}

?>