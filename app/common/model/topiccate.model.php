<?php

// ------------> 最原生的 CURD，无关联其他数据。

function topiccate__create($arr) {

	$r = db_create('topiccate', $arr);

	return $r;
}

function topiccate__update($id, $arr) {

	$r = db_update('topiccate', array('id'=>$id), $arr);

	return $r;
}

function topiccate__read($id) {

	$topiccate = db_find_one('topiccate', array('id'=>$id));

	return $topiccate;
}

function topiccate__delete($id) {

	$r = db_delete('topiccate', array('id'=>$id));

	return $r;
}

function topiccate__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$topiccatelist = db_find('topiccate', $cond, $orderby, $page, $pagesize, 'id');

	return $topiccatelist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function topiccate_create($arr) {

	$r = topiccate__create($arr);
	

	return $r;
}

function topiccate_update($id, $arr) {

	$r = topiccate__update($id, $arr);
	

	return $r;
}

function topiccate_read($id) {


		$topiccate = topiccate__read($id);
		topiccate_format($topiccate);
		return $topiccate;


}

// 关联数据删除
function topiccate_delete($id) {
	

	$r = topiccate__delete($id);


	return $r;
}
function topiccate_find_all($cond = array(), $orderby = array('sort'=>-1)) {
  
  $topiccatelist = db_find_all('topiccate',$cond, $orderby);
  if($topiccatelist) foreach ($topiccatelist as &$topiccate) topiccate_format($topiccate);
  
  return $topiccatelist;
}
function topiccate_find($cond = array(), $orderby = array('sort'=>-1), $page = 1, $pagesize = 1000) {
	
	$topiccatelist = topiccate__find($cond, $orderby, $page, $pagesize);
	if($topiccatelist) foreach ($topiccatelist as &$topiccate) topiccate_format($topiccate);
	
	return $topiccatelist;
}

// ------------> 其他方法

function topiccate_format(&$topiccate) {
	global $conf;
	if(empty($topiccate)) return;
    $topiccate['description_fmt'] = htmlspecialchars_decode($topiccate['description']);
    if(!empty($topiccate['gradeid'])){
      $topiccate['gradeid_fmt'] = explode(',',$topiccate['gradeid']);
      $topiccate['gradeid_info'] = db_find_all('usergrade',array('id'=>$topiccate['gradeid_fmt']),'','id','id,name');
     
    }else{
      $topiccate['gradeid_fmt'] = 0;
    }
    
    $topiccate['create_time_fmt'] = humandate($topiccate['create_time']);

}

function topiccate_count($cond = array()) {

	$n = db_count('topiccate', $cond);

	return $n;
}

function topiccate_maxid() {

	$n = db_maxid('topiccate', 'id');

	return $n;
}


function topiccate_add_from_keywords($keywords, $did, $method = 'add',$type=1)
{
    $tags_arr = explode(',', $keywords);

    if ($method == 'add') {
       $add_tags = $tags_arr;
    } else {
        
        $tid_arr = db_find_all('tagsandother', array('type' => $type, 'did' => $did), array('id' => '-1'), '', 'name');
        $tid_arr = array_map(function ($key) {return $key['name'];}, $tid_arr);

        $add_tags = array_diff($tags_arr,$tid_arr);

        $remove_tags = array_diff($tid_arr,$tags_arr);
        sort($remove_tags);
   }
if($type==1){
$type_inc = 'topic_num';
}elseif($type==2){
$type_inc = 'doc_num';
}else{
$type_inc = 'user_num';
}
    foreach ($add_tags as $key => $value) {
          
                $info = topiccate_read_by_name($value);
               
                $arr = array(
                    'type' => $type,
                    'tid'  => $info['id'],
                    'name' => $value,
                    'did'  => $did,
                );
                db_create('tagsandother', $arr);
                db_update('topiccate', array('id' => $info['id']), array($type_inc.'+' => 1,'num+'=>1));
              if($type==1||$type==2){
                $focususers = db_find_column('usersandother',array('type'=>2,'did'=>$info['id']),'uid');//得到所有关注该话题的用户
                if(!empty($focususers)){

$subject = '你关注的话题有了新内容&nbsp;&nbsp;<a href="'.r_url('tags-'.$info['id']).'">直达话题</a>';



                	foreach ($focususers as $key => $value) {
                		send_message($value,$subject,$subject,'new_content');
           

                    

                }
                }

}
      }

      if($method=='edit'&&!empty($remove_tags)){
      	
	db_update('topiccate', array('name'=>$remove_tags), array('num-' => 1,$type_inc.'-'=>1));
	db_delete('tagsandother',array('name'=>$remove_tags,'type'=>$type,'did'=>$did));
      }

}

function topiccate_read_by_name($name) {


	$topiccate = db_find_one('topiccate', array('name'=>$name));
	

	return $topiccate;
}

?>