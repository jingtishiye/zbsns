<?php

!defined('DEBUG') and exit('Access Denied.');


($uid <= 0) and message(-1, '请先登录');
//还需要验证token之类的
if ($action == 'focus') {
//type关联目标类型0用户1帖子2话题3文档
    //关联数据表usersandother

    $type   = param(3, 0);
    $val    = param('val');
    
    $insert = array(
        'type' => $type,
        'uid'  => $uid,
        'did'  => $val,
    );
    $delete_message = '';
    $r              = db_find_one('usersandother', $insert);
    if ($r) {
        $myoprate = '-';
    } else {
        $myoprate = '+';
    }

    if ($type == 1) {
        $suc_message = '收藏成功';
        $err_message = '收藏失败';
        $topicinfo   = topic_read($val);
        $name        = $topicinfo['title'];
        if (!$topicinfo) {
            message(-1, '该帖子不存在');
        }
        $icon['qx']    = 'star-o';
        $icon['focus'] = 'star';
        $typename      = '帖子';

        db_update('topic',array('id'=>$val), array('sc' . $myoprate => 1));
        user_extend_update($topicinfo['uid'], array('focus_mytopic_num' . $myoprate => 1));
        user_extend_update($uid, array('focus_topic_num' . $myoprate => 1));

    }elseif ($type == 4) {
        $suc_message = '点赞成功';
        $err_message = '点赞失败';
        $commentinfo   = comment__read($val);
        $clearcontent = clearHtml(htmlspecialchars_decode($commentinfo['content']));
        $name        = $clearcontent;
        if (!$commentinfo) {
            message(-1, '该评论不存在');
        }
        $icon['qx']    = 'thumbs-o-up';
        $icon['focus'] = 'thumbs-up';
        $typename      = '评论';

    } elseif ($type == 2) {
        $icon['qx']     = 'plus';
        $icon['focus']  = 'minus';
        $topiccate_info = topiccate_read($val);
        $name           = $topiccate_info['name'];
        if (!$topiccate_info) {
            message(-1, '该话题不存在');
        }
        $typename    = '话题';
        $suc_message = '关注成功';
        $err_message = '关注失败';

        if ($topiccate_info['uid'] > 0) {
            user_extend_update($topiccate_info['uid'], array('focus_mycate_num' . $myoprate => 1));
        }
        topiccate_update($topiccate_info['id'], array('user_num' . $myoprate => 1));
        user_extend_update($uid, array('focus_cate_num' . $myoprate => 1));
    } elseif ($type == 3) {

        $suc_message = '收藏成功';
        $err_message = '收藏失败';
        $topicinfo   = doc_read($val);
        $name        = $topicinfo['title'];
        if (!$topicinfo) {
            message(-1, '该文档不存在');
        }
        $icon['qx']    = 'star-o';
        $icon['focus'] = 'star';
        $typename      = '文档';
        
        db_update('doccon',array('id'=>$val), array('sc' . $myoprate => 1));

        user_extend_update($topicinfo['uid'], array('focus_mydoc_num' . $myoprate => 1));
        user_extend_update($uid, array('focus_doc_num' . $myoprate => 1));
    } else {
        $typename    = '用户';
        $suc_message = '关注成功';
        $err_message = '关注失败';
        $userinfo    = user_read_cache($val);
        $name        = $userinfo['nickname'];
        if (!$userinfo) {
            message(-1, '该用户不存在');
        }
        if ($uid == $userinfo['id']) {
            message(-1, '自己关注自己是不是太自恋了');
        }
        $icon['qx']    = 'plus';
        $icon['focus'] = 'minus';
        user_extend_update($userinfo['id'], array('fensi_num' . $myoprate => 1));
        user_extend_update($uid, array('focus_user_num' . $myoprate => 1));

    }

    if ($r) {
if($type==4){
message(-1, '您已经赞过这个评论');
}else{
    $result         = db_delete('usersandother', array('id' => $r['id']));
        $delete_message = '取消';
        $extra['icon']  = $icon['qx'];
 
}
         

       
    } else {
        $insert['name']        = $name;
        $insert['create_time'] = $time;
        $result                = db_create('usersandother', $insert);
        $extra['icon']         = $icon['focus'];

        if ($type == 0) {

$subject = '<a href="' . r_url('user-' . $uid) . '">' . $user['nickname'] . '</a>关注了你';


send_message($val,$subject,$subject,'someone_focusme');
 



        }
        if ($type == 4) {

            comment__update($val, array('ding+'=>1));
        }
    }

    if ($result) {

        message(0, $typename . $delete_message . $suc_message, $extra);
    } else {
        message(-1, $typename . $delete_message . $err_message);
    }

} elseif ($action == 'sixin') {

    $touid = param('uid');
    $type  = param('type');

    $content = param('content');
    $touser = user_qx_cache($touid);
    if ($touser['extend']['notify']['sxtype'] == 0) {
        message(-1, '该用户拒绝接收私信');
    }
    $focususers = db_find_column('usersandother', array('type' => 0, 'uid' => $touid), 'did');
    if ($touser['extend']['notify']['sxtype'] == 2) {

        if (!in_array($uid, $focususers)) {
            message(-1, '该用户只允许接收关注人的私信');
        }
    }
    if ($touser['extend']['notify']['sxtype'] == 3) {

        $friendusers = db_find_column('usersandother', array('type' => 0, 'did' => $touid, 'uid' => $focususers), 'uid');

        if (!in_array($uid, $friendusers)) {
            message(-1, '该用户只允许接收好友的私信');
        }
    }

    $r = db_minid('message', 'create_time', array('uid' => array($uid, $touid), 'touid' => array($uid, $touid)));

    if ($r) {
        $flag = $r;
    } else {
        $flag = $time;
    }
    $insert = array(
        'type'        => $type,
        'uid'         => $uid,
        'touid'       => $touid,
        'create_time' => $time,
        'content'     => $content,
        'id_to_id'    => $flag,
    );
    if(intval($uid)<1&&$type==2){
         message(-1, '请先登录');
    }
    


    $result = db_create('message', $insert);

    if ($result) {

        message(0, '私信成功');
    } else {
        message(-1, '私信失败，请稍后再试');
    }
}elseif ($action == 'tixian') {

   $score = intval(param('score', 0));
   $rmb = floor((100-$conf['tixian']['bili'])*$score/(100*$conf['chongzhi']['bili']));
   if ($rmb < 1) {
        message(-1, '提现金额不足1元');
   }
   if ($score < 1) {
        message(-1, '提现积分不足1元');
   }
   if($score>$user['extend']['point']){
     message(-1, '提现积分超出已有数量');
   }
   $type = param('type', 1);
  
   $account = param('account', '');




   $insert       = array(
        'score'        => $score,
        'rmb'          => $rmb,
        'type'         => $type,
        'account'   => $account,
        'uid'          => $uid,
        'create_time'  => $time,
        'status'  => 0,
    );
    $result = db_create('tixian', $insert);
if ($result) {
    message(0, '提现申请成功');
}else{
    message(-1, '提现申请失败');

}

} elseif ($action == 'delmess') {

    $r  = db_update('message', array('touid' => $uid, 'type' => 2), array('status' => 2));
    $r1 = db_delete('message', array('touid' => $uid, 'type' => 1));

    $list = db_find_all('message', array('touid' => 0, 'type' => 1));

    foreach ($list as $key => $vo) {
        db_create('usersandother', array('uid' => $uid, 'type' => 4, 'did' => $vo['id'], 'create_time' => $time, 'status' => 1, 'name' => $vo['content']));
    }
    delete_user_mess($uid,true);
    message(0, '消息已全部清空', array('url' => r_url('user-notification')));
} elseif ($action == 'readmess') {
    $id       = param('id');
    $info     = db_find_one('message', array('id' => $id));
    $type     = param('type');
    $mess_uid = param('uid');

    if ($type == 1) {
        //系统消息
        if ($info['touid'] == $uid) {
            db_delete('message', array('id' => $id));
        } else {
            db_create('usersandother', array('uid' => $uid, 'type' => 4, 'did' => $id, 'create_time' => $time, 'status' => 1, 'name' => $info['content']));
        }
    }

    if ($type == 2) {
        //私信更新两人所有对话为已读
        $r = db_update('message', array('uid' => $mess_uid, 'touid' => $uid, 'type' => 2), array('status' => 2));
    }
     delete_user_mess($uid);
    message(0, '消息已标记已读');

}
