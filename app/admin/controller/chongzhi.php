<?php

!defined('DEBUG') AND exit('Access Denied.');



if (empty($action) || $action == 'list') {

    $page       = param('page', 1);
   
    http_url_path();
    $where = array('status' => array('>='=>0));


    $pagenum    = $conf['pagesize'];
    $jubaolist   = db_find('chongzhi',$where, array('status'=>-1,'id'=>-1), $page, $pagenum);
    $totalnum   = db_count('chongzhi',$where);
    $pagination = pagination(r_url('chongzhi-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/cz_list.html";

} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_delete('chongzhi',array('id'=>$id));

    
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

} else if ($action == 'qkjl') {
    
    $result = db_delete('chongzhi',array('status'=>0));

    
    if ($result) {
        message(0, '清空成功');

    } else {
        message(-1, '清空失败');
    }

}



?>
