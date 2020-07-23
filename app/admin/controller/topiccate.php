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
        $where = cache_get('last_topiccate_search');
    } else {
        cache_set('last_topiccate_search', $where);
        $page = 1;
    }
    $pagenum    = $conf['pagesize'];
    $topiccatelist   = topiccate_find($where, array('create_time'=>-1), $page, $pagenum);
    $totalnum   = topiccate_count($where);
    

    $pagination = pagination(r_url('topiccate-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/topiccate_list.html";

} else if ($action == 'add') {
    if ($method == 'POST') {

        $dataarr = param_post('');
        if(!empty($dataarr['gradeid'])){
            $dataarr['gradeid'] = implode(',',$dataarr['gradeid']);
        }
        $checkarr = array(
            'name'   => array(array('length', '话题名称在2到10个字符之间', array(2, 10)), array('empty', '话题名称为空'), array('uniqid', '已存在该话题')));
 
        $r = wi_check_field('topiccate', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        $result = topiccate_create($r[1]);
        if ($result) {

       if(!empty($dataarr['cover_id'])){
          single_attach_post($dataarr['cover_id'],$result,3);
        }

            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {

        $usergrade = db_find_all('usergrade',array('status'=>1),'','id','id,name');
        foreach ($usergrade as $key => $value) {
            
            $usergrade[$key] = $value['name'];

        }
        
            

        $input['gradeid'] = form_multi_checkbox('gradeid[]', $usergrade);
        include ADMIN_PATH . "view/topiccate_add.html";
    }
} else if ($action == 'edit') {
    if ($method == 'POST') {

        $id   = param('id');
        $info = topiccate_read($id);

        $dataarr = param_post('');
        if(!empty($dataarr['gradeid'])){
            $dataarr['gradeid'] = implode(',',$dataarr['gradeid']);
        }
        if (!empty($dataarr)) {

            $checkarr = array(
                'name'   => array(array('length', '话题名称在2到10个字符之间', array(2, 10)), array('empty', '话题名称为空'), array('uniqid', '已存在该话题名称')));

            $r = wi_check_field('topiccate', $dataarr, $checkarr, 'edit','id',$info);

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }
            $r[1]['update_time'] = $time;
            $result = topiccate_update($id, $r[1]);
            if ($result) {
                if($r[1]['cover_id']!=$info['cover_id']){
                    
                    single_attach_post($r[1]['cover_id'],$id,3);
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
        $info     = topiccate_read($id);
       
        $usergrade = db_find_all('usergrade',array('status'=>1),'','id','id,name');
        foreach ($usergrade as $key => $value) {
            
            $usergrade[$key] = $value['name'];
           
        }
    $input['gradeid'] = form_multi_checkbox('gradeid[]', $usergrade,explode(',',$info['gradeid']));
    
        include ADMIN_PATH . "view/topiccate_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = topiccate_update($id, [$field => $value,'update_time'=>$time]);
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

    $result = topiccate_update($id, ['status' => $status,'update_time'=>$time]);
    if ($result) {
        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $result = topiccate_update($id, ['status' => -1,'update_time'=>$time]);
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
