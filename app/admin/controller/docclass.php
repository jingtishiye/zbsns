<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');



if (empty($action) || $action == 'list') {

    $parent_pid = param('parent_pid', 0);
    $pid        = param('pid', 0);
    $page       = param('page', 0);
    $name       = param('name', '');

    $where = array('pid' => $pid, 'status' => array('>=' => 0));

    if (!empty($name)) {

        $where['name'] = array('LIKE' => $name);

    }

    if ($page > 0) {
        $where = cache_get('last_docclass_search');
    } else {
        cache_set('last_docclass_search', $where);
        $page = 1;
    }
    $pagenum    = $conf['pagesize'];
    $docclasslist   = docclass_find($where, '', $page, $pagenum);
    $totalnum   = docclass_count($where);
    $pagination = pagination(r_url('docclass-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/docclass_list.html";

} else if ($action == 'add') {
    if ($method == 'POST') {

        $dataarr = param_post('');
if(!empty($dataarr['gradeid'])){
            $dataarr['gradeid'] = implode(',',$dataarr['gradeid']);
        }
        $checkarr = array(
            'name'   => array(array('length', '分类名称在3到20个字符之间', array(3, 20)), array('empty', '分类名称为空'), array('uniqid', '已存在该分类名称')),
        );

        $r = wi_check_field('docclass', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        if($r[1]['pid']>0){

            $info = docclass_read($r[1]['pid']);
            if($info['pid']>0){
                message(-1, '目前仅支持二级分类');
            }
        }
        $result = docclass_create($r[1]);
        if ($result) {
            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {
        $menulist = docclass_find(array('pid' => 0, 'status' => array('>=' => 0)), '', '', docclass_count(array('pid' => 0, 'status' => array('>=' => 0))));

        $select = docclass_getselect($menulist);
        $pid    = param('pid', 0);
          $usergrade = db_find_all('usergrade',array('status'=>1),'','id','id,name');
        foreach ($usergrade as $key => $value) {
            
            $usergrade[$key] = $value['name'];

        }
        
            

        $input['gradeid'] = form_multi_checkbox('gradeid[]', $usergrade);
        include ADMIN_PATH . "view/docclass_add.html";
    }
} else if ($action == 'edit') {
    if ($method == 'POST') {

        $id   = param('id');
        $info = docclass_read($id);

        $dataarr = param_post('');
        
        if(!empty($dataarr['gradeid'])){
            $dataarr['gradeid'] = implode(',',$dataarr['gradeid']);
        }
        if (!empty($dataarr)) {

            $checkarr = array(
                'name'   => array(array('length', '分类名称在3到20个字符之间', array(3, 20)), array('empty', '分类名称为空'), array('uniqid', '已存在该分类名称')),
            );

            $r = wi_check_field('docclass', $dataarr, $checkarr, 'edit','id',$info);

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }
            $r[1]['update_time'] = $time;

            
            
        if($r[1]['pid']>0){

            $pidinfo = docclass_read($r[1]['pid']);
            if($pidinfo['pid']>0){
                message(-1, '目前仅支持二级分类');
            }
            $count  =  docclass_count(array('pid'=>$id, 'status' => array('>=' => 0)));
            if($count>0){
               message(-1, '该分类存在子类，不能变为其他类别的子类');
            }
        }
            $result = docclass_update($id, $r[1]);
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
        $info     = docclass_read($id);
        $menulist = docclass_find(array('pid' => 0,'id'=>array('!='=>$id), 'status' => array('>=' => 0)), '', '', docclass_count(array('pid' => 0,'id'=>array('!='=>$id), 'status' => array('>=' => 0))));

        $select = docclass_getselect($menulist);
 
        $usergrade = db_find_all('usergrade',array('status'=>1),'','id','id,name');
        foreach ($usergrade as $key => $value) {
            
            $usergrade[$key] = $value['name'];
           
        }
    $input['gradeid'] = form_multi_checkbox('gradeid[]', $usergrade,explode(',',$info['gradeid']));



        include ADMIN_PATH . "view/docclass_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = docclass_update($id, [$field => $value,'update_time'=>$time]);
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

    $result = docclass_update($id, ['status' => $status,'update_time'=>$time]);
    if ($result) {
        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $count  =  docclass_count(array('pid'=>$id, 'status' => array('>=' => 0)));
    if($count>0){
        message(-1, '该分类下存在子类');
    }

    $result = db_delete('docclass', array('id'=>$id));

    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
