<?php

!defined('DEBUG') and exit('Access Denied.');

include PUBLIC_MODEL_PATH . 'pay.model.php';
($uid <= 0) and message(-1, '请先登录');
if ($action == 'dashang') {

    $pay_method = param('pay_method');

    $rmb     = intval(param('score'));
    $content = param('content');

    $id   = param('id');
    $info = topic_read($id);
    empty($info) and message(-1, '帖子不存在');

    if (empty($pay_method)) {
        message(-1, '请选择支付方式');
    }
    if ($rmb < 1) {
        message(-1, '怎么的也要打赏点吧');
    }
    $score = $conf['chongzhi']['bili'] * $rmb;

    if ($pay_method == 'caifu') {

        if ($user['extend']['point'] < $score) {
            message(-1, '余额不足');
        }

        $pointdata['uid']         = $uid;
        $pointdata['to_uid']      = $info['uid'];
        $pointdata['description'] = '打赏';
        $pointdata['itemid']      = $id;

        point_note_op(6, $conf['chongzhi']['bili'] * $score, 'point', '-', $pointdata);
        if (!empty($content)) {
            send_sys_message($info['uid'], '用户' . $user['nickname'] . '在帖子《' . $info['title'] . '》打赏了你，并给你留言：</br>' . $content);
        } else {
            send_sys_message($info['uid'], '用户' . $user['nickname'] . '在帖子《' . $info['title'] . '》打赏了你');
        }
        message(0, '打赏成功');
    } else {
        $extra['uid']        = $info['uid'];
        $extra['actiontype'] = 2;
        $extra['itemid']     = $id;
        $extra['score']      = $score;
        $extra['rmb']        = $rmb;
        $extra['errorcode']  = $content;
        $url                 = pay($pay_method, '帖子打赏', $extra);
        if (!empty($url)) {
            message(0, '跳转支付页面', array('html' => $url,'method'=>$pay_method));
        } else {
            message(-1, '支付失败');
        }

    }

} elseif ($action == 'chongzhi') {
    $pay_method = param('pay_method');

    if (empty($pay_method)) {
        message(-1, '请选择支付方式');
    }
    $chongzhi = param('chongzhi', 1);
    if (intval($chongzhi) < 1) {
        message(-1, '最少充值1元');
    }
    $chongzhi            = intval($chongzhi);
    $extra['uid']        = $uid;
    $extra['actiontype'] = 1;
    $extra['itemid']     = 0;
    $extra['score']      = $conf['chongzhi']['bili'] * $chongzhi;
    $extra['rmb']        = $chongzhi;

    $url = pay($pay_method, '积分充值', $extra);

    if (!empty($url)) {
        message(0, '跳转支付页面', array('html' => $url,'method'=>$pay_method));
    } else {
        message(-1, '支付失败');
    }

} elseif ($action == 'pay_return_url') {
    $out_trade_no = param('out_trade_no');
    //支付宝交易号

    $trade_no = param('trade_no');

    //交易状态
    $trade_status        = param('trade_status');
    $map['out_trade_no'] = $out_trade_no;
    $info                = db_find_one('chongzhi', $map);
    

        $verify_result = return_check($info['type'],false);

        if ($verify_result) {

            if ($info['status'] != 1) {

                $data['status']      = 1;
                $data['trade_no']    = $trade_no;
                $data['update_time'] = $time;
                db_update('chongzhi', $map, $data);

                if ($info['actiontype'] == 2) {
                    $info                     = topic_read($info['itemid']);
                    $pointdata['uid']         = $info['uid'];
                    $pointdata['to_uid']      = $uid;
                    $pointdata['description'] = '打赏';
                    $pointdata['itemid']      = $info['id'];

                    point_note_op(6, $info['score'], 'point', '+', $pointdata);

                    if (!empty($info['errorcode'])) {
                        send_sys_message($info['uid'], '用户' . $user['nickname'] . '在帖子《' . $info['title'] . '》打赏了你，并给你留言：</br>' . $info['errorcode']);
                    } else {
                        send_sys_message($info['uid'], '用户' . $user['nickname'] . '在帖子《' . $info['title'] . '》打赏了你');
                    }

                } else {
                    $pointdata['description'] = '充值';
                    $pointdata['uid']         = $uid;
                    $pointdata['to_uid']      = 0;

                    point_note_op(4, $info['score'], 'point', '+', $pointdata);
                }
            }

            echo "success";
            if ($info['actiontype'] == 1) {
                http_location(r_url('user-' . $uid));
            }
            if ($info['actiontype'] == 2) {
                http_location(r_url('thread-' . $info['itemid']));
            }
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败

            $data['trade_no']    = $trade_no;
            $data['errorcode']   = param('trade_status');
            $data['update_time'] = $time;
            db_update('chongzhi', $map, $data);

            echo "fail";

        }
    
} elseif ($action == 'pay_notify_url') {
 //file_put_contents('notify.txt', "收到来自微信的异步通知\r\n", FILE_APPEND);
    $out_trade_no = param('out_trade_no');
    //支付宝交易号

    $trade_no = param('trade_no');

    //交易状态
    $trade_status        = param('trade_status');
    $map['out_trade_no'] = $out_trade_no;
    $info                = db_find_one('chongzhi', $map);
    

        $verify_result = return_check($info['type'],true);


        if ($verify_result) {
//验证成功

            if ($info['status'] != 1) {

                $data['status']      = 1;
                $data['trade_no']    = $trade_no;
                $data['update_time'] = $time;
                db_update('chongzhi', $map, $data);

                if ($info['actiontype'] == 2) {
                    $info                     = topic_read($info['itemid']);
                    $pointdata['uid']         = $info['uid'];
                    $pointdata['to_uid']      = $uid;
                    $pointdata['description'] = '打赏';
                    $pointdata['itemid']      = $info['id'];

                    point_note_op(6, $info['score'], 'point', '+', $pointdata);
                } else {
                    $pointdata['description'] = '充值';
                    $pointdata['uid']         = $uid;
                    $pointdata['to_uid']      = 0;

                    point_note_op(4, $info['score'], 'point', '+', $pointdata);
                }

            }

            echo "success";
            if ($info['actiontype'] == 1) {
                http_location(r_url('user-' . $uid));
            }
            if ($info['actiontype'] == 2) {
                http_location(r_url('thread-' . $info['itemid']));
            }
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败

            $data['trade_no']    = $trade_no;
            $data['errorcode']   = param('trade_status');
            $data['update_time'] = $time;
            db_update('chongzhi', $map, $data);

            echo "fail";

        }
   
}