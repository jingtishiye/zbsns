<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');


if (empty($action) || $action == 'list') {




    $page       = param('page', 1);
    $title       = param('title', '');
    
    $where = array('status' => array('>=' => 0));

    $pagenum    = $conf['pagesize'];
    $usergradeslist   = db_find('usergrade',$where, array('create_time'=>-1), $page, $pagenum);
    $totalnum   = db_count('usergrade',$where);
    $pagination = pagination(r_url('usergrade-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/usergrade_list.html";

} else if ($action == 'add') {
   global $uid, $time, $conf;

    if ($method == 'POST') {
        $dataarr = param_post('');
         if(!empty($dataarr['type'])){
        $dataarr['type'] = implode(',',$dataarr['type']);
        }
        
        $dataarr['limittime'] = implode(',',arr_strtoinval($dataarr['limittime']));

        $checkarr = array(
            'name'   => array(array('empty', '标题为空'), array('uniqid', '已存在该名称')),
            'days'=>array(array('func', '请填写不小于0的整数','is_zint')),
            'nums'=>array(array('func', '请填写不小于0的整数','is_zint')),
            'score'=>array(array('func', '请填写不小于0的整数','is_zint'))
        );
 
        $r = wi_check_field('usergrade', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        
        $result = db_create('usergrade',$r[1]);
        if ($result) {
            if(!empty($dataarr['cover_id'])){
            single_attach_post( $r[1]['cover_id'],$result,4);
            }
            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {
        $input['gmtype'] = form_radio('gmtype', array('1'=>'购买','2'=>'升级'),1);     
        $input['type'] = form_multi_checkbox('type[]', array('1'=>'发帖','2'=>'分享文档','3'=>'回帖','4'=>'查看付费内容','5'=>'下载附件','6'=>'下载文档'));
        $input['days'] = form_text('days', 0,'200px');
        $input['nums'] = form_text('nums', 0,'200px');
        $input['bili'] = form_text('bili', 100,'200px');
        $input['limittime1'] = form_text('limittime[]', 0,'200px');
        $input['limittime2'] = form_text('limittime[]', 0,'200px');
        $input['limittime3'] = form_text('limittime[]', 0,'200px');
        $input['limittime4'] = form_text('limittime[]', 0,'200px');
        $input['name'] = form_text('name', '');
        $input['score'] = form_text('score', 0,'200px');

        include ADMIN_PATH . "view/usergrade_add.html";
    }
} else if ($action == 'edit') {
     $id   = param('id');
     $info = db_find_one('usergrade',array('id'=>$id));
     $limittme = explode(',',$info['limittime']);
     if(empty($limittme[3])){$limittme[3]=0;}
    if ($method == 'POST') {

       
        $dataarr = param_post('');

         

        if(!empty($dataarr['type'])){
        $dataarr['type'] = implode(',',$dataarr['type']);
        }
         $dataarr['limittime'] = implode(',',arr_strtoinval($dataarr['limittime']));
        if (!empty($dataarr)) {

            $checkarr = array(
            'name'   => array(array('empty', '标题为空'), array('uniqid', '已存在该名称')),
            'days'=>array(array('func', '请填写不小于0的整数','is_zint')),
            'nums'=>array(array('func', '请填写不小于0的整数','is_zint')),
            'score'=>array(array('func', '请填写不小于0的整数','is_zint'))
            );

            $r = wi_check_field('usergrade', $dataarr, $checkarr, 'edit','id',$info);
            

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }

            $r[1]['update_time'] = $time;
            
            
            $result = db_update('usergrade',array('id'=>$id), $r[1]);
            if ($result) {
               if($r[1]['cover_id']!=$info['cover_id']){
                 single_attach_post( $r[1]['cover_id'],$id,4);
               }
               $nowinfo = db_find_one('usergrade',array('id'=>$id));
              
                db_update('user_extend',array('grades'=>$id),array('grades_bili'=>$nowinfo['bili'],'grades_limittime'=>$nowinfo['limittime'],'grades_type'=>$nowinfo['type'],'grades_name'=>$nowinfo['name']));
               
                db_update('user_extend',array('up_grades'=>$id),array('up_grades_bili'=>$nowinfo['bili'],'up_grades_limittime'=>$nowinfo['limittime'],'up_grades_type'=>$nowinfo['type'],'up_grades_name'=>$nowinfo['name']));
               

               
               
               
               
            
                message(0, '编辑成功');
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }

    } else {
       $input['gmtype'] = form_radio('gmtype', array('1'=>'购买','2'=>'升级'),$info['gmtype']);
        $input['type'] = form_multi_checkbox('type[]', array('1'=>'发帖','2'=>'分享文档','3'=>'回帖','4'=>'查看付费内容','5'=>'下载附件','6'=>'下载文档'),explode(',',$info['type']));
        $input['days'] = form_text('days', $info['days'],'200px');
        $input['nums'] = form_text('nums', $info['nums'],'200px');
        $input['bili'] = form_text('bili', $info['bili'],'200px');

        $input['limittime1'] = form_text('limittime[]', $limittme[0],'200px');
        $input['limittime2'] = form_text('limittime[]', $limittme[1],'200px');
        $input['limittime3'] = form_text('limittime[]', $limittme[2],'200px');
        $input['limittime4'] = form_text('limittime[]', $limittme[3],'200px');
        $input['name'] = form_text('name', $info['name']);
        $input['score'] = form_text('score',$info['score'],'200px');

        include ADMIN_PATH . "view/usergrade_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = db_update('usergrade',array('id'=>$id), [$field => $value,'update_time'=>$time]);
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
   
    $result = db_update('usergrade',array('id'=>$id), ['status' => $status,'update_time'=>$time]);
    if ($result) {
        

        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $result = db_update('usergrade',array('id'=>$id), ['status' => -1,'update_time'=>$time]);
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
