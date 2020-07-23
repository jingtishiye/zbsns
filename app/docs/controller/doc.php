<?php

!defined('DEBUG') AND exit('Access Denied.');
include BASEPHP_FUNPATH . 'xn_zip.func.php';


$hot_doclist_6 = doc_find(array('status' => 1), array('view'=>-1), 1, 6);

if($action == 'list') {
$hot_tags = topiccate_find(array('status' => 1), array('num'=>-1), 1, 6);
$type = param(3,1);
$page = param('page',1);

$where = array('status' => 1);
if($type==1){
$order = array('settop'=>-1,'id'=>-1);
}elseif($type==2){
$order = array('settop'=>-1,'choice'=>-1);
}else{
$order = array('settop'=>-1,'view'=>-1);
}
$pagenum    = $conf['pagesize'];
$docslist   = doc_find($where, $order, $page, $pagenum);

$totalnum   = doc_count($where);
$pagination = pagination(r_url('doc-list-'.$type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);


include $conf['module_view_path'].'doc-list.html';
}elseif($action == 'status'){

$data['sha1'] = param('hash');

db_update('doccon',array('sha1'=>$data['sha1']),array('status'=>2));




}elseif($action == 'upload'){
   
$num = param('num');



$data['file'] = $_FILES['file'];


$data['page'] = intval(param('page'));//file_get_contents("php://input");
$data['sha1'] = param('sha1');
$data['token'] = param('token');
$data['time'] = param('time');
$time1 = $data['time'];
$token = md5($conf['auth_key'].$conf['appid'].$time1);
if($token!=$data['token']||$time-intval($time1)>60){
    //过时了
}else{
$tmpanme = $data['file']['name'];
$tmpurl = $conf['upload_url'] . 'docview/' . $tmpanme;
$replace['online_trans_num'] = $num-1;

file_replace_var(DATA_PATH.'config/conf.default.php', $replace);
if (!file_exists($tmpurl)) {
if (!move_uploaded_file($data['file']['tmp_name'], $tmpurl)) {
       echo xn_json_encode(array('code'=>0,'message'=>'创建文件失败'));
       return;
}
}

   $info = db_find_one('doccon',array('sha1'=>$data['sha1']));
       $fileinfo = db_find_one('file',array('id'=>$info['fileid']));
       $name = str_replace('.'.$fileinfo['ext'],'',$fileinfo['savename']);
       xn_unzip($conf['upload_path'].'docview/'.$name.'.zip', $conf['upload_path'].'docview/'.$name.'/');
       $pagenum = 0;
                    
                    $getimgsize = getimagesize($conf['upload_path'].'docview/'.$name.'/'.$name.'.png');
                    $imgwidth = $getimgsize[0];
                    $imgheight = $getimgsize[1];
                    image_clip($conf['upload_path'].'docview/'.$name.'/'.$name.'.png', $conf['upload_path'].'docview/'.$name.'/'.$name.'_preview.png', 0,0,$imgwidth,$imgheight-20);
                    foreach(glob($conf['upload_path'].'docview/'.$name.'/*.svg') as $filename)
                    {
                       
                        
                       $newfilename = str_replace($conf['upload_path'].'docview/'.$name.'/',"",$filename);
                       if($conf['sy_type']>0){
                        if($conf['sy_type']==1&&$conf['shuiyin']!=''){
                            write_svg($conf['shuiyin'],$filename,$conf['upload_path'].'docview/'.$name.'/','sy-'.$newfilename,'50%','50%',false);
                        }else{
                            write_svg($conf['web_url'].'/public/common/images/water.png',$filename,$conf['upload_path'].'docview/'.$name.'/','sy-'.$newfilename,'50%','50%',true);
                        }
                          
                        }
                        

                        $pagenum++;


                     }

         if(($info['showpage']==0&&$pagenum<$data['page'])||$info['showpage']>$pagenum){
                            $showpage = $pagenum;
             }else{
              $showpage = $info['showpage'];
             }






       db_update('doccon',array('sha1'=>$data['sha1']),array('pageid'=>$data['page'],'showpage'=>$showpage,'status'=>1));
       
echo xn_json_encode(array('code'=>1,'message'=>'回传文件成功'));
return;

}
}elseif($action == 'transvg'){
 $id = param('id');
 $info = db_find_one('doccon',array('id'=>$id));
       $fileinfo = db_find_one('file',array('id'=>$info['fileid']));
       $name = str_replace('.'.$fileinfo['ext'],'',$fileinfo['savename']);
 
if(is_file($conf['upload_path'].'docview/'.$name.'/'.$name.'_preview.png')){
   
}else{
$pagenum = 0;
                    
                    $getimgsize = getimagesize($conf['upload_path'].'docview/'.$name.'/'.$name.'.png');
                    $imgwidth = $getimgsize[0];
                    $imgheight = $getimgsize[1];
                    image_clip($conf['upload_path'].'docview/'.$name.'/'.$name.'.png', $conf['upload_path'].'docview/'.$name.'/'.$name.'_preview.png', 0,0,$imgwidth,$imgheight-20);
                    foreach(glob($conf['upload_path'].'docview/'.$name.'/*.svg') as $filename)
                    {
                       
                        
                       $newfilename = str_replace($conf['upload_path'].'docview/'.$name.'/',"",$filename);
                       if($conf['sy_type']>0){
                        if($conf['sy_type']==1&&$conf['shuiyin']!=''){
                            write_svg($conf['shuiyin'],$filename,$conf['upload_path'].'docview/'.$name.'/','sy-'.$newfilename,'50%','50%',false);
                        }else{
                            write_svg($conf['web_url'].'/public/common/images/water.png',$filename,$conf['upload_path'].'docview/'.$name.'/','sy-'.$newfilename,'50%','50%',true);
                        }
                          
                        }
                        

                        $pagenum++;


                     }

         if(($info['showpage']==0&&$pagenum<$info['pageid'])||$info['showpage']>$pagenum){
                            $showpage = $pagenum;
             }else{
              $showpage = $info['showpage'];
             }






db_update('doccon',array('id'=>$id),array('showpage'=>$showpage,'status'=>1));

}

echo xn_json_encode(array('code'=>1,'message'=>'变更ID为'.$id.'的文档状态成功'));
return;


}elseif($action == 'addcomment'){
user_login_check();
if(!in_array(3,$userqx['quanxian'])){
             message(-1, '无权限发布评论');
        }


 if($userqx['quanxian_limit_pinglun']>0){
    $max_create_time = db_maxid('comment','create_time',array('uid'=>$uid,'type'=>2));
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

if(!empty($_SESSION['doc_con']['token'])&&$token==$_SESSION['doc_con']['token']){

unset($_SESSION['doc_con']['token']);
}else{
message(-1, '请不要重复提交');
}
$fid = param('topic_id');
$topicinfo = doc_read($fid);

preg_match_all('/@(.*?):/', $content, $matches);
if($matches[1]){
    foreach ($matches[1] as $key => $value) {
        $userinfo = user_read_by_nickname($value);
        if($userinfo){
          $content =  str_replace('@'.$value.':','<a href="'.r_url('user-'.$userinfo['id']).'">@'.$value.':</a>',$content);
          //给每个用户发送消息，有人@ta
          send_sys_message($userinfo['id'],'有人在文档《<a href="'.r_url('doc-'.$fid).'">'.$topicinfo['title'].'</a>》评论中提到了你');
          
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
    'type'=>2,
    'content'=>$content

);
$result = db_create('comment', $arr);
 if ($result) {
            if($pid>0){
               db_update('comment',array('id'=>$pid),array('reply+'=>1)); 

            }else{
                 
$subject = '有人评论了你的文档《<a href="'.r_url('doc-'.$fid).'">'.$topicinfo['title'].'</a>》';


send_message($topicinfo['uid'],$subject,$subject,'new_comment_content');




find_content_img($content,7,$result);

               db_update('doccon',array('id'=>$fid),array('reply+'=>1)); 
            }
            point_rule('commentadd',$uid);
            message(0, '评论成功');
        } else {
            message(-1, '评论失败');
        }


}elseif($action == 'create') {

	user_login_check();
if(!in_array(2,$userqx['quanxian'])){
             message(-1, '无权限发布文档');
        }
  if($userqx['quanxian_limit_doc']>0){
    $max_create_time = db_maxid('doccon','create_time',array('uid'=>$uid));
    if($time-$max_create_time<$userqx['quanxian_limit_doc']){
      message(-1, '两次文档发布的限制时间还有'.humanmiao($userqx['quanxian_limit_doc']-$time+$max_create_time));
    }
  }


	if($method == 'GET') {
	    $token = xn_safe_key();
      $_SESSION['doc_create']['token'] = $token;
		  $header['title'] = '文档发布';

    if($conf['allow_cate_show']>0){
       $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,$conf['allow_cate_show']);
    }else{
       $taglist = topiccate_find_all(array('status'=>1),array('sort'=>'-1'));
    }  
		




$filetypes         = include DATA_PATH . 'config/attach.conf.php';

		include $conf['module_view_path'].'doc-create.html';
		
	} else {
		
		
		$dataarr = param_post('');




if($dataarr['docid']<=0||empty($dataarr['docid'])){
message(-1, '请添加文档');
}
$look_info = db_find_one('doccon',array('sha1'=>$dataarr['sha1']));
if($look_info){
  message(-1, '该文档已经添加过了',array('url'=>r_url('doc-'.$look_info['id'])));  
}
        $checkarr = array(
            'title'   => array(array('empty', '标题为空'),array('length', '标题在6到100个字符之间', array(6, 100)))

            
        );

        $r = wi_check_field('doccon', $dataarr, $checkarr, 'add');
        
        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        if(!empty($_SESSION['doc_create']['token'])&&$r[1]['token']==$_SESSION['doc_create']['token']){

              unset($_SESSION['doc_create']['token']);
        }else{
              message(-1, '请不要重复提交');
        }
        unset($r[1]['token']);
        

        $r[1]['create_time'] = $time;
        $r[1]['uid'] = $uid;
        $r[1]['score'] = intval($r[1]['score'])<0?0:$r[1]['score'];
        
        $r[1]['showpage'] = intval($r[1]['showpage'])<0?0:$r[1]['showpage'];

        $r[1]['fileid'] = $dataarr['docid'];
        unset($r[1]['docid']);
        $r[1]['ctype'] = $conf['online_trans'];
       // $r[1]['description'] = wi_getSummary($r[1]['content']);
       
        
        $result = doc_create($r[1]);
        if ($result) {
            if(!empty($r[1]['keywords'])){
        	      topiccate_add_from_keywords($r[1]['keywords'],$result,'add',2); 
            }
            user_extend_update($uid,array('doc_num+'=>1));
         
            doc_file_upload($r[1]['fileid'],$result);
          
            
                   $focususers = db_find_column('usersandother',array('type'=>0,'did'=>$uid),'uid');//得到所有关注该用户的用户
                if(!empty($focususers)){
                  
$subject = '你关注的用户分享了文档《<a href="'.r_url('doc-'.$result).'">'.$r[1]['title'].'</a>》';



                    foreach ($focususers as $key => $value) {




send_message($value,$subject,$subject,'new_user_content');

    

                    

                }
                }

                point_rule('docadd',$uid);
            message(0, '添加成功',array('url'=>r_url('doc-list')));
        } else {
            message(-1, '添加失败');
         }
	}
	

}elseif($action == 'edit') {

	user_login_check();
    $id   = param('id');
    $info = doc_read($id);

   if($uid!=$info['uid']){
   	 message(-1, '无权编辑该文档');
   }


	if($method == 'GET') {
    
$token = xn_safe_key();
        $_SESSION['doc_edit']['token'] = $token;
		$header['title'] = '文档编辑-'.$info['title'];

	
        if($conf['allow_cate_show']>0){
       $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,$conf['allow_cate_show']);
    }else{
       $taglist = topiccate_find_all(array('status'=>1),array('sort'=>'-1'));
    }  


$filetypes         = include DATA_PATH . 'config/attach.conf.php';

		include $conf['module_view_path'].'doc-edit.html';
		
	} else {
    $dataarr = param_post('');


	
		
         $dataarr['fileid'] = $dataarr['docid'];
         if($info['fileid']!=$dataarr['fileid']){
            $look_info = db_find_one('doccon',array('sha1'=>$dataarr['sha1']));
               if($look_info){
                   message(-1, '该文档已经添加过了',array('url'=>r_url('doc-'.$look_info['id'])));  
                } 
         }
  
         unset($dataarr['docid']);
        if (!empty($dataarr)) {

           $checkarr = array(
            'title'   => array(array('empty', '标题为空'),array('length', '标题在6到100个字符之间', array(6, 100))),
            
           );

            $r = wi_check_field('doccon', $dataarr, $checkarr, 'edit','id',$info);
             

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }
            if(!empty($_SESSION['doc_edit']['token'])&&$r[1]['token']==$_SESSION['doc_edit']['token']){

              unset($_SESSION['doc_edit']['token']);
            }else{
              message(-1, '请不要重复提交');
            }
            unset($r[1]['token']);
            $r[1]['update_time'] = $time;
            //$r[1]['status'] = 0;
            
            
            $result = doc_update($id, $r[1]);
            if ($result) {
            if(!empty($r[1]['keywords'])){
        	     topiccate_add_from_keywords($r[1]['keywords'],$id,'edit',2); 
            }
            if($r[1]['fileid']!=$info['fileid']){
           
                doc_file_upload($r[1]['fileid'],$id,$info['fileid']);

            }
                 
                message(0, '编辑成功',array('url'=>r_url('doc-list')));
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }
	}
}elseif($action == 'pingfen') {

$data['itemid'] = param('id');
$data['score'] = param('score');
$data['uid'] = $uid;



$info = db_find_one('raty_user',$data);
if($info){
   message(-1, '你已经对该文档进行过评分');
}else{
  db_create('raty_user',$data);
  $n = db_sum('raty_user','score',array('itemid'=>$data['itemid']));
  $m = db_count('raty_user',array('itemid'=>$data['itemid']));
  $format_num = sprintf("%.1f",$n/$m);
  doc_update($data['itemid'],array('raty'=>$format_num));


  message(0, '感谢您的评分');
}



// 详情 | post detail
} else {
	


	$id = param(2, 0);
	
	$token = xn_safe_key();
    $_SESSION['doc_con']['token'] = $token;
	
	$thread = doc_read($id);
	empty($thread) AND message(-1, '文档不存在');
	doc_inc_views($id,1);
  $name = str_replace('.'.$thread['fileinfo']['ext'],'',$thread['fileinfo']['savename']);
            $dir = $conf['upload_path'].'docview/'.$name.'/';


            if(!is_dir($dir)){
                
               $thread['can_view']=0;
            }else{
              $thread['can_view']=1;
            }
    $ta_topiclist_6 = doc_find(array('status' => 1,'uid'=>$thread['uid']), array('view'=>-1), 1, 6);

$ratywhere['itemid'] = $id;
$ratywhere['uid'] = $uid;
$ratyinfo = db_find_one('raty_user',$ratywhere);
if($ratyinfo){
   $raty['has']=1;
   $raty['score'] = $ratyinfo['score'];
}else{
   $raty['has']=0;
   $raty['score'] = 0;
}




$has_buy = has_buy($uid,$thread['id'],2);
if($has_buy||$uid==$thread['uid']||$thread['score']==0){
  $free = 1;
}else{
  $free = 0;
}

	
	$header['title'] = $thread['title'].'-'.$conf['sitename']; 
	$header['keywords'] = ''; 
	$header['description'] = $thread['description'];


$page = param('page',1);
$pagenum    = $conf['pagesize'];
$comment_list = comment_find(array('status' => 1,'type'=>2,'fid'=>$thread['id'],'pid'=>0), array('create_time'=>1), $page, $pagenum);

$totalnum   = comment_count(array('status' => 1,'type'=>2,'fid'=>$thread['id'],'pid'=>0));
$pagination = pagination(r_url('doc-'.$id, array('page' => 'pagenum')), $totalnum, $page, $pagenum);




    include $conf['module_view_path'].'doc-con.html';
}


?>