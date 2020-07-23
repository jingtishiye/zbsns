<?php

!defined('DEBUG') AND exit('Access Denied.');

$hot_topiclist_6 = topic_find(array('status' => 1,'type'=>2), array('view'=>-1), 1, 6);

if($action == 'list') {
$hot_tags = topiccate_find(array('status' => 1), array('num'=>-1), 1, 6);
$type = param(3,1);
$page = param('page',1);

$where = array('status' => 1,'type'=>2);
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
$pagination = pagination(r_url('thread-list-'.$type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);



include $conf['module_view_path'].'thread-list.html';

}elseif($action == 'addcomment'){
$uid<=0 AND message(-1, '请先登录');
if(!in_array(3,$userqx['quanxian'])){
             message(-1, '无权限发布评论');
        }
 if($userqx['quanxian_limit_pinglun']>0){
    $max_create_time = db_maxid('comment','create_time',array('uid'=>$uid,'type'=>1));
    if($time-$max_create_time<$userqx['quanxian_limit_pinglun']){
      message(-1, '两次评论发布的限制时间还有'.humanmiao($userqx['quanxian_limit_pinglun']-$time+$max_create_time));
    }
  }

$content = param('content');//后期添加表情等需要进行处理。
$clearcontent = clearHtml(htmlspecialchars_decode($content));
if(empty($content)){
    message(-1, '内容为空');
}
$token = param('token');

if(!empty($_SESSION['thread_con']['token'])&&$token==$_SESSION['thread_con']['token']){

unset($_SESSION['thread_con']['token']);
}else{
message(-1, '请不要重复提交');
}
$fid = param('topic_id');
 $topicinfo = topic_read($fid);
preg_match_all('/@(.*?):/', $content, $matches);
if($matches[1]){
    foreach ($matches[1] as $key => $value) {
        $userinfo = user_read_by_nickname($value);
        if($userinfo){
          $content =  str_replace('@'.$value.':','<a href="'.r_url('user-'.$userinfo['id']).'">@'.$value.':</a>',$content);
          //给每个用户发送消息，有人@ta
           send_sys_message($userinfo['id'],'有人在帖子《<a href="'.r_url('thread-'.$fid).'">'.$topicinfo['title'].'</a>》评论中提到了你');
        }else{
          $content =  str_replace('@'.$value.':','',$content);
        }

    }
}


$pid = param('pid');
$content = remove_xss($content);
$arr = array(
    'uid'=>$uid,
    'fid'=>$fid,
    'pid'=>$pid,
    'create_time'=>$time,
    'type'=>1,
    'content'=>$content

);

$result = db_create('comment', $arr);
 if ($result) {
            if($pid>0){
               db_update('comment',array('id'=>$pid),array('reply+'=>1)); 

            }else{

               
             $subject = '有人评论了你的帖子《<a href="'.r_url('thread-'.$fid).'">'.$topicinfo['title'].'</a>》';


send_message($topicinfo['uid'],$subject,$subject,'new_comment_content');


                
             find_content_img($content,7,$result);   


               db_update('topic',array('id'=>$fid),array('reply+'=>1)); 
            }
            point_rule('commentadd',$uid);
            message(0, '评论成功');
        } else {
            message(-1, '评论失败');
        }

}elseif($action == 'create') {

	user_login_check();
    
    if(!in_array(1,$userqx['quanxian'])){
             message(-1, '无权限发布帖子');
        }
if($userqx['quanxian_limit_topic']>0){
    $max_create_time = db_maxid('topic','create_time',array('uid'=>$uid));
    if($time-$max_create_time<$userqx['quanxian_limit_topic']){
      message(-1, '两次帖子发布的限制时间还有'.humanmiao($userqx['quanxian_limit_topic']-$time+$max_create_time));
    }
  }



	if($method == 'GET') {
	    $token = xn_safe_key();
        $_SESSION['thread_create']['token'] = $token;
		$header['title'] = '帖子发布';
        $input['free'] = form_radio('free', array('0'=>'免费','1'=>'付费','2'=>'部分内容付费'),0);
       
		    if($conf['allow_cate_show']>0){
       $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,$conf['allow_cate_show']);
    }else{
       $taglist = topiccate_find_all(array('status'=>1),array('sort'=>'-1'));
    }  

        $filetypes         = include DATA_PATH . 'config/attach.conf.php';


		include $conf['module_view_path'].'thread-create.html';
		
	} else {
		
		$type=2;//帖子类型

$dataarr = param_post('');
$filelist = param('filelist',array());
$scorelist = param('filescore',array());
unset($dataarr['filelist']);
unset($dataarr['filescore']);



           if($dataarr['score']<0){
            $dataarr['score']=0;
            $dataarr['free']=0;
            }
        $checkarr = array(
            'title'   => array(array('empty', '标题为空'),array('length', '标题在6到255个字符之间', array(6, 255))),
            'content'   => array(array('empty', '内容为空')),
        );

        $r = wi_check_field('topic', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        if(!empty($_SESSION['thread_create']['token'])&&$r[1]['token']==$_SESSION['thread_create']['token']){

              unset($_SESSION['thread_create']['token']);
        }else{
              message(-1, '请不要重复提交');
        }
        unset($r[1]['token']);

        $r[1]['create_time'] = $time;
        $r[1]['uid'] = $uid;
        $r[1]['type'] = $type;
        $r[1]['description'] = wi_getSummary($r[1]['content']);
        
       
       
        $result = topic_create($r[1]);
        if ($result) {
            if(!empty($r[1]['keywords'])){
        	   topiccate_add_from_keywords($r[1]['keywords'],$result,'add',1); 
            }
            user_extend_update($uid,array('topic_num+'=>1));

            thread_attach_post($result,$filelist,$scorelist);

            $focususers = db_find_column('usersandother',array('type'=>0,'did'=>$uid),'uid');//得到所有关注该用户的用户
                if(!empty($focususers)){

$subject = '你关注的用户发布了帖子《<a href="'.r_url('thread-'.$result).'">'.$r[1]['title'].'</a>》';


                    
                    foreach ($focususers as $key => $value) {

send_message($value,$subject,$subject,'new_user_content');


                }
                }

            point_rule('topicadd',$uid);
            message(0, '添加成功',array('url'=>r_url('thread-list')));
        } else {
            message(-1, '添加失败');
        }
	}
	

}elseif($action == 'edit') {

	user_login_check();
    $id   = param('id');
    $info = topic_read($id);

   if($uid!=$info['uid']){
   	 message(-1, '无权编辑该帖子');
   }


	if($method == 'GET') {
    
$token = xn_safe_key();
        $_SESSION['thread_edit']['token'] = $token;
		$header['title'] = '帖子编辑-'.$info['title'];
$input['free'] = form_radio('free', array('0'=>'免费','1'=>'付费','2'=>'部分内容付费'),$info['free']);
		    if($conf['allow_cate_show']>0){
       $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,$conf['allow_cate_show']);
    }else{
       $taglist = topiccate_find_all(array('status'=>1),array('sort'=>'-1'));
    }  
$filetypes         = include DATA_PATH . 'config/attach.conf.php';

		include $conf['module_view_path'].'thread-edit.html';
		
	} else {

$dataarr = param_post('');
$filelist = param('filelist',array());
$scorelist = param('filescore',array());
unset($dataarr['filelist']);
unset($dataarr['filescore']);



        if (!empty($dataarr)) {
           if($dataarr['score']<0){
            $dataarr['score']=0;
            $dataarr['free']=0;
            }
           $checkarr = array(
            'title'   => array(array('empty', '标题为空'),array('length', '标题在6到255个字符之间', array(6, 255))),
            'content'   => array(array('empty', '内容为空')),
           );

            $r = wi_check_field('topic', $dataarr, $checkarr, 'edit','id',$info);

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }
                if(!empty($_SESSION['thread_edit']['token'])&&$r[1]['token']==$_SESSION['thread_edit']['token']){

              unset($_SESSION['thread_edit']['token']);
        }else{
              message(-1, '请不要重复提交');
        }
        unset($r[1]['token']);
            $r[1]['update_time'] = $time;
            if(!empty($r[1]['content'])){
            	$r[1]['description'] = wi_getSummary($r[1]['content']);
            }
            
           
            $result = topic_update($id, $r[1]);
            if ($result) {
            if(!empty($r[1]['keywords'])){
        	     topiccate_add_from_keywords($r[1]['keywords'],$id,'edit',1); 
            }


                 thread_attach_post($id,$filelist,$scorelist,'edit');


                message(0, '编辑成功',array('url'=>r_url('thread-list')));
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }
	}
	
// 帖子详情 | post detail
} else {
	
	
	$id = param(2, 0);
	
	$token = xn_safe_key();
    $_SESSION['thread_con']['token'] = $token;
	
	$thread = topic_read($id);
   
	empty($thread) AND message(-1, '帖子不存在');
	topic_inc_views($id,1);
    $ta_topiclist_6 = topic_find(array('status' => 1,'type'=>2,'uid'=>$thread['uid']), array('view'=>-1), 1, 6);
    
	
	$header['title'] = $thread['title'].'-'.$conf['sitename']; 
	$header['keywords'] = ''; 
	$header['description'] = $thread['description'];


$pointnote['type']=6;
$pointnote['itemid']=$id;
$pointnote['inctype']='+';
$pointnote['scoretype']='point';




$sumscore = db_sum('point_note','score',$pointnote);
$point_count = db_count('point_note',$pointnote);
$pointnotelist = db_find('point_note',$pointnote,array('score'=>-1),1,10);




if($thread['free']>0){

$has_buy = has_buy($uid,$id,3);
if($has_buy||$uid==$thread['uid']){
    $thread['free']=0;
$newcontent = htmlspecialchars_decode($thread['content']); 
}else{
if($thread['free']==1){
    //全部隐藏
    $newcontent = '';
}else{



$content = htmlspecialchars_decode($thread['content']);

preg_match("/<hr(.*)class=\"hidecontent\"(.*)>/i",$content,$r);
if(empty($r)){
$newcontent = $content; 
$thread['free'] = 0;
}else{
   $newcontent = str_replace($r[0],'',$content); 
}


}
}


}else{
  $newcontent = htmlspecialchars_decode($thread['content']);  
}


$page = param('page',1);
$pagenum    = $conf['pagesize'];
$comment_list = comment_find(array('status' => 1,'type'=>1,'fid'=>$thread['id'],'pid'=>0), array('create_time'=>1), $page, $pagenum);

$totalnum   = comment_count(array('status' => 1,'type'=>1,'fid'=>$thread['id'],'pid'=>0));
$pagination = pagination(r_url('thread-'.$id, array('page' => 'pagenum')), $totalnum, $page, $pagenum);




    include $conf['module_view_path'].'thread-con.html';
}


?>