<?php

!defined('DEBUG') and exit('Access Denied.');


$active = $action;

if (in_array($action, array('focus', 'topic', 'doc', 'notification', 'setting', 'usermail', 'password', 'setnotify', 'rzuser', 'avatar','tags','up_usergrade','myjifen','chongzhi','buy'))) {
    user_login_check();
}

if (empty($action)) {


$type = param(3, 1); //1是文章2是文档
$type = module_select($type);


    $_uid                  = param(2, 0);
    empty($_uid) and $_uid = $uid;
    $_user                 = user_read($_uid);

    empty($_user) and message(-1, '用户不存在');

    $header['title'] = $_user['nickname'] . '的主页';

    $where = array('status' => 1, 'uid' => $_uid);
    $order = array('id' => -1);

    $pagenum = $conf['pagesize'];
    $page    = param('page', 1);

    $pagination = '';
   

    if ($type == 1) {
        $topicslist = topic_find($where, $order, $page, $pagenum);
        $totalnum   = topic_count($where);
        $pagination = pagination(r_url('user-' . $_uid . '-1', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    } elseif($type==2) {
//这里放文档
        $topicslist = doc_find($where, $order, $page, $pagenum); //这里换成获取文档列表
        $totalnum   = doc_count($where);
        $pagination = pagination(r_url('user-' . $_uid . '-2', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    }

    include $conf['view_path'] . 'ucenter.html';

} elseif ($action == 'focus') {

    $type       = param(3, 1); //1是粉丝2是关注3是好友
    $_user      = $user;
    $fensi_arr  = andother_find_arr('usersandother', 0, $uid, 'did', 'uid', true); //所有粉丝的id
    $focus_arr  = andother_find_arr('usersandother', 0, $uid, 'uid', 'did', true); //所有我关注的用户的id
    $friend_arr = array_intersect($fensi_arr, $focus_arr); //两个数组的交集就是好友

    if ($type == 1) {
        $arr = $fensi_arr;
    } elseif ($type == 2) {
        $arr = $focus_arr;
    } else {
        $arr = $friend_arr;
    }
    empty($arr) and $arr = 0;

    $where      = array('status' => 1, 'id' => $arr);
    $order      = array('id' => -1);
    $pagenum    = $conf['pagesize'];
    $page       = param('page', 1);
    $userslist  = user_find($where, $order, $page, $pagenum);
    $totalnum   = user_count($where);
    $pagination = pagination(r_url('user-focus-' . $type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include $conf['view_path'] . 'user_focus.html';
} elseif ($action == 'topic') {

    $type  = param(3, 1); //1是自己文章2是收藏文章
    $_user = $user;

   

    

    if ($type == 1) {
         $where      = array('status' => 1, 'uid' => $uid);
    } else {
        $focus_arr = andother_find_arr('usersandother', 1, $uid, 'uid', 'did', true); //所有我关注的文章的id
        empty($focus_arr) and $focus_arr = 0;
        $where      = array('status' => 1, 'id' => $focus_arr);
    }



    

   
    $order      = array('id' => -1);
    $pagenum    = $conf['pagesize'];
    $page       = param('page', 1);
    $topicslist = topic_find($where, $order, $page, $pagenum);
    $totalnum   = topic_count($where);
    $pagination = pagination(r_url('user-topic-' . $type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include $conf['view_path'] . 'ucenter_topic.html';
} elseif ($action == 'doc') {

    $type  = param(3, 1); //1是自己文档2是收藏文档
    $_user = $user;

   

    
    

    if ($type == 2) {
    	$focus_arr   = andother_find_arr('usersandother', 3, $uid, 'uid', 'did', true); //所有我关注的文档的id
        $arr = $focus_arr;
    } elseif($type==3) {
         $down_where['uid']=$uid;
         $down_where['inctype']='-';
         $down_where['type']=2;
         $down_id_arr = db_find_column('point_note',$down_where,'itemid');

    	
        $arr = $down_id_arr;
    }
    empty($arr) and $arr = 0;


    if($type==1){
       $where   = array('status' => 1, 'uid' => $uid);
    }else{
    	$where   = array('status' => 1, 'id' => $arr);
    }
    



    $order   = array('id' => -1);
    $pagenum = $conf['pagesize'];
    $page    = param('page', 1);

    $topicslist = doc_find($where, $order, $page, $pagenum);

    $totalnum   = doc_count($where);
    $pagination = pagination(r_url('user-doc-' . $type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include $conf['view_path'] . 'ucenter_doc.html';
} elseif ($action == 'myjifen') {
     $_user = $user;
     $page = param('page');

$where = array('status' => 1,'uid'=>$uid);

$pagenum    = $conf['pagesize'];
$pointnotelist   = db_find('point_note',$where, array('create_time'=>-1), $page, $pagenum);
$totalnum   = db_count('point_note',$where);
$pagination = pagination(r_url('user-myjifen', array('page' => 'pagenum')), $totalnum, $page, $pagenum);



    include $conf['view_path'] . 'user_myjifen.html';

}elseif ($action == 'buy') {

empty($user) AND message(-1, '用户未登录');
$id = param('id');
$type = param(3,5);
$score = 0; 
$description = '';
$touid = 0;


if($type==5){
$gradeinfo = db_find_one('usergrade',array('id'=>$id));
empty($gradeinfo) AND message(-1, '会员组不存在');
$score = $gradeinfo['score']; 
$description = '购买会员组'.$gradeinfo['name'];
$touid = 0;

}
if($type==3){
if(!in_array(4,$userqx['quanxian'])){
             message(-1, '无权限付费阅读');
 }
$threadinfo = topic_read($id);
empty($threadinfo) AND message(-1, '帖子不存在');
$score = $threadinfo['score']; 
$description = '付费阅读帖子《'.$threadinfo['title']."》";
$touid = $threadinfo['uid']; 

}

$user['extend']['point']<$score AND message(-1, '您的积分不足，请及时充值！');

$data['uid'] = $uid;
$data['to_uid'] = $touid;
$data['description'] = $description;
$data['itemid'] = $id;

$r = point_note_op($type,$score,'point','-',$data);
if($r){
if($type==5){
$updata = array('grades'=>$gradeinfo['id'],'grades_type'=>$gradeinfo['type'],'grades_days'=>$gradeinfo['days'],'grades_nums'=>$gradeinfo['nums'],'grades_bili'=>$gradeinfo['bili'],'grades_name'=>$gradeinfo['name'],'grades_limittime'=>$gradeinfo['limittime'],'grades_time'=>time());
user_extend_update($uid,$updata);
$userqx = user_qx($uid);
}


if($type==3){
  message(0, $description.'成功',array('url'=>r_url('thread-'.$id)));   
}
message(0, $description.'成功'); 


}else{
 message(-1, $description.'失败！');   
}


} elseif ($action == 'usergrade') {
$type  = param(3, 2); //1是我的会员组2是购买3是升级
if($type>1){
$usergradelist = db_find_all('usergrade',array('status'=>1,'gmtype'=>$type-1));
}else{
    user_login_check();
}
include $conf['view_path'] . 'ucenter_usergrade.html';


} elseif ($action == 'tags') {
    $type  = param(3, 1); //1是关注的话题2是创建的话题
    $_user = $user;
   
if($type==1){
 $focus_arr   = andother_find_arr('usersandother', 2, $uid, 'uid', 'did', true); //所有我关注的话题的id

    empty($focus_arr) and $focus_arr = 0;

    $where   = array('status' => 1, 'id' => $focus_arr);
}else{
    $where   = array('status' => 1, 'uid' => $uid);
}
   
    $order   = array('num' => -1);
    $pagenum = $conf['pagesize'];
    $page    = param('page', 1);

    $tagslist = topiccate_find($where, $order, $page, $pagenum);

    $totalnum   = topiccate_count($where);
    $pagination = pagination(r_url('user-tags-' . $type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);

    include $conf['view_path'] . 'ucenter_tags.html';


} elseif ($action == 'jubao') {
    empty($user) and message(-1, '请先登录');
    $id      = param('id');
    $type    = param('type');
    $reason  = param('reason');
    $content = param('content');
    $insert  = array(
        'type' => $type,
        'uid'  => $uid,
        'fid'  => $id,
    );

    $r = db_find_one('jubao', $insert);
    if ($r) {
        message(-1, '你已举报过该内容');
    } else {
        $insert['create_time'] = $time;
        $insert['content']     = $content;
        $insert['reason']      = $reason;
        $result                = db_create('jubao', $insert);
        if ($result) {
            message(0, '举报成功');
        } else {
            message(-1, '举报失败，请稍后再试');
        }
    }

} elseif ($action == 'notification') {
	$type  = param(3, 1); //1是系统消息2是私信
    $_user = $user;
    $where = array();


$hasreadlist=db_find_column('usersandother',array('type'=>4,'uid'=>$uid),'did');

 $arr2 =  db_find_column('message',array('status'=>1,'touid'=>array($uid,0),'type'=>1,'id'=>array('!='=>$hasreadlist)),'id');




if($type==1){
if($arr2){
$where   = array('id'=>$arr2);
}else{
$where   = array('status'=>0,'type'=>1);
}


}else{
$where   = array('status' => 1, 'touid' => $uid,'type'=>2,'uid'=>array('>'=>0));	
}
    
   
    
    $order   = array('create_time' => -1);
    $pagenum = $conf['pagesize'];
    $page    = param('page', 1);

    $messslist = db_find('message',$where, $order, $page, $pagenum);


    foreach ($messslist as $key => $value) {
    	
    	
    	if($value['type']==2){
    		$messslist[$key]['content_fmt'] = msubstr(htmlspecialchars_decode($value['content']),0,150);
    		$messslist[$key]['user'] = user_read($value['uid']);
    	}else{
    		$messslist[$key]['content_fmt'] = htmlspecialchars_decode($value['content']);

    	}
    	
    	$messslist[$key]['create_time_fmt'] = humandate($value['create_time']);
    }
   
    $totalnum   = db_count('message',$where);
    $pagination = pagination(r_url('user-notification-' . $type, array('page' => 'pagenum')), $totalnum, $page, $pagenum);




    include $conf['view_path'] . 'user_notification.html';
} elseif ($action == 'openmess') {

$mess_uid = param('uid');
$id = param('id');
$mess_user = user_read_cache($mess_uid);
$messlist = db_find_all('message',array('type'=>2,'uid'=>array($mess_uid,$uid),'touid'=>array($mess_uid,$uid),'status'=>1),array('create_time'=>1));




include $conf['view_path'] . 'openmess.html';

} elseif ($action == 'setting') {

    if ($method == 'POST') {
        $dataarr  = param_post('');
        $checkarr = array(

            'nickname' => array(array('empty', '昵称不能为空'), array('uniqid', '该昵称已被其他人使用')),
        );
        $dataarr['id'] = $uid;
        $r             = wi_check_field('user', $dataarr, $checkarr, 'edit', 'id', $user);
        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }

        $result = user_update($uid, $r[1]);
        if ($result) {
            message(0, '资料修改成功');
        } else {
            message(-1, '资料修改失败');
        }

    } else {
        include $conf['view_path'] . 'user_setting.html';
    }

} elseif ($action == 'usermail') {

    if ($method == 'POST') {

        // 校验数据
        $sess_email = _SESSION('user_bind_email');
        $sess_code  = _SESSION('user_bind_code');
        (empty($sess_email) || empty($sess_code)) and message(-1, '验证码已失效');

        $usermail = param('usermail');
        $code     = param('code');

        $usermail != $sess_email and message(-1, '邮箱不一致');

        $_user = user_read_by_email($usermail);
        if (!empty($_user) && $_user['id'] != $uid) {
            message('usermail', '该邮箱已被其他人占用');
        }
        if ($sess_code == $code) {
            user_update($uid, array('usermail' => $usermail));
            user_extend_update($uid, array('mailstatus' => 1));

            unset($_SESSION['user_bind_email']);
            unset($_SESSION['user_bind_code']);
            point_rule('bindmail',$uid);
            message(0, '邮箱绑定成功');

        } else {
            message(-1, '验证码不正确');
        }

    } else {

        include $conf['view_path'] . 'user_usermail.html';
    }

} elseif ($action == 'list') {
$page = param('page',1);
$pagenum    = $conf['pagesize'];
$where = array('status' => 1,'rz' => array('>'=>0));
$search_list_user   = user_find($where, array('regtime'=>1), $page, $pagenum);

$totalnum   = db_count('user',$where);


$pagination = pagination(r_url('user-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);

    include $conf['view_path'] . 'user_list.html';

} elseif ($action == 'password') {

    if ($method == 'GET') {

        include $conf['view_path'] . 'user_pass.html';

    } elseif ($method == 'POST') {

        $password_old        = param('password');
        $password_new        = param('newpassword');
        $password_new_repeat = param('repassword');

        $password_new_repeat != $password_new and message(-1, '两次密码输入不一致');
        md5($password_old . $user['salt']) != $user['password'] and message('password', '旧密码输入不正确');
        $password_new = md5($password_new . $user['salt']);
        $r            = user_update($uid, array('password' => $password_new));
        $r === false and message(-1, '密码修改失败');

        message(0, '密码修改成功', array('reset' => 1));

    }

} elseif ($action == 'setnotify') {

    if ($method == 'GET') {
        $arr = array(
            'sxtype'                   => 1,
            'new_content'              => 0,
            'new_user_content'         => 0,
            'new_comment_content'      => 0,
            'content_operate'          => 0,
            'someone_focusme'          => 0,
            'mail_new_content'         => 0,
            'mail_new_user_content'    => 0,
            'mail_new_comment_content' => 0,
            'mail_content_operate'     => 0,
            'mail_someone_focusme'     => 0);

        $notify_set = $user['extend']['notify_set'];
        if ($notify_set) {

            $notify_set = xn_json_decode($notify_set);

            $notify_set = $notify_set + $arr;

        } else {
            $notify_set = $arr;

        }

        include $conf['view_path'] . 'user_setnotify.html';

    } else {

        $data = param_post();
        user_extend_update($uid, array('notify_set' => xn_json_encode($data)));
        message(0, '设置成功');

    }

} elseif ($action == 'rzuser') {

    $rzuser = db_find_one('rzuser', array('uid' => $uid));
    if ($method == 'GET') {

           if($conf['allow_cate_show']>0){
       $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,$conf['allow_cate_show']);
    }else{
       $taglist = topiccate_find_all(array('status'=>1),array('sort'=>'-1'));
    }  

        
        include $conf['view_path'] . 'user_rzuser.html';

    } else {
        $dataarr         = param_post('');
        $dataarr['type'] = param('type', 0);
        $checkarr        = array(
            'name'      => array(array('empty', '姓名|机构名为空')),
            'idcard'    => array(array('empty', '证件号码为空')),
            'statusdes' => array(array('empty', '认证介绍为空')),
            'mobile'    => array(array('empty', '手机号码为空'), array('func', '手机号码格式不正确', 'is_mobile')),
            'keywords'  => array(array('empty', '未选择擅长领域')),
        );

        $r = wi_check_field('rzuser', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }

        if ($dataarr['cover_id'] == 0) {
           // message(-1, '扫描件必须上传');
        }
        if ($dataarr['type'] == 0) {
            message(-1, '未选择认证类型');
        }
        $r[1]['create_time'] = $time;
        $r[1]['update_time'] = $time;
        $r[1]['status']      = 0;
        $r[1]['uid']         = $uid;

        $result = db_replace('rzuser', $r[1]);

        if ($result !== false) {

            if ($rzuser['cover_id'] != $dataarr['cover_id']) {
               
                single_attach_post($dataarr['cover_id'],$uid,5);
            }

            if ($rzuser['status'] == 1) {

                user_update($uid, ['rz' => 0]);
            }

            message(0, '申请信息已上传，请等待管理员审核');
        } else {
            message(-1, '申请信息上传失败');
        }

    }

} elseif ($action == 'avatar') {

    if ($method == 'GET') {

        include $conf['view_path'] . 'user_avatar.html';

    } else {

        $width  = param('width');
        $height = param('height');
        $data   = param('data', '', false);

        empty($data) and message(-1, '数据为空');
        $filetypes = include DATA_PATH . 'config/attach.conf.php';
        $data      = base64_decode_file_data($data);
        $size      = strlen($data);
        $size > 40000 and message(-1, '超出大小');

        $filename = "$uid.png";
        $dir      = substr(sprintf("%09d", $uid), 0, 3) . '/';
        $path     = $conf['upload_path'] . 'avatar/' . $dir;
        $url      = $conf['upload_url'] . 'avatar/' . $dir . $filename;
        !is_dir($path) and (mkdir($path, 0777, true) or message(-2, '文件夹创建失败'));

        file_put_contents($path . $filename, $data) or message(-1, '写入文件失败');
        $mimetype = check_safe_image($path . $filename);
        if ($mimetype == IMAGETYPE_GIF || $mimetype == IMAGETYPE_JPEG || $mimetype == IMAGETYPE_PNG || $mimetype == IMAGETYPE_BMP) {
            user_update($uid, array('avatar' => $time));

            message(0, '上传成功', array('url' => $url . '?' . $time));
        } else {
            unlink($path . $filename);
            message(-1, '非法文件');
        }

    }
} elseif ($action == 'login') {

    if ($method == 'GET') {

        $referer = user_http_referer();

        $header['title'] = '用户登录';

        include $conf['view_path'] . 'user_login.html';

    } else if ($method == 'POST') {

        $net_auto_login = param('net_auto_login');

        $username = param('username'); // 邮箱或者用户名
        $password = param('password');
        empty($username) and message('username', '邮箱或用户名为空');
        if (is_email($username)) {
            $_user = user_read_by_email($username);
            empty($_user) and message('username', '该邮箱不存在');
        } else {
            $_user = user_read_by_username($username);
            empty($_user) and message('username', '该用户不存在');
        }
        $password = md5($password . $_user['salt']);
        !is_password($password) and message('password', '密码输入有误');
        $check = ($password == $_user['password']);

        !$check and message('password', '密码不正确');

        // 更新登录时间和次数

        user_update($_user['id'], array('last_login_ip' => $longip, 'last_login_time' => $time, 'logins+' => 1));

      

        $uid = $_user['id'];

        $_SESSION['uid'] = $uid;

        if ($net_auto_login == 1) {
            user_token_set($_user['id']);
        }

        // 设置 token，下次自动登陆。
        point_rule('login',$uid);
        message(0, '登录成功');

    }

} elseif ($action == 'create') {

    if (!$conf['user_create_on']) {

        message(-1, '网站未开放注册');
    }

    if ($method == 'GET') {

        $referer         = user_http_referer();
        $header['title'] = '用户注册';

        include $conf['view_path'] . 'user_create.html';

    } else if ($method == 'POST') {

        $usermail   = param('usermail');
        $username   = param('username');
        $password   = param('password');
        $repassword = param('repassword');
        $code = param('code');
        empty($usermail) and message('usermail', '邮箱为空');
        empty($username) and message('username', '用户名为空');
        empty($password) and message('password', '密码为空');

        if ($conf['user_create_email_on']) {
            $sess_email = _SESSION('user_create_email');
            $sess_code  = _SESSION('user_create_code');
            empty($sess_code) and message('code', '点击获取验证码');
            empty($sess_email) and message('code', '点击获取验证码');
            $usermail != $sess_email and message('code', '验证码错误');
            $code != $sess_code and message('code', '验证码错误');
        }

        !is_email($usermail) and message('usermail', '邮箱格式不正确');
        $_user = user_read_by_email($usermail);
        $_user and message('usermail', '该邮箱已被占用');

        !is_username($username) and message('username', '用户名格式不正确');
        $_user = user_read_by_username($username);
        $_user and message('username', '该用户名已被占用');

        ($repassword != $password) and message('password', '两次密码输入不一致');

        $salt = xn_rand(16);
        $pwd  = md5($password . $salt);

        $_user = array(
            'username'        => $username,
            'nickname'        => $username,
            'usermail'        => $usermail,
            'password'        => $pwd,
            'salt'            => $salt,
            'userip'          => $longip,
            'regtime'         => $time,
            'logins'          => 1,
            'last_login_time' => $time,
            'last_login_ip'   => $longip,
        );
        $uid = user_create($_user);
        $uid === false and message('usermail', '注册失败');
        $user = user_read($uid);


        $yq_uid   = param('yq_uid',0);

        $point_rule_data['ip'] = $longip;
if($yq_uid>0){
    point_rule('yaoqing',$yq_uid,$point_rule_data);//要传递ip，判断是否跟上个注册的ip是一个
}
        
        // 更新 session

        unset($_SESSION['user_create_email']);
        unset($_SESSION['user_create_code']);
        $_SESSION['uid'] = $uid;
        user_token_set($uid);

        $extra = array('token' => user_token_gen($uid), 'url' => '/');

   

       
       
    
        message(0, '注册成功', $extra);
    }

} elseif ($action == 'logout') {

    $uid             = 0;
    $_SESSION['uid'] = $uid;
    user_token_clear();

    message(0, '退出成功', array('url' => http_referer()));

// 重设密码第 1 步 | reset password first step
} elseif ($action == 'resetpw') {

    !$conf['user_resetpw_on'] and message(-1, '未开启密码找回功能！');

    if ($method == 'GET') {

        $header['title'] = '找回密码';

        include $conf['view_path'] . 'user_resetpw.html';

    } else if ($method == 'POST') {

        $email = param('email');
        empty($email) and message('email', '请输入邮箱');
        !is_email($email) and message('email', '邮箱格式错误');

        $_user = user_read_by_email($email);
        !$_user and message('email', '邮箱未被使用');

        $code = param('code');
        empty($code) and message('code', '请输入验证码');

        $sess_email = _SESSION('user_resetpw_email');
        $sess_code  = _SESSION('user_resetpw_code');
        empty($sess_code) and message('code', '验证码为空');
        empty($sess_email) and message('code', '记录邮箱为空');
        $email != $sess_email and message('code', '验证码错误');
        $code != $sess_code and message('code', '验证码错误');

        $_SESSION['resetpw_verify_ok'] = 1;

        message(0, '验证成功');
    }

// 重设密码第 3 步 | reset password step 3
} elseif ($action == 'resetpw_complete') {

    // 校验数据
    $email             = _SESSION('user_resetpw_email');
    $resetpw_verify_ok = _SESSION('resetpw_verify_ok');
    (empty($email) || empty($resetpw_verify_ok)) and message(-1, '非法请求');

    $_user = user_read_by_email($email);
    empty($_user) and message(-1, '邮箱不存在');
    $_uid = $_user['id'];

    if ($method == 'GET') {

        $header['title'] = '重置密码';

        include $conf['view_path'] . 'user_resetpw_complete.html';

    } else if ($method == 'POST') {

        $password = param('password');
        empty($password) and message('password', '请输入密码');

        $salt     = $_user['salt'];
        $password = md5($password . $salt);
        user_update($_uid, array('password' => $password));

        !is_password($password) and message('password', '密码错误');

        unset($_SESSION['user_resetpw_email']);
        unset($_SESSION['user_resetpw_code']);
        unset($_SESSION['resetpw_verify_ok']);

        message(0, '修改成功');

    }

// 发送验证码
} elseif ($action == 'send_code') {

    $method != 'POST' and message(-1, '发送方式错误');

    $action2 = param(3);

    // 创建用户
    if ($action2 == 'user_create') {

        $usermail = param('usermail');

        empty($usermail) and message('usermail', '邮箱为空');
        !is_email($usermail) and message('usermail', '邮箱格式错误');
        empty($conf['user_create_email_on']) and message(-1, '未开放邮箱验证');
        $_user = user_read_by_email($usermail);
        !empty($_user) and message('usermail', '该邮箱已被占用');

        $code                          = rand(100000, 999999);
        $_SESSION['user_create_email'] = $usermail;
        $_SESSION['user_create_code']  = $code;

        // 重置密码，往老地址发送
    } elseif ($action2 == 'user_resetpw') {

        $usermail = param('email');

        empty($usermail) and message('email', '邮箱为空');
        !is_email($usermail) and message('email', '邮箱格式错误');
        $_user = user_read_by_email($usermail);
        empty($_user) and message('email', '该邮箱未使用');

        empty($conf['user_resetpw_on']) and message(-1, '重置密码未启用');

        $code                           = rand(100000, 999999);
        $_SESSION['user_resetpw_email'] = $usermail;
        $_SESSION['user_resetpw_code']  = $code;

    } elseif ($action2 == 'user_bindmail') {
//绑定邮箱

        $usermail = param('usermail');

        empty($usermail) and message('usermail', '邮箱为空');
        !is_email($usermail) and message('usermail', '邮箱格式错误');
        $_user = user_read_by_email($usermail);
        if (!empty($_user) && $_user['id'] != $uid) {
            message('usermail', '该邮箱已被其他人占用');
        }

        $code                        = rand(100000, 999999);
        $_SESSION['user_bind_email'] = $usermail;
        $_SESSION['user_bind_code']  = $code;

    } else {
        message(-1, 'action2 error');
    }

    $subject = $conf['sitename'] . '提示您-验证码：' . $code . '，该验证码5分钟内有效。';
    $message = $subject;

    $smtplist = include DATA_PATH . 'config/smtp.conf.php';
    $n        = array_rand($smtplist);
    $smtp     = $smtplist[$n];

    $r = xn_send_mail($smtp, $conf['sitename'], $usermail, $subject, $message);

    if ($r === true) {
        message(0, '邮件发送成功，请注意查收');
    } else {
        xn_log($errstr, 'send_mail_error');
        message(-1, $errstr);
    }

} else {

}
