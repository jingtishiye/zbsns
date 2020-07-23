<?php

$g_static_users   = array();
$g_static_users_qx = array();
$user_mess_count = array();
// ------------> 最原生的 CURD，无关联其他数据。

function user__create($arr)
{

    $r = db_insert('user', $arr);
    if ($r !== false) {
        db_insert('user_extend', array('uid' => $r));
    }
    return $r;
}

function user__update($uid, $update)
{

    $r = db_update('user', array('id' => $uid), $update);
    return $r;

}
function user_extend_update($uid, $arr)
{
    $r = db_update('user_extend', array('uid' => $uid), $arr);
    return $r;
}


function user__read($uid)
{
    $user           = db_find_one('user', array('id' => $uid));
    $user['extend'] = db_find_one('user_extend', array('uid' => $uid));
    return $user;
}

function user__delete($uid)
{

    $r = db_delete('user', array('id' => $uid));
    db_delete('user_extend', array('uid' => $uid));
    return $r;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function user_create($arr)
{

    global $conf;
    $r = user__create($arr);

    return $r;
}

function user_update($uid, $arr)
{

    global $conf, $g_static_users;

    if(!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))) {
    	cache_delete("user-$uid");
    	cache_delete("user-qx-$uid");
    }
    isset($g_static_users[$uid]) and $g_static_users[$uid] = array_merge($g_static_users[$uid], $arr);

    $arr1 = array('doc_num', 'topic_num', 'mailstatus', 'mobilestatus', 'grades', 'gradename', 'focus_cate_num', 'focus_topic_num', 'focus_doc_num', 'focus_user_num', 'fensi_num', 'expoint3', 'expoint2', 'expoint1', 'point', 'keywords');

    foreach ($arr as $key => $value) {
        if (in_array($key, $arr1)) {
            $arr['extend'][$key] = $value;
            unset($arr[$key]);
        }
    }
    $r1 = 0;

    if (!empty($arr['extend'])) {

        $r1 = user_extend_update($uid, $arr['extend']);
        unset($arr['extend']);
    }
    $r = user__update($uid, $arr);

   
    return $r + $r1;
}

function user_read($uid)
{
    global $g_static_users;
    if (empty($uid)) {
        return array();
    }

    $uid  = intval($uid);
    $user = user__read($uid);
    user_format($user);
    $g_static_users[$uid] = $user;
    return $user;
}
function mess_count($uid){
$hasreadlist=db_find_column('usersandother',array('type'=>4,'uid'=>$uid),'did');
 
$arr2 =  db_find_column('message',array('status'=>1,'touid'=>array($uid,0),'type'=>1,'id'=>array('!='=>$hasreadlist)),'id');

if($arr2){
$where   = array('id'=>$arr2);
}else{
$where   = array('status'=>0,'type'=>1);
}
$count1 = db_count('message',$where);


$wheren   = array('status' => 1, 'touid' => $uid,'type'=>2); 
$count2 = db_count('message',$wheren);

return $count1+$count2;

}
function delete_user_mess($uid,$all=false){
     global $conf,$user_mess_count;

     if($all){
        if (isset($user_mess_count[$uid])) {
            $user_mess_count[$uid] = 0;
       }
     }else{
        if (isset($user_mess_count[$uid])) {
            $user_mess_count[$uid] = $user_mess_count[$uid]-1;
        }
     }
    if (!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))) {
            cache_set("user_mess_count-$uid", $user_mess_count[$uid]);
     }

}
function user_mess_count($uid){
   global $conf,$user_mess_count;
    if (isset($user_mess_count[$uid])) {
        return $user_mess_count[$uid];
    }
    if (empty($uid)) {
        return 0;
    }
    if (!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))) {
        $r = cache_get("user_mess_count-$uid");
        if ($r === null) {
            $r = mess_count($uid);
            cache_set("user_mess_count-$uid", $r);
        }
    } else {
        $r = mess_count($uid);
    }
    $user_mess_count[$uid] = $r ? $r : 0;

    return $r;

}
// 从缓存中读取，避免重复从数据库取数据，主要用来前端显示，可能有延迟。重要业务逻辑不要调用此函数，数据可能不准确，因为并没有清理缓存，针对 request 生命周期有效。
function user_read_cache($uid)
{
    global $conf, $g_static_users;
    if (isset($g_static_users[$uid])) {
        return $g_static_users[$uid];
    }

    // 游客
    if ($uid == 0) {
        return array();
    }

    if (!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))) {
        $r = cache_get("user-$uid");
        if ($r === null) {
            $r = user_read($uid);
            cache_set("user-$uid", $r);
        }
    } else {
        $r = user_read($uid);
    }

    $g_static_users[$uid] = $r ? $r : array();

    return $g_static_users[$uid];
}

function user_delete($uid)
{
    global $conf, $g_static_users;

    $user = user_read($uid);
    if (empty($user)) {
        return null;
    }

    // 删除头像
    $user['avatar_path'] and xn_unlink($user['avatar_path']);

    $r = user__delete($uid);

    if(!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))){
    	cache_delete("user-$uid");
    	cache_delete("user-qx-$uid");
    }

    if (isset($g_static_users[$uid])) {
        unset($g_static_users[$uid]);
    }
    if (isset($g_static_users_qx[$uid])) {
        unset($g_static_users_qx[$uid]);
    }
    return $r;
}

function user_find($cond = array(), $orderby = array(), $page = 1, $pagesize = 20)
{
    global $g_static_users;

    $userlist = db_find('user', $cond, $orderby, $page, $pagesize);

    if ($userlist) {
        foreach ($userlist as &$user) {

            $user['extend'] = db_find_one('user_extend', array('uid' => $user['id']));
            user_format($user);
            $g_static_users[$user['id']] = $user;

        }
    }

    return $userlist;
}

// ------------> 其他方法

function user_read_by_email($email)
{
    global $g_static_users;

    $user = db_find_one('user', array('usermail' => $email));

    user_format($user);
    $g_static_users[$user['id']] = $user;

    return $user;
}
function user_read_by_nickname($nickname)
{
    global $g_static_users;

    $user = db_find_one('user', array('nickname' => $nickname));

    user_format($user);
    $g_static_users[$user['id']] = $user;

    return $user;
}
function user_read_by_username($username)
{
    global $g_static_users;

    $user = db_find_one('user', array('username' => $username));

    user_format($user);
    $g_static_users[$user['id']] = $user;

    return $user;
}

function user_count($cond = array())
{

    $n = db_count('user', $cond);

    return $n;
}

function user_maxid($cond = array())
{

    $n = db_maxid('user', 'id');

    return $n;
}
function up_usergrade($user)
{
    global $conf, $g_static_users;


    $nowtime = time();

    $updata  = array();
    $upgrade = db_find_one('usergrade', array('gmtype' => 2, 'status' => 1, 'score' => array('<=' => $user['extend']['expoint1'])), array('score' => -1));
    if ($upgrade['id'] != $user['extend']['up_grades']) {
        //升级会员信息

        $updata += array('up_grades' => $upgrade['id'], 'up_grades_type' => $upgrade['type'], 'up_grades_bili' => $upgrade['bili'], 'up_grades_name' => $upgrade['name'], 'up_grades_limittime' => $upgrade['limittime'], 'up_grades_time' => $nowtime);

    }
    if ($user['extend']['grades'] > 0) {

        if ($user['extend']['grades_days'] > 0) {
//大于0才有比较的意义，否则是不限制天数
            $days = floor(($nowtime - $user['extend']['grades_time']) / 86400);

            if ($user['extend']['grades_days'] <= $days) {
                $updata += array('grades' => 0);
            } else {
                $updata += array('grades_days-' => $days);
            }
        }

    }

    if (!empty($updata)) {
        user_extend_update($user['id'], $updata);

        $user['extend'] = array_merge($user['extend'], $updata);

        if(!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))){

        	cache_delete("user-".$user['id']);
        	cache_delete("user-qx-".$user['id']);
        } 
    }
    return $user;

}

function user_qx($uid){
	global $g_static_users;
    if (empty($uid)) {
        return array();
    }

    $uid  = intval($uid);
    $user['extend'] = db_find_one('user_extend', array('uid' => $uid));
    $user = user_extend_format($user);

    $g_static_users_qx[$uid] = $user;
    return $user;
}
function user_qx_cache($uid){
	global $conf, $g_static_users_qx;
    if (isset($g_static_users_qx[$uid])) {
        return $g_static_users_qx[$uid];
    }


    if (!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))) {
        $r = cache_get("user-qx-$uid");
        if ($r === null) {
            $r = user_qx($uid);
            cache_set("user-qx-$uid", $r);
        }
    } else {
        $r = user_qx($uid);
    }

    $g_static_users_qx[$uid] = $r ? $r : array();

    return $g_static_users_qx[$uid];
}
function user_extend_format($user)
{

    $user['extend']['notify'] =
    array(
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
        'mail_someone_focusme'     => 0,
    );

    if (!empty($user['extend']['notify_set'])) {

        $user['extend']['notify'] = xn_json_decode($user['extend']['notify_set']) + $user['extend']['notify'];

    }

    $user['quanxian']               = array();
    $user['quanxian_limit_topic']   = 0;
    $user['quanxian_limit_doc']     = 0;
    $user['quanxian_limit_pinglun'] = 0;
    $user['quanxian_limit_download'] = 0;
    $user['bili']                   = '';
    $user['usergradename']          = '';
    $user['days']                   = 0;
    $user['nums']                   = 0;
    $user['gradeid']                = 0;

    if ($user['extend']['up_grades'] > 0) {

        $user['quanxian'] = explode(',', $user['extend']['up_grades_type']); //得到权限数组
        $limitarr         = explode(',', $user['extend']['up_grades_limittime']); //得到权限时间限制数组
        $user['bili']     = $user['extend']['up_grades_bili'];
        $user['gradeid']  = $user['extend']['up_grades'];

        $user['quanxian_limit_topic']   = $limitarr[0];
        $user['quanxian_limit_doc']     = $limitarr[1];
        $user['quanxian_limit_pinglun'] = $limitarr[2];
        $user['quanxian_limit_download'] = $limitarr[3];
        $user['usergradename']          = $user['extend']['up_grades_name'];

    }
    if ($user['extend']['grades'] > 0) {

        $grades_type_arr  = explode(',', $user['extend']['grades_type']);
        $user['quanxian'] = array_merge(array_diff($user['quanxian'], $grades_type_arr), $grades_type_arr);
        $user['gradeid']  = $user['extend']['grades'];
        $limitarr         = explode(',', $user['extend']['grades_limittime']);
        if ($user['quanxian_limit_topic'] > $limitarr[0]) {
            $user['quanxian_limit_topic'] = $limitarr[0];
        }
        if ($user['quanxian_limit_doc'] > $limitarr[1]) {
            $user['quanxian_limit_doc'] = $limitarr[1];
        }
        if ($user['quanxian_limit_pinglun'] > $limitarr[2]) {
            $user['quanxian_limit_pinglun'] = $limitarr[2];
        }
        if ($user['quanxian_limit_download'] > $limitarr[3]) {
            $user['quanxian_limit_download'] = $limitarr[3];
        }
        if ($user['extend']['grades_bili'] < $user['bili']) {
            $user['bili'] = $user['extend']['grades_bili'];
            $user['days'] = $user['extend']['grades_days'];
            $user['nums'] = $user['extend']['grades_nums'];
        }
        $user['usergradename'] = $user['extend']['grades_name'];

    }


    return $user;
}
function user_format(&$user)
{
    global $conf, $grouplist;
    if (empty($user)) {
        return;
    }

    $user['create_ip_fmt']   = long2ip(intval($user['userip']));
    $user['create_date_fmt'] = empty($user['regtime']) ? '0000-00-00' : date('Y-m-d', $user['regtime']);
    $user['login_ip_fmt']    = long2ip(intval($user['last_login_ip']));
    $user['login_date_fmt']  = empty($user['last_login_time']) ? '0000-00-00' : date('Y-m-d', $user['last_login_time']);

    $dir = substr(sprintf("%09d", $user['id']), 0, 3);

    $user['avatar_url']  = $user['avatar'] ? $conf['web_url'] .'/'. $conf['upload_url'] . "avatar/$dir/$user[id].png?" . $user['avatar'] : $conf['web_url'] .'/'. 'public/common/images/avatar.png';
    $user['avatar_path'] = $user['avatar'] ? $conf['upload_path'] . "avatar/$dir/$user[id].png?" . $user['avatar'] : '';

    $user['online_status'] = 1;

    if ($user['sex'] == 1) {
        $user['sexname'] = '男';
    } elseif ($user['sex'] == 2) {
        $user['sexname'] = '女';
    } else {
        $user['sexname'] = '保密';
    }
    if (empty($user['description'])) {
        $user['description'] = '这个家伙很懒，什么都没有留下';
    }
    if (!empty($user['extend']['keywords'])) {
        $keywords_arr = explode(',', $user['extend']['keywords']);

        $user['keywords_arr'] = topiccate_find(array('name' => $keywords_arr), array('num' => -1), 1, count($keywords_arr));

    }
}



function user_find_by_uids($uids)
{

    $uids = trim($uids);
    if (empty($uids)) {
        return array();
    }

    $arr = explode(',', $uids);
    $r   = array();
    foreach ($arr as $_uid) {
        $user = user_read_cache($_uid);
        if (empty($user)) {
            continue;
        }

        $r[$user['uid']] = $user;
    }
    return $r;
}

// 获取用户安全信息
function user_safe_info($user)
{

    unset($user['password']);
    unset($user['usermail']);
    unset($user['salt']);
    unset($user['mobile']);
    unset($user['create_ip']);
    unset($user['create_ip_fmt']);
    unset($user['create_date']);
    unset($user['create_date_fmt']);
    unset($user['login_ip']);
    unset($user['login_date']);
    unset($user['login_ip_fmt']);
    unset($user['login_date_fmt']);
    unset($user['logins']);
    return $user;
}

// 用户
function user_token_get()
{
    global $time;
    $_uid = user_token_get_do();

    if (!$_uid) {
        //setcookie('bbs_token', '', $time - 86400, '');
    }

    return $_uid;
}

// 用户
function user_token_get_do()
{
    global $time, $ip, $conf;
    $token = param('5isns_token');

    if (empty($token)) {
        return false;
    }

    $tokenkey = md5(xn_key());
    $s        = xn_decrypt($token, $tokenkey);
    if (empty($s)) {
        return false;
    }

    $arr = explode("\t", $s);
    if (count($arr) != 4) {
        return false;
    }

    list($_ip, $_time, $_uid, $_pwd) = $arr;

    // 检查密码是否被修改。
    if ($time - $_time > 1800) {
        $user = user_read($_uid);
        if (empty($user)) {
            return 0;
        }

        if (md5($user['password']) != $_pwd) {
            return 0;
        }
    }

    return $_uid;
}

// 设置 token，防止 sid 过期后被删除
function user_token_set($uid)
{
    global $time, $conf;
    if (empty($uid)) {
        return;
    }

    $token = user_token_gen($uid);
    setcookie('5isns_token', $token, $time + 8640000, $conf['cookie_path']);

}

function user_token_clear()
{
    global $time, $conf;
    setcookie('5isns_token', '', $time - 8640000, $conf['cookie_path']);

}

function user_token_gen($uid)
{
    global $ip, $time, $conf;

    $user     = user_read($uid);
    $pwd      = md5($user['password']);
    $tokenkey = md5(xn_key());
    $token    = xn_encrypt("$ip	$time	$uid	$pwd", $tokenkey);

    return $token;
}

// 前台登录验证
function user_login_check()
{
    global $user;

    empty($user) and http_location(r_url('user-login'));

}

// 获取用户来路
function user_http_referer()
{

    $referer                     = param('referer'); // 优先从参数获取 | GET is priority
    empty($referer) and $referer = array_value($_SERVER, 'HTTP_REFERER', '');

    $referer = str_replace(array('\"', '"', '<', '>', ' ', '*', "\t", "\r", "\n"), '', $referer); // 干掉特殊字符 strip special chars

    if (
        !preg_match('#^(http|https)://[\w\-=/\.]+/[\w\-=.%\#?]*$#is', $referer)
        || strpos($referer, 'user-login.htm') !== false
        || strpos($referer, 'user-logout.htm') !== false
        || strpos($referer, 'user-create.htm') !== false
        || strpos($referer, 'user-setpw.htm') !== false
        || strpos($referer, 'user-resetpw_complete.htm') !== false
               || strpos($referer, 'user/login.htm') !== false
        || strpos($referer, 'user/logout.htm') !== false
        || strpos($referer, 'user/create.htm') !== false
        || strpos($referer, 'user/setpw.htm') !== false
        || strpos($referer, 'user/resetpw_complete.htm') !== false
               || strpos($referer, 'c=user&a=login.htm') !== false
        || strpos($referer, 'c=user&a=logout.htm') !== false
        || strpos($referer, 'c=user&a=create.htm') !== false
        || strpos($referer, 'c=user&a=setpw.htm') !== false
        || strpos($referer, 'c=user&a=resetpw_complete.htm') !== false
    ) {
        $referer = './';
    }

    return $referer;
}

function user_auth_check($token)
{
    global $time;
    $auth = param(2);
    $s    = decrypt($auth);
    empty($s) and message(-1, '解密失败');
    $arr = explode('-', $s);
    count($arr) != 3 and message(-1, '解密失败');
    list($_ip, $_time, $_uid) = $arr;
    $_user                    = user_read($_uid);
    empty($_user) and message(-1, '用户不存在');
    $time - $_time > 3600 and message(-1, '链接已经过期');
    return $_user;
}
function andother_find_arr($tablename = 'usersandother', $type, $id, $key = 'uid', $name, $map = false)
{
    $arr = db_find_all($tablename, array('type' => $type, $key => $id), '', '', $name);
    if ($map) {

        return array_map(function ($array) use ($name) {return $array[$name];}, $arr);

    } else {
        return $arr;
    }

}
