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
    $rzuserlist   = db_find('rzuser',$where, array('create_time'=>-1), $page, $pagenum);
    $totalnum   = db_count('rzuser',$where);
    $pagination = pagination(r_url('rzuser-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/rzuser_list.html";

} else if ($action == 'cstatus') {
      $id   = param('uid');
        $info = db_find_one('rzuser',array('uid'=>$id));
    if ($method == 'POST') {
        $id      = param('uid');
        $status   = param('status');
        $rzdescrib   = param('rzdescrib');
     
        $result  = db_update('rzuser',array('uid'=>$id), ['status' =>$status,'update_time'=>$time,'rzdescrib'=>$rzdescrib]);
        if ($result!==false) {


           if($status==1){
            $update = array(
             'statusdes'=>$info['statusdes'],
             'rz'=>$info['type'],
             'keywords'=>$info['keywords'],

            );

            user_update($id,$update);
           }

            message(0, '审核完毕');
        } else {
            message(-1, '审核失败');
        }

    }else{
       
        include ADMIN_PATH . "view/rzuser_edit.html";
    }
}
