<?php

!defined('DEBUG') AND exit('Access Denied.');



if (empty($action) || $action == 'list') {

    $page       = param('page', 1);
   
    
    $where = array('status' => array('>'=>0));


    $pagenum    = $conf['pagesize'];
    $jubaolist   = db_find('jubao',$where, array('id'=>-1), $page, $pagenum);
    $totalnum   = db_count('jubao',$where);
    $pagination = pagination(r_url('message-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/jubao_list.html";

} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_delete('jubao',array('id'=>$id));
    $touid = param('uid'); 
    $type = param(3,1);
    if($type==1){
    	send_sys_message($touid,'感谢你的举报，我们将积极处理该举报内容。');
    }
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}



?>
