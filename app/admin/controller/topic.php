<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');

if (empty($action) || $action == 'list') {

    $page  = param('page', 0);
    $title = param('title', '');

    $where = array('status' => array('>=' => 0));

    if (!empty($title)) {

        $where['title'] = array('LIKE' => $title);

    }

    if ($page > 0) {
        $where = cache_get('last_topic_search');
    } else {
        cache_set('last_topic_search', $where);
        $page = 1;
    }
    $pagenum    = $conf['pagesize'];
    $topicslist = topic_find($where, array('create_time' => -1), $page, $pagenum);
    $totalnum   = topic_count($where);
    $pagination = pagination(r_url('topic-list', array('page' => 'pagenum')), $totalnum, $page, $pagenum);
    include ADMIN_PATH . "view/topic_list.html";

} else if ($action == 'add') {
    global $uid, $time, $conf;
    if ($method == 'POST') {

        $dataarr   = param_post('');
        $filelist  = param('filelist', array());
        $scorelist = param('filescore', array());
        unset($dataarr['filelist']);
        unset($dataarr['filescore']);

        if ($dataarr['score'] < 0) {
            $dataarr['score'] = 0;
            $dataarr['free']  = 0;
        }
        $checkarr = array(
            'title'   => array(array('empty', '标题为空'), array('length', '标题在6到255个字符之间', array(6, 255))),
            'content' => array(array('empty', '内容为空')),
        );

        $r = wi_check_field('topic', $dataarr, $checkarr, 'add');

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        $r[1]['create_time'] = $time;
        $r[1]['uid']         = $uid;
        $r[1]['description'] = wi_getSummary($r[1]['content']);

        $result = topic_create($r[1]);
        if ($result) {

            if (!empty($r[1]['keywords'])) {
                topiccate_add_from_keywords($r[1]['keywords'], $result, 'add', 1);
            }

            user_extend_update($uid, array('topic_num+' => 1));

            thread_attach_post($result, $filelist, $scorelist);

            $focususers = db_find_column('usersandother', array('type' => 0, 'did' => $uid), 'uid'); //得到所有关注该用户的用户
            if (!empty($focususers)) {

                $subject      = '你关注的用户发布了帖子《<a href="' . r_url('thread-' . $result) . '">' . $r[1]['title'] . '</a>》';
               

                foreach ($focususers as $key => $value) {

                    send_message($value, $subject, $subject, 'new_user_content');

                }
            }

            message(0, '添加成功');
        } else {
            message(-1, '添加失败');
        }

    } else {
        $taglist       = topiccate_find(array('status' => 1), array('sort' => '-1'), 1, 20);
        $input['free'] = form_radio('free', array('0' => '免费', '1' => '付费', '2' => '部分内容付费'), 0);
        $filetypes     = include DATA_PATH . 'config/attach.conf.php';

        include ADMIN_PATH . "view/topic_add.html";
    }
} else if ($action == 'edit') {
    $id   = param('id');
    $info = topic_read($id);
    if ($method == 'POST') {

        $dataarr   = param_post('');
        $filelist  = param('filelist', array());
        $scorelist = param('filescore', array());
        unset($dataarr['filelist']);
        unset($dataarr['filescore']);

        if ($dataarr['score'] < 0) {
            $dataarr['score'] = 0;
            $dataarr['free']  = 0;
        }

        $checkarr = array(
            'title'   => array(array('empty', '标题为空'), array('length', '标题在6到255个字符之间', array(6, 255))),
            'content' => array(array('empty', '内容为空')),
        );

        $r = wi_check_field('topic', $dataarr, $checkarr, 'edit', 'id', $info);

        if (!$r[0]) {
            message(-1, $r[1], array('key' => $r[2]));
        }
        
        if (!empty($r[1])) {
            $r[1]['update_time'] = $time;
            if (!empty($r[1]['content'])) {
                $r[1]['description'] = wi_getSummary($r[1]['content']);
            }

            $result = topic_update($id, $r[1]);
            if ($result) {
                if (!empty($r[1]['keywords'])) {
                    topiccate_add_from_keywords($r[1]['keywords'], $id, 'edit', 1);
                }
                thread_attach_post($id, $filelist, $scorelist, 'edit');
                message(0, '编辑成功');
            } else {
                message(-1, '编辑失败');
            }
        } else {
            message(-1, '未有数据被更改');
        }

    } else {
        $input['free'] = form_radio('free', array('0' => '免费', '1' => '付费', '2' => '部分内容付费'), $info['free']);
        $filetypes     = include DATA_PATH . 'config/attach.conf.php';

        $taglist = topiccate_find(array('status' => 1), array('sort' => '-1'), 1, 20);

        include ADMIN_PATH . "view/topic_edit.html";
    }
} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = topic_update($id, [$field => $value, 'update_time' => $time]);
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
        $status     = 0;
        $statusname = '待审';
    } else {
        $status     = 1;
        $statusname = '审核通过';
    }
    $topicinfo = topic_read($id);

    $subject      = '你的帖子《' . $topicinfo['title'] . '》' . $statusname . '了&nbsp;&nbsp;';
    $mail_subject = $subject;

    send_message($topicinfo['uid'], $subject, $mail_subject, 'content_operate');

    $result = topic_update($id, ['status' => $status, 'update_time' => $time]);
    if ($result) {

        message(0, '操作成功', array('val' => $status));

    } else {
        message(-1, '操作失败');
    }

} else if ($action == 'delete') {
    $id     = param('id');
    $result = topic_update($id, ['status' => -1, 'update_time' => $time]);
    if ($result) {

        $topicinfo    = topic_read($id);
        $subject      = '你的帖子《' . $topicinfo['title'] . '》被删除了&nbsp;&nbsp;';
        $mail_subject = $subject;

        send_message($topicinfo['uid'], $subject, $mail_subject, 'content_operate');

        message(0, '删除成功');

    } else {
        message(-1, '删除失败');
    }

}
