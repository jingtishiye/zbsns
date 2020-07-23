<?php

// ------------> 最原生的 CURD，无关联其他数据。

function nav__create($arr) {

	$r = db_create('nav', $arr);

	return $r;
}

function nav__update($id, $arr) {

	$r = db_update('nav', array('id'=>$id), $arr);

	return $r;
}

function nav__read($id) {

	$nav = db_find_one('nav', array('id'=>$id));

	return $nav;
}

function nav__delete($id) {

	$r = db_delete('nav', array('id'=>$id));

	return $r;
}

function nav__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$navlist = db_find('nav', $cond, $orderby, $page, $pagesize, 'id');

	return $navlist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function nav_create($arr) {

	$r = nav__create($arr);
	nav_list_cache_delete();

	return $r;
}

function nav_update($id, $arr) {

	$r = nav__update($id, $arr);
	nav_list_cache_delete();

	return $r;
}

function nav_read($id) {


		$nav = nav__read($id);
		nav_format($nav);
		return $nav;


}

// 关联数据删除
function nav_delete($id) {
	

	$r = nav__delete($id);

	nav_list_cache_delete();

	return $r;
}

function nav_find($cond = array(), $orderby = array('id'=>-1), $page = 1, $pagesize = 1000) {
	
	$navlist = nav__find($cond, $orderby, $page, $pagesize);
	if($navlist) foreach ($navlist as &$nav) nav_format($nav);
	
	return $navlist;
}

// ------------> 其他方法

function nav_format(&$nav) {
	global $conf;
	if(empty($nav)) return;
    

}

function nav_count($cond = array()) {

	$n = db_count('nav', $cond);

	return $n;
}

function nav_top(){
	global $conf, $navlist;
	$navlist = cache_get('navlist-top');
	
	
	
	if($navlist === NULL) {
		$navlist = db_find_all('nav',array('status'=>1,'pid'=>1),array('sort'=>-1));
		cache_set('navlist-top', $navlist, 60);
	}
	
	return $navlist;
}
function nav_bottom(){
	global $conf, $navlist;
	$navlist = cache_get('navlist-bottom');
	
	
	
	if($navlist === NULL) {
		$navlist = db_find_all('nav',array('status'=>1,'pid'=>2),array('sort'=>-1));
		cache_set('navlist-bottom', $navlist, 60);
	}
	
	return $navlist;
}
function nav_url($link){
	if(substr($link,0,7) == 'http://'||substr($link,0,8) == 'https://'){
        $url = $link;
    }else{
    	if(strpos($link, ',') === FALSE){
           $url = r_url($link);
    	}else{
    	   $arr = explode(',',$link);
           $url = r_url($arr[0],'',$arr[1]);
    	}
        
    }
    return $url;
}
function nav_list_cache_delete() {
	global $conf;
	static $deleted = FALSE;
	if($deleted) return;
	
	
	cache_delete('navlist-bottom');
	cache_delete('navlist-top');

	$deleted = TRUE;
	
}

?>