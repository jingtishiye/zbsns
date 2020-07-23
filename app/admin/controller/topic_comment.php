<?php

!defined('DEBUG') AND exit('Access Denied.');



if (empty($action) || $action == 'list') {

    $page       = param('page', 1);
   
    
    $where = array('status' => 1,'type'=>1);


    $pagenum    = $conf['pagesize'];
    $commentlist   = db_find('comment',$where, array('create_time'=>-1), $page, $pagenum);
    $totalnum   = db_count('comment',$where);
    $pagination = pagination(r_url('topic_comment-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/comment_list.html";

} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_update('comment',array('id'=>$id), ['status' => -1,'update_time'=>$time]);
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}



?>
