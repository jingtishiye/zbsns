<?php

function point_note_op($type,$score,$scoretype,$inctype='+',$data) {//操作
//type1下载附件2下载文档3付费阅读4充值5购买会员
//inctype1为增加2为减少

$invsersetype = ($inctype == '+') ? '-' : '+';
$data['score'] = $score;
$data['type'] = $type;
$data['scoretype'] = $scoretype;
$data['inctype'] = $inctype;
$data['create_time'] = time();
$data['status'] = 1;
$r = db_insert('point_note',$data);

if($r!==false){

$data['uid']>0 AND $r1 = db_update('user_extend',array('uid'=>$data['uid']),array($scoretype.$inctype=>$score));
if(!empty($data['to_uid'])&&$inctype == '-'){
$r2 = db_update('user_extend',array('uid'=>$data['to_uid']),array($scoretype.$invsersetype=>$score));


$data1['to_uid'] = $data['uid'];
$data1['uid'] = $data['to_uid'];
$data1['score'] = $score;
$data1['type'] = $type;
$data1['scoretype'] = $scoretype;
$data1['inctype'] = $invsersetype;
$data1['create_time'] = time();
$data1['status'] = 1;
$data1['itemid'] = $data['itemid'];

if(!empty($data['description'])){
  $data1['description'] = $data['description'];  
}
db_insert('point_note',$data1);


} 

return true;
}else{
	return false;
}





}
function has_buy($uid,$itemid,$type){

	$where['uid'] = $uid;
	$where['itemid'] = $itemid;
	$where['type'] = $type;
    $where['inctype'] = '-';
    $count = db_count('point_note',$where);
    if($count>0){
        return true;
    }else{
        return false;
    }
}
function point_rule($controller,$userid,$data=array()){

global $conf;

$info = db_find_one('point_rule',array('controller'=>$controller));
if($info){
$inctype = ($info['type'] == 2) ? '-' : '+';
if($info['tasknum']>0){

    
    $where['uid'] = $userid;
    $where['itemid'] = $info['id'];
    $where['type'] = 8;

   
    $count = db_count('point_note',$where);
    if($count>=$info['tasknum']){
        return false;
    }
}
if($info['num']>0){

    $time = time()-24*3600;
    $where['uid'] = $userid;
    $where['itemid'] = $info['id'];
    $where['type'] = 8;
    $where['create_time'] = array('>'=>$time);
    $count = db_count('point_note',$where);
    if($count>=$info['num']){
        return false;
    }
}
$data['description'] = $conf['pointrule'][$controller];
if($controller=='yaoqing'){

    $where['uid'] = $userid;
    $where['itemid'] = $info['id'];
    $where['type'] = 8;
    $where['description'] = $conf['pointrule'][$controller].$data['ip'];
    $count = db_count('point_note',$where);
    if($count>0){
        return false;
    }
    $data['description'] = $conf['pointrule'][$controller].$data['ip'];
    unset($data['ip']);
}
$data['to_uid']=0;
$data['itemid'] = $info['id'];
$data['uid'] = $userid;


point_note_op(8,$info['score'],$info['scoretype'],$inctype,$data);
}else{
    return false;
}
}
?>