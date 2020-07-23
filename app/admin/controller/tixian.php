<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');



if (empty($action) || $action == 'list') {

    $page       = param('page', 1);

    $where = array('status' => array('>=' => 0));

    $pagenum    = $conf['pagesize'];
    $tixian_list   = db_find('tixian',$where, array('create_time'=>-1), $page, $pagenum);
    $totalnum   = db_count('tixian',$where);
    $pagination = pagination(r_url('tixian-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/tixian_list.html";

} else if ($action == 'cstatus') {
  
        $id      = param('id');
    $info = db_find_one('tixian',array('id'=>$id));
        $result  = db_update('tixian',array('id'=>$id), ['status'=>1,'update_time'=>$time]);
        if ($result) {
$data['uid'] = $info['uid'];
$data['to_uid'] = 0;
$data['description'] = '提现';
           point_note_op(7,$info['score'],'point','-',$data);

            message(0, '提现成功');
        } else {
            message(-1, '提现失败');
        }



} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_delete('tixian',array('id'=>$id));
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
