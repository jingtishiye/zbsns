<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');

if (empty($action) || $action == 'list') {

    $page       = param('page', 0);
    $title       = param('title', '');

    $where = array('status' => array('>=' => 0));

    if (!empty($title)) {

        $where['title'] = array('LIKE' => $title);

    }

    if ($page > 0) {
        $where = cache_get('last_topicslider_search');
    } else {
        cache_set('last_topicslider_search', $where);
        $page = 1;
    }
    $pagenum    = $conf['pagesize'];
    $topicsliderlist   = db_find('topicslider', $where, array('create_time'=>-1), $page, $pagenum);
    $totalnum   = db_count('topicslider', $where);
    

    $pagination = pagination(r_url('topicslider-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/topicslider_list.html";

} else if ($action == 'add') {
    if ($method == 'POST') {

        $dataarr = param_post('');
      
        $checkarr = array(
            'title'   => array(array('length', '轮播图名称在2到20个字符之间', array(2, 20)), array('empty', '轮播图名称为空')));
 
        $r = wi_check_field('topicslider', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        $result = db_create('topicslider', $r[1]);
        if ($result) {

       if(!empty($dataarr['cover_id'])){
          single_attach_post($dataarr['cover_id'],$result,8);
        }

            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {

       $input['type'] = form_radio('type', array('1'=>'文章ID','2'=>'外链'),1);
        include ADMIN_PATH . "view/topicslider_add.html";
    }
} else if ($action == 'edit') {
     $id   = param('id');
        $info = db_find_one('topicslider', array('id'=>$id));
    if ($method == 'POST') {

       

        $dataarr = param_post('');
        
        if (!empty($dataarr)) {

            $checkarr = array(
            'title'   => array(array('length', '轮播图名称在2到20个字符之间', array(2, 20)), array('empty', '轮播图名称为空')));

            $r = wi_check_field('topicslider', $dataarr, $checkarr, 'edit','id',$info);

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }
            $r[1]['update_time'] = $time;
            $result = db_update('topicslider', array('id'=>$id), $r[1]);
            if ($result) {
                if($r[1]['cover_id']!=$info['cover_id']){
                    
                    single_attach_post($r[1]['cover_id'],$id,8);
                }
                message(0, '编辑成功');
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }

    } else {
        
       
       $input['type'] = form_radio('type', array('1'=>'文章ID','2'=>'外链'),$info['type']);
    
        include ADMIN_PATH . "view/topicslider_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = db_update('topicslider', array('id'=>$id), [$field => $value,'update_time'=>$time]);
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

    $result = db_update('topicslider', array('id'=>$id), ['status' => $status,'update_time'=>$time]);
    if ($result) {
        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_delete('topicslider', array('id'=>$id));
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
