<?php

!defined('DEBUG') and exit('Access Denied.');
include BASEPHP_FUNPATH . 'xn_zip.func.php';


if (empty($action) || $action == 'create') {

   
if($uid<=0){
    message(-1, '请先登录');
}



 $width  = param('width', 0);
 $height = param('height', 0);
    
$name = param('name');
$data = param_base64('data');


empty($data) and message(-1, '数据为空');
$filetypes         = include DATA_PATH . 'config/attach.conf.php';
$size = strlen($data);
$size > $filetypes['filesize'] and message(-1, '文件大小超出限制');
$ext  = file_ext($name, 7);
$allowtype = param(3,'attach');
$editid = param(4,0);

$tmpanme = $uid . '_' . xn_rand(15) . '.' . $ext;
$tmpfile = $conf['upload_path'] . 'tmp/' . $tmpanme;
$tmpurl  = $conf['upload_url'] . 'tmp/' . $tmpanme;
$filetype = file_type($tmpanme, $filetypes);
$filetype!=$allowtype and message(-1, '文件类型不允许');
file_put_contents($tmpfile, $data) or message(-1, '写入文件失败');
$sha1 = md5_file($tmpfile);


     if ($filetype == 'image') {
        $is_image = 1;

$mimetype = check_safe_image($tmpfile);
if ($mimetype == IMAGETYPE_GIF || $mimetype == IMAGETYPE_JPEG || $mimetype == IMAGETYPE_PNG || $mimetype == IMAGETYPE_BMP)
{

}else{
      unlink($tmpfile);
      message(-1, '非法文件');
}

}else{
    $is_image = 0;

}



$fileinfo = db_find_one('file', array('sha1'=>$sha1));
//如果是图片，则继续，文件名加时间
//如果是附件和文档，
if($fileinfo){
   
    if($is_image){

    $count = db_count('file',array('sha1'=>$sha1));
    $realname = $uid . '_' . $sha1 . $count. '.' . $ext;
    $day = xn_substr($sha1,5,2);
            
            $path = $conf['upload_path'].$allowtype.'/'.$day;
            $url = $conf['upload_url'].$allowtype.'/'.$day;
            !is_dir($path) AND mkdir($path, 0777, TRUE);
            
            $destfile = $path.'/'.$realname;
            $desturl = $url.'/'.$realname;
            
            $r = xn_copy($tmpfile, $destfile);

            !$r AND xn_log("xn_copy($tmpfile), $destfile) failed, dataid:$dataid, fileid:$fileid", 'php_error');
           


            $attach = array(
                'uid'=>$uid,
                'size'=>$size,
                'width'=>$width,
                'height'=>$height,
                'savepath'=>"$day/$realname",
                'savename'=>$realname,
                'name'=>$name,
                'mime'=>$filetype,
                'ext'=>$ext,
                'sha1'=>$sha1,
                'create_time'=>$time,
                'status'=>1,
                'type'=>1,
                'comment'=>'',
                'downloads'=>0,
                'isimage'=>$is_image
            );
            $id = file_create($attach);
            $attach['id'] = $id;
    }else{
        if(($fileinfo['uid']==$uid&&$fileinfo['tid']==0)||($editid>0&&$fileinfo['tid']==$editid)){

           $attach = $fileinfo;//表示这个可以用

        }else{
            
            unlink($tmpfile);
            message(-1, '该文件已上传');
        }
    }
    unlink($tmpfile);
}else{
            $realname = $uid . '_' . $sha1 . '.' . $ext;
            
            $day = xn_substr($sha1,5,2);
            
            $path = $conf['upload_path'].$allowtype.'/'.$day;
            $url = $conf['upload_url'].$allowtype.'/'.$day;
            !is_dir($path) AND mkdir($path, 0777, TRUE);
            
            $destfile = $path.'/'.$realname;
            $desturl = $url.'/'.$realname;
            
            $r = xn_copy($tmpfile, $destfile);

            !$r AND xn_log("xn_copy($tmpfile), $destfile) failed, dataid:$dataid, fileid:$fileid", 'php_error');
            unlink($tmpfile);


            $attach = array(
                'uid'=>$uid,
                'size'=>$size,
                'width'=>$width,
                'height'=>$height,
                'savepath'=>"$day/$realname",
                'savename'=>$realname,
                'name'=>$name,
                'mime'=>$filetype,
                'ext'=>$ext,
                'sha1'=>$sha1,
                'create_time'=>$time,
                'status'=>1,
                'type'=>1,
                'comment'=>'',
                'downloads'=>0,
                'isimage'=>$is_image
            );
            $id = file_create($attach);
            $attach['id'] = $id;
}
$attach['file_url'] = $conf['upload_url'].$allowtype.'/'.$attach['savepath'];
message(0, '上传成功', $attach);



}elseif($action == 'getfileinfo'){
  $id = param('id');
  $fileinfo = db_find_one('file', array('id'=>$id));
  message(0, '返回成功', $fileinfo);

 
} elseif ($action == 'upload_cup') {
   $uid<=0 AND message(-1, '请先登录');

    $filetypes  = include DATA_PATH . 'config/attach.conf.php';
    $ext = file_ext($_POST['file_name'], 7);

    $file = isset($_FILES['file_data']) ? $_FILES['file_data'] : null; //分段的文件

    $size = isset($_POST['file_size']) ? $_POST['file_size'] : null; //文件大小

    $total = isset($_POST['file_total']) ? $_POST['file_total'] : 0; //总片数

    $index = isset($_POST['file_index']) ? $_POST['file_index'] : 0; //当前片数

    $tmpanme = $uid . '_' . md5($_POST['file_name']) . '.' . $ext;

    $allowtype = param(3,'attach');

    $filetype = file_type($tmpanme, $filetypes);
    
    

    if ($size > $filetypes['filesize']) {
        $da = array(-1, '文件大小超出限制');
        echo xn_json_encode($da);
        return;
    }

    

    if($filetype!=$allowtype){
        
         $da = array(-1, '文件上传类型不允许');
         echo xn_json_encode($da);
         return;
    }
    



     if ($filetype == 'image') {
        $is_image = 1;
       
$mimetype = check_safe_image($file['tmp_name']);
if ($mimetype == IMAGETYPE_GIF || $mimetype == IMAGETYPE_JPEG || $mimetype == IMAGETYPE_PNG || $mimetype == IMAGETYPE_BMP)
{

}else{
      $da = array(-1, '非法文件');
        echo xn_json_encode($da);
        return;
}



    } else {
        $is_image = 0;
    }
$tmpurl = $conf['upload_url'] . 'tmp/' . $tmpanme;
$tmpfile     = isset($_POST['file_name']) ? $conf['upload_path'] . 'tmp/' . $tmpanme : null; //要保存的文件名
if (!$file || !$tmpfile) {
        $da = array(-1, '文件上传失败');
        echo xn_json_encode($da);
         return;
}
if ($file['error'] == 0) {
        //检测文件是否存在
        if (!file_exists($tmpfile)) {
            if (!move_uploaded_file($file['tmp_name'], $tmpfile)) {

            }

        } else {

            if (filesize($tmpfile) >= $size) {
                 
            } else {
                $content = file_get_contents($file['tmp_name']);
                if (!file_put_contents($tmpfile, $content, FILE_APPEND)) {
                    $da = array(-1, '文件上传失败');
                    echo xn_json_encode($da);
                    return;
                }
            }

        }
if($total==$index){//已经传完了
$sha1 = md5_file($tmpfile);
$fileinfo = db_find_one('file', array('sha1'=>$sha1));
if($fileinfo){
    $attach = $fileinfo;
    unlink($tmpfile);
}else{
    $day = xn_substr($sha1,5,2);
     $path = $conf['upload_path'].$allowtype.'/'.$day;
     $url = $conf['upload_url'].$allowtype.'/'.$day;
     !is_dir($path) AND mkdir($path, 0777, TRUE);


$realname = $uid . '_' . $sha1 . '.' . $ext;

            $destfile = $path.'/'.$realname;
            $desturl = $url.'/'.$realname;
            
            $r = xn_copy($tmpfile, $destfile);

            !$r AND xn_log("xn_copy($tmpfile), $destfile) failed, dataid:$dataid, fileid:$fileid", 'php_error');
            unlink($tmpfile);


            $attach = array(
                'uid'=>$uid,
                'size'=>$size,
                'width'=>0,
                'height'=>0,
                'savepath'=>"$day/$realname",
                'savename'=>$realname,
                'name'=>$_POST['file_name'],
                'mime'=>$filetype,
                'ext'=>$ext,
                'sha1'=>$sha1,
                'create_time'=>$time,
                'status'=>1,
                'type'=>1,
                'comment'=>'',
                'downloads'=>0,
                'isimage'=>$is_image
            );
            $id = file_create($attach);
            $attach['id'] = $id;



}
 $da = array(0, '文件上传成功',$attach);
 echo xn_json_encode($da);
 return;


}
}else{
     $da = array(-1, '文件上传失败');
     echo xn_json_encode($da);
     return;
}

} elseif ($action == 'delete') {


} elseif ($action == 'download') {

    // 判断权限
    //判断该会员组下载次数是否为0，为0后更新会员的会员组信息
    
     
    ($uid<=0) and message(-1, '请先登录');
     
    $aid    = param(4, 0);
    $tid    = param(3, 0);
    $attach = file_read($aid);
    empty($attach) and message(-1, '文件不存在');


    if($tid!=$attach['tid']){
        message(-1, '非法参数');
    }

    $has_buy = has_buy($uid,$tid,$attach['type']);
    if($has_buy||$attach['uid']==$uid||$attach['score']==0){
        $buy_score = 0;
    }else{
        $buy_score = floor($attach['score']*$userqx['bili']/100);

        if($user['extend']['point']<$buy_score){
           message(-1, '积分不足');
        }
    }

  

    if($attach['type']==1){

        if(!in_array(5,$userqx['quanxian'])&&$attach['uid']!=$uid){
             message(-1, '无权限下载附件');
        }
       $thread    = topic_read($tid);
       $path = 'attach';
       
    }elseif($attach['type']==2){
        if(!in_array(6,$userqx['quanxian'])&&$attach['uid']!=$uid){
             message(-1, '无权限下载文档');
        }
       $thread    = doc_read($tid); 
       $path = 'doc';
      
    }
     if($userqx['quanxian_limit_download']>0){
         
    $max_create_time = db_maxid('point_note','create_time',array('uid'=>$uid,'itemid'=>$tid,'type'=>$attach['type'],'inctype'=>'-'));
    if($time-$max_create_time<$userqx['quanxian_limit_download']){
      message(-1, '两次下载的限制时间还有'.humanmiao($userqx['quanxian_limit_download']-$time+$max_create_time));
    }
  }

    $attachpath = $conf['upload_path'] . $path.'/' . $attach['savepath'];
    $attachurl  = $conf['upload_url'] . $path.'/' . $attach['savepath'];
    
    !is_file($attachpath) and message(-1, '文件不存在');
     
        if(!$has_buy&&$attach['uid']!=$uid){//已经下载过的再次下载不计入次数
        

        if($attach['type']==2){
            doc_update($tid, array('down+' => 1));
           
        }
        
            $data['description'] = '下载'.$attach['name'];
            $data['uid'] = $uid;
            $data['to_uid'] = $thread['uid'];
            $data['itemid'] = $thread['id'];
            point_note_op($attach['type'],$buy_score,'point','-',$data);
        
        

        if($userqx['nums']>0&&$buy_score>0){//等于0表示无限制
        if($user['extend']['grades_nums']==1){
            user_extend_update($uid,array('grades'=>0,'grades_nums'=>0));
        }else{
            user_extend_update($uid,array('grades_nums-'=>1));
        }
        }
        file_update($aid, array('downloads+' => 1));

        }

       
        


        $filesize = $attach['size'];
    $type = 'php';

    // php 输出
    if ($type == 'php') {
  
        if (stripos($_SERVER["HTTP_USER_AGENT"], 'MSIE') !== false || stripos($_SERVER["HTTP_USER_AGENT"], 'Edge') !== false || stripos($_SERVER["HTTP_USER_AGENT"], 'Trident') !== false) {
            $attach['name'] = urlencode($attach['name']);
            $attach['name'] = str_replace("+", "%20", $attach['name']);
        }
        $timefmt = date('D, d M Y H:i:s', $time) . ' GMT';
        header('Date: ' . $timefmt);
        header('Last-Modified: ' . $timefmt);
        header('Expires: ' . $timefmt);
        header('Cache-control: max-age=0, must-revalidate, post-check=0, pre-check=0');
        header('Cache-control: max-age=86400');
        header('Content-Transfer-Encoding: binary');
        header("Pragma: public");
        header("Accept-Length: ".$filesize);
        
        header('Content-Disposition: attachment; filename="' . $attach['name'] . '"');
        header('Content-Type: application/octet-stream');
        //header("Content-Type: application/force-download");    // 后面的会覆盖前面
        ob_clean();   //*******************修改部分*******************************
        ob_flush();
        flush();     //*******************修改部分*******************************

        readfile($attachpath);
       clearstatcache();
       // ob_end_clean();
        exit;
    } else {

        http_location($attachurl);
    }
} elseif ($action == 'downloadbak') {

    $file_time = param('time');

    if ($file_time) {
        $zipfilename = $file_time . '.zip';
        $attachpath  = $conf['batabase_export_config']['path'] . $zipfilename;
        if (is_file($attachpath)) {

        } else {
            $name     = date('Ymd-His', $file_time) . '-*.sql*';
            $filename = date('Ymd-His', $file_time) . 'sql';
            $path     = $conf['batabase_export_config']['path'] . $name;
            $file     = glob($path);

            $count = count($file);
            if ($count > 0) {
                wi_add_files_to_zip($conf['batabase_export_config']['path'] . $zipfilename, $file, $conf['batabase_export_config']['path']);

            } else {
                message(-1, '无可下载文件！');
            }

        }
        $filesize = filesize($attachpath);
        if (stripos($_SERVER["HTTP_USER_AGENT"], 'MSIE') !== false || stripos($_SERVER["HTTP_USER_AGENT"], 'Edge') !== false || stripos($_SERVER["HTTP_USER_AGENT"], 'Trident') !== false) {
            $zipfilename = urlencode($zipfilename);
            $zipfilename = str_replace("+", "%20", $zipfilename);
        }
        $timefmt = date('D, d M Y H:i:s', time()) . ' GMT';
        header('Date: ' . $timefmt);
        header('Last-Modified: ' . $timefmt);
        header('Expires: ' . $timefmt);
        // header('Cache-control: max-age=0, must-revalidate, post-check=0, pre-check=0');
        header('Cache-control: max-age=86400');
        header('Content-Transfer-Encoding: binary');
        header("Pragma: public");
        header('Content-Disposition: attachment; filename="' . $zipfilename . '"');
        header('Content-Type: application/octet-stream');
        ob_clean();   //*******************修改部分*******************************
        ob_flush();
        flush();     //*******************修改部分*******************************

        readfile($attachpath);
        clearstatcache();
        ob_end_clean();

        exit;

    } else {
        message(-1, '参数错误！');

    }

}
