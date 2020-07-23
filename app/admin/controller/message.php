<?php

!defined('DEBUG') AND exit('Access Denied.');



if (empty($action) || $action == 'list') {

    $page       = param('page', 1);
   
    
    $where = array('status' => array('>'=>0));


    $pagenum    = $conf['pagesize'];
    $messagelist   = db_find('message',$where, array('id'=>-1), $page, $pagenum);
    $totalnum   = db_count('message',$where);
    $pagination = pagination(r_url('message-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/message_list.html";
} else if ($action == 'add') {

    if ($method == 'POST') {





        $dataarr = param_post('');

        $checkarr = array(
            'content'   => array(array('empty', '内容为空')));
 
        $r = wi_check_field('message', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['type']=1;
        $r[1]['status']=1;
        $r[1]['create_time']=$time;
        $result = db_create('message',$r[1]);
        if ($result) {
           
            
            message(0, '发送通知成功');
        } else {
            message(-1, '发送通知失败');
        }

    } else {
        
     
        
        include ADMIN_PATH . "view/message_add.html";
    }
} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_delete('message',array('id'=>$id));
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}



?>
