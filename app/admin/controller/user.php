<?php

!defined('DEBUG') AND exit('Access Denied.');



if (empty($action) || $action == 'list') {

   
  
    $page       = param('page', 0);
    $name       = param('name', '');

    $where = array('status' => array('>=' => 0));

    if (!empty($name)) {
        $str = db_concat_field(array('nickname','username'));
        $where[$str] = array('LIKE'=>$name);
 

    }

    if ($page > 0) {
        $where = cache_get('last_user_search');
    } else {
        cache_set('last_user_search', $where);
        $page = 1;
    }
    
    


    $pagenum    = $conf['pagesize'];
    $userlist   = user_find($where, array('id'=>-1), $page, $pagenum);
    $totalnum   = user_count($where);
    $pagination = pagination(r_url('user-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/user_list.html";

} else if ($action == 'edit') {
	  $id       = param('id');
       $info     = user_read($id);
    if ($method == 'POST') {

       

        $dataarr = param_post('');
        if (!empty($dataarr)) {

            $checkarr = array(
                'nickname'   => array(array('empty', '昵称为空'), array('uniqid', '已存在该昵称')),
                
            );

            $r = wi_check_field('user', $dataarr, $checkarr, 'edit','id',$info);

            if (!$r[0]) {
                message(-1, $r[1], array('key' => $r[2]));
            }
           
            $result = user_update($id, $r[1]);
            if ($result) {
                message(0, '编辑成功');
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }

    } else {
      
     

        include ADMIN_PATH . "view/user_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = user_update($id, [$field => $value]);
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
        
    $result = user_update($id, ['status' => $status]);
    if ($result) {
  

        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $result = user_update($id, ['status' => -1]);
    if ($result) {
        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}elseif($action == 'changepwd') {
	
	//更改管理员密码
	
	// 校验数据
	
	
	if($method == 'GET') {

		
		
		include ADMIN_PATH.'view/user_changepwd.html';

	} else if($method == 'POST') {
		
		$password_old = param('password_old');
		$password_new = param('password_new');
		$password_new_repeat = param('password_new_repeat');
		empty($password_old) AND message('password_old', '请输入旧密码');
		empty($password_new) AND message('password_new', '请输入新密码');
		empty($password_new_repeat) AND message('password_new_repeat', '新密码需要输入两次');
		if($password_new!=$password_new_repeat){
			message('password_new', '两次密码不一致');
		}

		$uid = intval(_SESSION('uid'));
		$user = user_read($uid);
		$salt = $user['salt'];
		$password_old = md5($password_old.$salt);
		
		
		!is_password($password_old) AND message('password_old', '旧密码输入有误');
		if($password_old!=$user['password']){
            message('password_old', '旧密码不正确');
		}else{

            $arr = array('password'=>md5($password_new.$salt));

            $result = user_update($uid,$arr);
            if($result){
               message(0, '修改密码成功');
            }else{
               message(-1, '修改密码失败');
            }
           
		}

		
	}


}



?>
