<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');

include PUBLIC_MODEL_PATH.'smtp.model.php';
$smtplist = smtp_init(DATA_PATH.'config/smtp.conf.php');

 $menu['setting'] = array(
        'url'=>r_url('config-setting'), 
        'text'=>'配置', 
        'icon'=>'fa-cog', 
        'tab'=> array (
            'base'=>array('url'=>r_url('config-setting'), 'text'=>'基础配置'),
            'smtp'=>array('url'=>r_url('config-smtp'), 'text'=>'邮箱配置'),
            'chongzhi'=>array('url'=>r_url('config-chongzhi'), 'text'=>'支付配置'),
           )
       );
if (empty($action) || $action == 'setting') {
    if($method == 'GET') {
if(empty($conf['online_trans_num'])){
    $conf['online_trans_num']=0;
}
        $input = array();
        $input['sitename'] = form_text('sitename', $conf['sitename']);
        $input['sitebrief'] = form_textarea('sitebrief', $conf['sitebrief'], '100%', 100);
        $input['beinum'] = form_text('beinum', $conf['beinum']);
        $input['web_url'] = form_text('web_url', $conf['web_url']);
        $input['appid'] = form_text('appid', $conf['appid']);
        $input['allow_cate_show'] = form_text('allow_cate_show', $conf['allow_cate_show']);
        $input['choose_cate_num'] = form_text('choose_cate_num', $conf['choose_cate_num']);

        $input['shuiyin'] = form_text('shuiyin', $conf['shuiyin']);
        $input['url_rewrite_on'] = form_radio('url_rewrite_on', array('0'=>'关闭','1'=>'打开'),$conf['url_rewrite_on']);
 $input['no_img_index'] = form_radio('no_img_index', array('0'=>'无图首页','1'=>'有图首页'),$conf['no_img_index']);

        $input['sy_type'] = form_radio('sy_type', array('0'=>'关闭','1'=>'文字','2'=>'图片'),$conf['sy_type']);
        $input['user_create_on'] = form_radio_yes_no('user_create_on', $conf['user_create_on']);
        $input['user_create_email_on'] = form_radio_yes_no('user_create_email_on', $conf['user_create_email_on']);
        $input['user_resetpw_on'] = form_radio_yes_no('user_resetpw_on', $conf['user_resetpw_on']);
        $input['online_trans'] = form_radio_yes_no('online_trans', $conf['online_trans']);
        $input['cdn_use'] = form_radio_yes_no('cdn_use', $conf['cdn_use']);
     
        
        
        include ADMIN_PATH.'view/config_setting.html';
        
    } else {
        
        $sitebrief = param('sitebrief', '', FALSE);
        $sitename = param('sitename', '', FALSE);
        $beinum = param('beinum', '', FALSE);
        $web_url = param('web_url', '', FALSE);
        $appid = param('appid', '', FALSE);
       
     
$allow_cate_show = param('allow_cate_show', 0);
$choose_cate_num = param('choose_cate_num', 0);

        $no_img_index = param('no_img_index', 0);

        $shuiyin = param('shuiyin', '', FALSE);
        $sy_type = param('sy_type', 0);
        $url_rewrite_on = param('url_rewrite_on', 0);
        $user_create_on = param('user_create_on', 0);
        $user_create_email_on = param('user_create_email_on', 0);
        $user_resetpw_on = param('user_resetpw_on', 0);
        $online_trans = param('online_trans', 0);
        $cdn_use = param('cdn_use', 0);


        $replace = array();
        $replace['sitename'] = $sitename;
        $replace['beinum'] = $beinum;
        $replace['web_url'] = $web_url;
        $replace['appid'] = $appid;
        $replace['no_img_index'] = $no_img_index;
        $replace['shuiyin'] = $shuiyin;
        $replace['sy_type'] = $sy_type;
        $replace['url_rewrite_on'] = $url_rewrite_on;

        $replace['sitebrief'] = $sitebrief;
        $replace['user_create_on'] = $user_create_on;
        $replace['user_create_email_on'] = $user_create_email_on;
        $replace['user_resetpw_on'] = $user_resetpw_on;
        $replace['online_trans'] = $online_trans;
        $replace['cdn_use'] = $cdn_use;
         $replace['allow_cate_show'] = $allow_cate_show;
          $replace['choose_cate_num'] = $choose_cate_num;
        
        file_replace_var(DATA_PATH.'config/conf.default.php', $replace);
       
        message(0, '修改成功');
    }
}if ($action == 'chongzhi') {
    if($method == 'GET') {

        $input = array();
        $input['paymethod'] = form_radio('paymethod', array('0'=>'关闭','1'=>'支付宝'),$conf['paymethod']);
        
        $input['alipay']['app_id'] = form_text('alipay_app_id', $conf['alipay']['app_id']);
        $input['alipay']['public_key'] = form_text('alipay_public_key', $conf['alipay']['public_key']);
        $input['alipay']['private_key'] = form_text('alipay_private_key', $conf['alipay']['private_key']);


        $input['wechat']['mch_id'] = form_text('wechat_mch_id', $conf['wechat']['mch_id']);
$input['wechat']['app_id'] = form_text('wechat_app_id', $conf['wechat']['app_id']);
$input['wechat']['mch_key'] = form_text('wechat_mch_key', $conf['wechat']['mch_key']);

        $input['chongzhi']['bili'] = form_text('czbili', $conf['chongzhi']['bili']);
        $input['tixian']['bili'] = form_text('txbili', $conf['tixian']['bili']);
        
        include ADMIN_PATH.'view/config_chongzhi.html';
        
    } else {
        
        $czbili = param('czbili', '', FALSE);
        $txbili = param('txbili', '', FALSE);
        $paymethod = param('paymethod', 0);
        $alipay_public_key = param('alipay_public_key', '', FALSE);
        $alipay_app_id = param('alipay_app_id', '', FALSE);
        $alipay_private_key = param('alipay_private_key', '', FALSE);

        $wechat_mch_id = param('wechat_mch_id', '', FALSE);
        $wechat_app_id = param('wechat_app_id', '', FALSE);
        $wechat_mch_key = param('wechat_mch_key', '', FALSE);


    if(intval($czbili)<1){
         message(-1, '请填写大于1的整数');
    }
    if(intval($txbili)<1){
         message(-1, '请填写大于1的整数');
    }
        $replace = array();
        $replace['chongzhi']['bili'] = intval($czbili);
        $replace['tixian']['bili'] = intval($txbili);
        $replace['alipay']['app_id'] = $alipay_app_id;
        $replace['alipay']['public_key'] = $alipay_public_key;
        $replace['alipay']['private_key'] = $alipay_private_key;
        $replace['wechat']['mch_id'] = $wechat_mch_id;
        $replace['wechat']['app_id'] = $wechat_app_id;
        $replace['wechat']['mch_key'] = $wechat_mch_key;
        $replace['paymethod'] = $paymethod;
        file_replace_var(DATA_PATH.'config/conf.default.php', $replace);
       
        message(0, '修改成功');
    }
} elseif($action == 'smtp') {

   
    
    if($method == 'GET') {
        
        $smtplist = smtp_find();
        $maxid = smtp_maxid();
       
        
        include ADMIN_PATH."view/setting_smtp.html";
    
    } else {
        
        
        $email = param('email', array(''));
        $host = param('host', array(''));
        $port = param('port', array(0));
        $user = param('user', array(''));
        $pass = param('pass', array(''));
        $is_ssl = param('is_ssl', array(''));
       
        $smtplist = array();
        foreach ($email as $k=>$v) {
           

            $smtplist[$k] = array(
                'email'=>$email[$k],
                'host'=>$host[$k],
                'port'=>$port[$k],
                'user'=>$user[$k],
                'pass'=>$pass[$k],
                'is_ssl'=>$is_ssl[$k],
              
            );
        }
        $r = file_put_contents_try(DATA_PATH.'config/smtp.conf.php', "<?php\r\nreturn ".var_export($smtplist,true).";\r\n?>");
        !$r AND message(-1, '写入文件失败');

        
        message(0, '保存成功');
    }
}
