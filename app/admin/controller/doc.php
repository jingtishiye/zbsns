<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');
include BASEPHP_FUNPATH . 'xn_zip.func.php';


if (empty($action) || $action == 'list') {

    $page       = param('page', 0);
    $title       = param('title', '');
    
    $where = array('status' => array('>=' => 0));

    if (!empty($title)) {

        $where['title'] = array('LIKE' => $title);

    }

    if ($page > 0) {
        $where = cache_get('last_doc_search');
    } else {
        cache_set('last_doc_search', $where);
        $page = 1;
    }
    $pagenum    = $conf['pagesize'];
    $topicslist   = doc_find($where, array('id'=>-1), $page, $pagenum);
    $totalnum   = doc_count($where);
    $pagination = pagination(r_url('doc-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);

    




    include ADMIN_PATH . "view/doc_list.html";
} else if ($action == 'tran') {
      $id   = param('id');
      $data = doc_read($id);
if($data['status']==2){
    echo '该文件已经上传，请等待';
    return;
}
if($conf['online_trans']==1){
$online_trans['time'] = $time;
$online_trans['appid'] = $conf['appid'];
$online_trans['token'] = md5($conf['auth_key'].$conf['appid'].$time);
$nowfileinfo = file__read($data['fileid']);

$filepath = $conf['upload_path']."doc/".$nowfileinfo['savepath'];
$online_trans['file'] = '@' . $filepath;
$online_trans['showpage'] = $data['showpage'];
$online_trans['hash'] = $nowfileinfo['sha1'];
$online_trans['filesize'] = $nowfileinfo['size'];
$online_trans['ext'] = $nowfileinfo['ext'];
$online_trans['filename'] = $nowfileinfo['savename'];
$online_trans['onlyname'] = str_replace('.'.$nowfileinfo['ext'],'',$nowfileinfo['savename']);

$n=http_post_file($conf['api_url'].'/api/upload',$online_trans,$filepath);

}else{
    $nowfileinfo = file__read($data['fileid']);
    $tmpfile = $conf['upload_path'] . 'doc/' . $nowfileinfo['savepath'];

$destfile = $conf['upload_path'] . 'output/' . $nowfileinfo['savename'];
$r = xn_copy($tmpfile, $destfile);
}

doc_update($id, ['status'=>2]);
echo '正在上传，可关闭此页面';







} else if ($action == 'add') {
  // global $uid, $time, $conf;
   //var_dump($_SESSION);
    if ($method == 'POST') {





        $dataarr = param_post('');

        $checkarr = array(
            'title'   => array(array('empty', '标题为空')));
 
        $r = wi_check_field('topic', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        $r[1]['uid'] = $uid;
        $r[1]['description'] = wi_getSummary($r[1]['content']);
         
        $result = topic_create($r[1]);
        if ($result) {
            if(!empty($r[1]['keywords'])){
                 topiccate_add_from_keywords($r[1]['keywords'],$result,'add',2); 
            }
            
            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {
        $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,20);
     
        
        include ADMIN_PATH . "view/topic_add.html";
    }
} else if ($action == 'edit') {
    if ($method == 'POST') {

        $id   = param('id');
        $info = doc_read($id);

        $dataarr = param_post('');
        if (!empty($dataarr)) {

            $checkarr = array(
                'title'   => array(array('empty', '标题为空')));

            $r = wi_check_field('doccon', $dataarr, $checkarr, 'edit','id',$info);
            

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }

            $r[1]['update_time'] = $time;
            
          
            
            $result = doc_update($id, $r[1]);
            if ($result) {
                if(!empty($r[1]['keywords'])){
                 topiccate_add_from_keywords($r[1]['keywords'],$id,'edit',2); 
            }
            
                message(0, '编辑成功');
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }

    } else {
        $id       = param('id');
        $info     = doc_read($id);

        $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,20);

        include ADMIN_PATH . "view/doc_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = doc_update($id, [$field => $value,'update_time'=>$time]);
        if ($result) {
            message(0, $message . '成功');
        } else {
            message(-1, $message . '失败');
        }

    }
} else if ($action == 'forbidden') {
    $id     = param('id');
    $status = param('val');
    if ($status == 1) {
        $status = 0;
        $statusname = '待审';
    } else {
        $status = 1;
        $statusname = '审核通过';
    }
         

    $topicinfo = doc_read($id);
    $subject = '你的文档《'.$topicinfo['title'].'》'.$statusname.'了&nbsp;&nbsp;';
    $mail_subject = '你的文档《'.$topicinfo['title'].'》'.$statusname.'了&nbsp;&nbsp;';

    send_message($topicinfo['uid'],$subject,$mail_subject,'content_operate');



    $result = doc_update($id, ['status' => $status,'update_time'=>$time]);
    if ($result) {
  
        
        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $result = doc_update($id, ['status' => -1,'update_time'=>$time]);
    if ($result) {

$topicinfo = doc_read($id);
$subject = '你的文档《'.$topicinfo['title'].'》被删除了&nbsp;&nbsp;';
$mail_subject = $subject;

send_message($topicinfo['uid'],$subject,$mail_subject,'content_operate');




        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
