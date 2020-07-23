<?php


require EXTEND_PATH . 'pay-php-sdk/init.php';
$config['alipay']['notify_url'] = $conf['web_url'] . 'zhifu-pay_notify_url.htm';
$config['alipay']['return_url'] = $conf['web_url'] . 'zhifu-pay_return_url.htm';
$config['alipay']['debug'] = false;
$config['alipay']['app_id'] = $conf['alipay']['app_id'];
$config['alipay']['public_key'] = $conf['alipay']['public_key'];  
$config['alipay']['private_key'] = $conf['alipay']['private_key'];
 $config['wechat']['debug'] = false;
 $config['wechat']['app_id'] = $conf['wechat']['app_id'];
 $config['wechat']['mch_id'] = $conf['wechat']['mch_id'];
 $config['wechat']['mch_key'] = $conf['wechat']['mch_key'];
 $config['wechat']['ssl_cer'] = '';
 $config['wechat']['ssl_key'] = '';
$config['wechat']['notify_url'] = $conf['web_url'] . 'zhifu-pay_notify_url.htm';
$config['wechat']['return_url'] = $conf['web_url'] . 'zhifu-pay_return_url.htm';

function pay($pay_method,$subject,$extra) {
    global $config,$conf;
    $url = '';

	
		$out_trade_no = xn_safe_key();
		
$out_trade_no_info = db_find_one('chongzhi',array('out_trade_no'=>$out_trade_no));
if($out_trade_no_info){
   $out_trade_no .=time();
   $out_trade_no = xn_substr($out_trade_no,0,32);
}
if ($pay_method == 'alipay') {
    $type=1;
}else{
    $type=2;
}
        $extra['type'] = $type;
        $extra['out_trade_no'] = $out_trade_no;
        $extra['create_time'] = time();

        $result = db_create('chongzhi', $extra);
        if ($result) {


 
$pay = new \Pay\Pay($config);
if ($pay_method == 'alipay') {
$options = [
    'out_trade_no' => $out_trade_no, // 商户订单号
    'total_amount' => $extra['rmb'], // 支付金额
    'subject'      => $subject, // 支付订单描述
];
return  $pay->driver('alipay')->gateway('web')->apply($options);

}else{
$options = [
    'out_trade_no'     => $out_trade_no, // 订单号
    'total_fee'        => 1,//$extra['rmb']*100 // 订单金额，**单位：分**
    'body'             => $subject, // 订单描述
    'product_id'       => $extra['actiontype'], // 订单商品 ID
];
$result = $pay->driver('wechat')->gateway('scan')->apply($options);
$img = downloaderweima($result,$out_trade_no);
$n = '<div><img src="'.$conf['web_url'] .$img.'" /></div>';
return $n;
}
 
         
          
        }else{
        	message(-1, '支付失败');
        }
}

function return_check($type,$post=false){
	   global $config;
       $pay = new \Pay\Pay($config);

       if($post){

         $data = $_POST; 
       }else{
        $data = $_GET; 
       }
       if($type==2){
        return $pay->driver('wechat')->gateway('mp')->verify(file_get_contents('php://input'));
       }else{
        return $pay->driver('alipay')->gateway()->verify($data,$data['sign']);
       }
       
}


?>