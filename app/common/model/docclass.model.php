<?php
$docclasshtml;
$docclassselect = array(0=>'无上级');

function docclass__create($arr) {
	
	$r = db_create('docclass', $arr);
	
	return $r;
}

function docclass__update($id, $arr) {
	
	$r = db_update('docclass', array('id'=>$id), $arr);
	
	return $r;
}

function docclass__read($id) {
	
	$docclass = db_find_one('docclass', array('id'=>$id));
	
	return $docclass;
}

function docclass__delete($id) {
	
	$r = db_delete('docclass', array('id'=>$id));
	
	return $r;
}

function docclass__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$docclasslist = db_find('docclass', $cond, $orderby, $page, $pagesize,'id');

	return $docclasslist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function docclass_create($arr) {

	$r = docclass__create($arr);

	return $r;
}

function docclass_update($id, $arr) {

	$r = docclass__update($id, $arr);
	return $r;
}

function docclass_read($id) {


		$docclass = docclass__read($id);
		docclass_format($docclass);
		return $docclass;


}



function docclass_find($cond = array(), $orderby = array('sort'=>-1), $page = 1, $pagesize = 1000) {

	$docclasslist = docclass__find($cond, $orderby, $page, $pagesize);
	if($docclasslist) foreach ($docclasslist as &$docclass) docclass_format($docclass);

	return $docclasslist;
}

// ------------> 其他方法
function docclass_format(&$docclass) {
    global $conf;
	if(empty($docclass)) return;
	$docclass['description_fmt'] = htmlspecialchars_decode($docclass['description']);
    if(!empty($docclass['gradeid'])){
      $docclass['gradeid_fmt'] = explode(',',$docclass['gradeid']);
      $docclass['gradeid_info'] = db_find_all('usergrade',array('id'=>$docclass['gradeid_fmt']),'','id','id,name');
     
    }else{
      $docclass['gradeid_fmt'] = 0;
    }
    
    $docclass['create_time_fmt'] = humandate($docclass['create_time']);


}
function docclass_getselect($docclassarr,$level=1){

global $docclassselect;
   $str = '|-';
   for($i=0;$i<$level;$i++){
   	$str = '&nbsp;&nbsp;'.$str;
   }
   
   foreach ($docclassarr as $key => $value) {
	
         
		 $docclassselect[$value['id']] = $str.$value['name'];
         
	     $count = docclass_count(array('pid'=>$value['id'],'status'=>array('>='=>0)));


	if($count>0){
		$child = docclass_find(array('pid'=>$value['id'],'status'=>array('>='=>0)),'','',$count);
		$level++;
		docclass_getselect($child,$level);
        $level--;

	}else{
		
		
		
		continue;
	}
	
}

return $docclassselect;
}
function docclass_count($cond = array()) {

	$n = db_count('docclass', $cond);

	return $n;
}

function docclass_maxid() {

	$n = db_maxid('docclass', 'id');

	return $n;
}






?>