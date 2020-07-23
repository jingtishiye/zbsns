<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') AND exit('Access Denied.');


if(empty($action)||$action == 'list') {
 
        
	include ADMIN_PATH.'view/trash_list.html';
	
} elseif($action == 'datalist') {

    $name = param('name');
    $show_key = $conf['trashlist'][$name][1];
    $table_key = $conf['trashlist'][$name][2];
    $page       = param('page', 0);
    $where = array('status' => -1);


    $pagenum    = $conf['pagesize'];
    $trash_datalist = db_find($name,$where, '', $page, $pagenum);
    $totalnum   = db_count($name,$where);
    $pagination = pagination(r_url('trash-datalist', array('name'=>$name,'page' => 'pagenum')), $totalnum, $page, $pagenum);
	include ADMIN_PATH.'view/trash_data_list.html';
}elseif($action == 'restoredata'){
	$name = param('name');
	$table_key = $conf['trashlist'][$name][2];
	$id_val = param($table_key);
    $r = db_update($name,array($table_key=>$id_val),['status'=>1]);
    if ($r) {
            message(0, '恢复成功');
        } else {
            message(-1, '恢复失败');
        }
}elseif($action == 'trashdatadel'){
	$name = param('name');
	$table_key = $conf['trashlist'][$name][2];
	$id_val = param($table_key);
    if($name=='topic'){
    //帖子删除时要把个人的帖子数-1
    //要通知用户帖子被删除
    //所有收藏该帖子的记录全部删除
    $info = topic_read($id_val);
    send_sys_message($info['uid'],'你的帖子《'.$info['title'].'》被删除');
    user_extend_update($info['uid'],array('topic_num-'=>1));
    db_delete('comment',array('type'=>1,'fid'=>$info['id']));///删除所有收藏的
    db_delete('usersandother',array('type'=>1,'did'=>$info['id']));///删除所有收藏的
    db_delete('jubao',array('type'=>1,'fid'=>$info['id']));///删除所有收藏的
    $tagsinfo = db_find_all('tagsandother',array('type'=>1,'did'=>$info['id']));
    point_rule('topicdelete',$info['uid']);
    if($tagsinfo){
        foreach ($tagsinfo as $key => $value) {
           
            topiccate_update($value['tid'],array('topic_num-'=>1));
        }
    }

    $r = topic_delete($id_val);
    }elseif($name=='doccon'){
    $info = doc_read($id_val);
    send_sys_message($info['uid'],'你的文档《'.$info['title'].'》被删除');
    user_extend_update($info['uid'],array('doc_num-'=>1));
    db_delete('usersandother',array('type'=>3,'did'=>$info['id']));///删除所有收藏的
    db_delete('comment',array('type'=>2,'fid'=>$info['id']));///删除所有收藏的
    db_delete('jubao',array('type'=>2,'fid'=>$info['id']));///删除所有收藏的
    $tagsinfo = db_find_all('tagsandother',array('type'=>2,'did'=>$info['id']));
    point_rule('docdelete',$info['uid']);
    if($tagsinfo){
        foreach ($tagsinfo as $key => $value) {
           
            topiccate_update($value['tid'],array('doc_num-'=>1));
        }
    }

    $r = doc_delete($id_val);
    }elseif($name=='user'){
      db_update('topic',array('uid'=>$id_val),array('status'=>-1));
      db_update('doc',array('uid'=>$id_val),array('status'=>-1));
      db_delete('comment',array('uid'=>$id_val));///删除评论
      db_update('topiccate',array('uid'=>$id_val),array('status'=>-1));
      db_delete('user_extend',array('uid'=>$id_val));///删除所有收藏的
      db_delete('rzuser',array('uid'=>$id_val));///删除所有收藏的
      db_delete('usersandother',array('type'=>0,'did'=>$id_val));///删除所有收藏的
      db_delete('jubao',array('type'=>0,'fid'=>$id_val));///删除所有收藏的
      $tagsinfo = db_find_all('tagsandother',array('type'=>0,'did'=>$id_val));
    if($tagsinfo){
        foreach ($tagsinfo as $key => $value) {
           
            topiccate_update($value['tid'],array('user_num-'=>1));
        }
    }
    $r = user_delete($id_val);
    }elseif($name=='comment'){
      $info = db_find_one('comment',array('id'=>$id_val));
      
      if($info['type']==1){
         if($info['pid']==0){
            db_update('topic',array('id'=>$info['fid']),array('reply-'=>1));
        }else{
            db_update('comment',array('id'=>$info['pid']),array('reply-'=>1));
        }
         
        
      }else{
         if($info['pid']==0){
            db_update('doccon',array('id'=>$info['fid']),array('reply-'=>1));
        }else{
            db_update('comment',array('id'=>$info['pid']),array('reply-'=>1));
        }

     }
 
    $r = db_delete('comment',array('id'=>$id_val));  
    }elseif($name=='topiccate'){
      
         db_delete('tagsandother',array('tid'=>$id_val));
        $r = db_delete('topiccate',array('id'=>$id_val));  


    }else{
      $r = db_delete($name,array($table_key=>$id_val));  
    }
    
    if ($r) {





            message(0, '删除成功');
        } else {
            message(-1, '删除失败');
        }
}


?>
