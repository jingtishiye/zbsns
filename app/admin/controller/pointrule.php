<?php

!defined('DEBUG') AND exit('Access Denied.');



if (empty($action) || $action == 'list') {
	$page       = param('page', 1);
    $where = array('status'=>1);
    $pagenum    = $conf['pagesize'];
    $pointrulelist   = db_find('point_rule',$where, array('id'=>-1), $page, $pagenum);
    $totalnum   = db_count('point_rule',$where);
    $pagination = pagination(r_url('pointrule-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    
    include ADMIN_PATH . "view/pointrule_list.html";
} else if ($action == 'add') {

    if ($method == 'POST') {





        $dataarr = param_post('');
        
        $dataarr['score'] = intval($dataarr['score']);
        $dataarr['num'] = intval($dataarr['num']);
        $dataarr['tasknum'] = intval($dataarr['tasknum']);
if($dataarr['num']<0){
	message(-1, '次数不能小于0');
}
if($dataarr['tasknum']<0){
    message(-1, '次数不能小于0');
}
if($dataarr['score']<1){
	message(-1, '积分不能小于1');
}
        $checkarr = array(
            'controller'   => array(array('empty', '名称为空'), array('uniqid', '已存在该动作名称')));
 
        $r = wi_check_field('point_rule', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        
        $r[1]['status'] = 1;
        
        $result = db_create('point_rule',$r[1]);
        if ($result) {
            
            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {




       $input['controller'] = form_select('controller', $conf['pointrule']);
     $input['scoretype'] = form_select('scoretype', $conf['pointname']);
         $input['type'] = form_select('type', array(1=>'增加',2=>'减少'));
        include ADMIN_PATH . "view/pointrule_add.html";
    }
} else if ($action == 'edit') {
 $id   = param('id');
        $info = db_find_one('point_rule',array('id'=>$id));
    if ($method == 'POST') {





        $dataarr = param_post('');
        
        $dataarr['score'] = intval($dataarr['score']);
        $dataarr['num'] = intval($dataarr['num']);
        $dataarr['tasknum'] = intval($dataarr['tasknum']);
if($dataarr['num']<0){
	message(-1, '次数不能小于0');
}
if($dataarr['tasknum']<0){
    message(-1, '次数不能小于0');
}
if($dataarr['score']<1){
	message(-1, '积分不能小于1');
}
        $checkarr = array(
            'controller'   => array(array('empty', '名称为空'), array('uniqid', '已存在该动作名称')));
 
        $r = wi_check_field('point_rule', $dataarr, $checkarr, 'edit','id',$info);

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['update_time'] = $time;
        
         
        $result = db_update('point_rule',array('id'=>$id),$r[1]);
        if ($result) {
            
            message(0, '编辑成功');
        } else {
            message(-1, '编辑失败');
        }

    } else {




       $input['controller'] = form_select('controller', $conf['pointrule'],$info['controller']);
     $input['scoretype'] = form_select('scoretype', $conf['pointname'],$info['scoretype']);
        $input['type'] = form_select('type', array(1=>'增加',2=>'减少'),$info['type']);
        include ADMIN_PATH . "view/pointrule_edit.html";
    }
} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_delete('point_rule',array('id'=>$id));
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}



?>
