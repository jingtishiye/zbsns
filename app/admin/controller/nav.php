<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');



if (empty($action) || $action == 'list') {

    $page       = param('page', 0);
    $name       = param('name', '');
    
    $where = array('status' => array('>=' => 0));

    if (!empty($name)) {

        $where['name'] = array('LIKE' => $name);

    }

    if ($page > 0) {
        $where = cache_get('last_nav_search');
    } else {
        cache_set('last_nav_search', $where);
        $page = 1;
    }
    $pagenum    = $conf['pagesize'];
    $navlist   = nav_find($where, array('id'=>-1), $page, $pagenum);
    $totalnum   = nav_count($where);
    $pagination = pagination(r_url('nav-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/nav_list.html";

} else if ($action == 'add') {
    if ($method == 'POST') {

        $dataarr = param_post('');

        $checkarr = array(
            'name'   => array(array('length', '导航名称在2到10个字符之间', array(2, 10)), array('empty', '导航名称为空'), array('uniqid', '已存在该导航')));
 
        $r = wi_check_field('nav', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        $result = nav_create($r[1]);
        if ($result) {

       

            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {


        include ADMIN_PATH . "view/nav_add.html";
    }
} else if ($action == 'edit') {
    if ($method == 'POST') {

        $id   = param('id');
        $info = nav_read($id);

        $dataarr = param_post('');
        if (!empty($dataarr)) {

            $checkarr = array(
                'name'   => array(array('length', '导航名称在3到10个字符之间', array(2, 10)), array('empty', '导航名称为空'), array('uniqid', '已存在该导航名称')));

            $r = wi_check_field('nav', $dataarr, $checkarr, 'edit','id',$info);

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }
            $r[1]['update_time'] = $time;
            $result = nav_update($id, $r[1]);
            if ($result) {
            
                message(0, '编辑成功');
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }

    } else {
        $id       = param('id');
        $info     = nav_read($id);


        include ADMIN_PATH . "view/nav_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = nav_update($id, [$field => $value,'update_time'=>$time]);
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
    } else {
        $status = 1;
    }

    $result = nav_update($id, ['status' => $status,'update_time'=>$time]);
    if ($result) {
        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $result = nav_delete($id);
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
