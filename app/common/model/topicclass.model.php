<?php
$topicclasshtml;
$topicclassselect = array(0=>'无上级');

function topicclass__create($arr) {
	
	$r = db_create('topicclass', $arr);
	
	return $r;
}

function topicclass__update($id, $arr) {
	
	$r = db_update('topicclass', array('id'=>$id), $arr);
	
	return $r;
}

function topicclass__read($id) {
	
	$topicclass = db_find_one('topicclass', array('id'=>$id));
	
	return $topicclass;
}

function topicclass__delete($id) {
	
	$r = db_delete('topicclass', array('id'=>$id));
	
	return $r;
}

function topicclass__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$topicclasslist = db_find('topicclass', $cond, $orderby, $page, $pagesize,'id');

	return $topicclasslist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function topicclass_create($arr) {

	$r = topicclass__create($arr);

	return $r;
}

function topicclass_update($id, $arr) {

	$r = topicclass__update($id, $arr);
	return $r;
}

function topicclass_read($id) {


		$topicclass = topicclass__read($id);
		topicclass_format($topicclass);
		return $topicclass;


}



function topicclass_find($cond = array(), $orderby = array('sort'=>-1), $page = 1, $pagesize = 1000) {

	$topicclasslist = topicclass__find($cond, $orderby, $page, $pagesize);
	if($topicclasslist) foreach ($topicclasslist as &$topicclass) topicclass_format($topicclass);

	return $topicclasslist;
}

// ------------> 其他方法
function topicclass_format(&$topicclass) {
    global $conf;
	if(empty($topicclass)) return;
	$topicclass['description_fmt'] = htmlspecialchars_decode($topicclass['description']);
    if(!empty($topicclass['gradeid'])){
      $topicclass['gradeid_fmt'] = explode(',',$topicclass['gradeid']);
      $topicclass['gradeid_info'] = db_find_all('usergrade',array('id'=>$topicclass['gradeid_fmt']),'','id','id,name');
     
    }else{
      $topicclass['gradeid_fmt'] = 0;
    }
    
    $topicclass['create_time_fmt'] = humandate($topicclass['create_time']);


}
function topicclass_getselect($topicclassarr,$level=1){

global $topicclassselect;
   $str = '|-';
   for($i=0;$i<$level;$i++){
   	$str = '&nbsp;&nbsp;'.$str;
   }
   
   foreach ($topicclassarr as $key => $value) {
	
         
		 $topicclassselect[$value['id']] = $str.$value['name'];
         
	     $count = topicclass_count(array('pid'=>$value['id'],'status'=>array('>='=>0)));


	if($count>0){
		$child = topicclass_find(array('pid'=>$value['id'],'status'=>array('>='=>0)),'','',$count);
		$level++;
		topicclass_getselect($child,$level);
        $level--;

	}else{
		
		
		
		continue;
	}
	
}

return $topicclassselect;
}
function topicclass_count($cond = array()) {

	$n = db_count('topicclass', $cond);

	return $n;
}

function topicclass_maxid() {

	$n = db_maxid('topicclass', 'id');

	return $n;
}






?>