<?php


// ------------> 最原生的 CURD，无关联其他数据。

function file__create($arr) {

	$r = db_create('file', $arr);

	return $r;
}

function file__update($id, $arr) {

	$r = db_update('file', array('id'=>$id), $arr);

	return $r;
}

function file__read($id) {

	$file = db_find_one('file', array('id'=>$id));

	return $file;
}

function file__delete($id) {

	$r = db_delete('file', array('id'=>$id));

	return $r;
}

function file__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 20) {

	$filelist = db_find('file', $cond, $orderby, $page, $pagesize);

	return $filelist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function file_create($arr) {

	$r = file__create($arr);

	return $r;
}

function file_update($id, $arr) {

	$r = file__update($id, $arr);

	return $r;
}

function file_read($id) {

	$file = file__read($id);
	file_format($file);

	return $file;
}

function file_delete($id) {

	global $conf;
	$file = file_read($id);
	$path = $conf['upload_path'].'attach/'.$file['filename'];
	file_exists($path) AND unlink($path);
	
	$r = file__delete($id);

	return $r;
}

function file_delete_by_pid($pid) {
	global $conf;
	list($filelist, $imagelist, $filelist) = file_find_by_pid($pid);

	foreach($filelist as $file) {
		$path = $conf['upload_path'].'attach/'.$file['filename'];
		file_exists($path) AND unlink($path);
		file__delete($file['id']);
	}

	return count($filelist);
}

function file_delete_by_uid($uid) {
	global $conf;

	$filelist = db_find('file', array('uid'=>$uid), array(), 1, 9000);
	foreach ($filelist as $file) {
		$path = $conf['upload_path'].'file/'.$file['filename'];
		file_exists($path) AND unlink($path);
		file__delete($file['id']);
	}

}

function file_find($cond = array(), $orderby = array(), $page = 1, $pagesize = 20) {

	$filelist = file__find($cond, $orderby, $page, $pagesize);
	if($filelist) foreach ($filelist as &$file) file_format($file);

	return $filelist;
}
function find_image_by_id($id,$width=50,$height=50) {
    global $conf;
    if($id==0){
    	
    	return $conf['base_web_url'].'public/common/images/noimg.png';
    }

	$fileinfo = file_read($id);

    if($fileinfo['isimage']){

      return $fileinfo['url'];
    }else{
    	return '';
    }
	
}
// 获取 $filelist $imagelist
function file_find_by_pid($pid) {
	$filelist = $imagelist = $filelist = array();

	$filelist = file__find(array('pid'=>$pid), array(), 1, 1000);
	if($filelist) {
		foreach ($filelist as $file) {
			file_format($file);
			$file['isimage'] ? ($imagelist[] = $file) : ($filelist[] = $file);
		}
	}

	return array($filelist, $imagelist, $filelist);
}

// ------------> 其他方法

function file_format(&$file) {
	global $conf;
	if(empty($file)) return;

	$file['create_time_fmt'] = humandate($file['create_time']);
	$file['url'] = $conf['base_web_url'].$conf['upload_url'].$file['mime'].'/'.$file['savepath'];
    
    if(in_array($file['ext'],array('tar','zip','gz','rar','7z','bz'))){
    	$icon = 'fa-file-zip-o';
    }else if(in_array($file['ext'],array('gif','jpg','jpeg','png','bmp'))){
    	$icon = 'fa-file-image-o';
    }else if(in_array($file['ext'],array('doc','docx','wps','wpt','dot','rtf'))){
    	$icon = 'fa-file-word-o';
    }else if(in_array($file['ext'],array('pptx','dps','dpt','ppt','pot','pps'))){
    	$icon = 'fa-file-powerpoint-o';
    }else if(in_array($file['ext'],array('xlsx','et','ett','xls','xlt'))){
    	$icon = 'fa-file-image-o';
    }else if($file['ext']=='pdf'){
        $icon = 'fa-file-pdf-o';
    }elseif($file['ext']=='txt'){
    	$icon = 'fa-file-text-o';
    }else{
    	$icon = 'fa-file-zip-o';
    }
    

    $file['icon'] = $icon;
$file['user'] = user_read($file['uid']);
$file['size_fmt'] = humansize($file['size']);

}

function file_count($cond = array()) {

	$cond = db_cond_to_sqladd($cond);
	$n = db_count('file', $cond);

	return $n;
}

function file_type($name, $types) {

	$ext = file_ext($name);
	foreach($types as $type=>$exts) {
		if($type == 'all') continue;
		if(in_array($ext, $exts)) {
			return $type;
		}
	}
	return 'other';
}

// 扫描垃圾的附件，每日清理一次
function file_gc() {
	global $time, $conf;

	$tmpfiles = glob($conf['upload_path'].'tmp/*.*');
	if(is_array($tmpfiles)) {
		foreach($tmpfiles as $file) {
			// 清理超过一天还没处理的临时文件
			if($time - filemtime($file) > 86400) {
				unlink($file);
			}
		}
	}

}
function single_attach_post($fileid,$dataid,$type){
	 $fileinfo = db_find_one('file',array('id'=>$fileid));
          if($fileinfo['tid']!=0){
          	
          }else{
          	  db_update('file',array('id'=>$fileid),array('tid'=>$dataid,'type'=>$type,'update_time'=>time()));
          }

}
function unlink_file($fileid){
    global $conf;
    $nowfileinfo = file__read($fileid);
    if($nowfileinfo){


	$file = $conf['upload_path'] . $nowfileinfo['mime'].'/' . $nowfileinfo['savepath'];
    	if(is_file($file)){
    		unlink($file);
    }


    	
    	db_delete('file',array('id'=>$fileid));
    	
    }
 	return true;
}
function find_content_img($content,$type,$tid){
	 preg_match_all('/\<img.*?data-id\=\"(.*?)\"[^>]*>/i',htmlspecialchars_decode($content),$matches);//本地图片
    
    foreach ($matches[1] as $key => $value) {
         $img_arr = explode('-',$value);
          $imginfo = db_find_one('file',array('id'=>$img_arr[0],'sha1'=>$img_arr[1]));
          if($imginfo['tid']!=0&&$imginfo['tid']!=$tid){
              
          }else{
           db_update('file',array('id'=>$img_arr[0],'sha1'=>$img_arr[1]),array('tid'=>$tid,'type'=>$type));
          }

    	
    	
    }
}
function doc_file_upload($fileid,$dataid,$oldfileid=0) {
global $uid, $time, $conf;


$data = db_find_one('doccon', array('id'=>$dataid));

if(empty($data)) return;
db_update('file',array('id'=>$fileid),array('score'=>$data['score'],'tid'=>$dataid,'type'=>2));			
		
if($oldfileid>0){

	$deleteinfo = db_find_one('doccon', array('id'=>$oldfileid));
	if($deleteinfo){
		 unlink($conf['upload_path'] . $deleteinfo['mime'].'/' . $deleteinfo['savepath']);
	     db_delete('file',array('id'=>$oldfileid));
	}
   
}	
$nowfileinfo = file__read($fileid);
		
if($conf['online_trans']==1){
$online_trans['time'] = $time;
$online_trans['appid'] = $conf['appid'];
$online_trans['token'] = md5($conf['auth_key'].$conf['appid'].$time);


$filepath = $conf['upload_path']."doc/".$nowfileinfo['savepath'];
$online_trans['file'] = '@' . $filepath;
$online_trans['showpage'] = $data['showpage'];
$online_trans['hash'] = $nowfileinfo['sha1'];
$online_trans['filesize'] = $nowfileinfo['size'];
$online_trans['ext'] = $nowfileinfo['ext'];
$online_trans['filename'] = $nowfileinfo['savename'];
$online_trans['onlyname'] = str_replace('.'.$nowfileinfo['ext'],'',$nowfileinfo['savename']);

$n=http_post_file($conf['api_url'].'/api/upload',$online_trans,$filepath);

}else{
//复制文档

$tmpfile = $conf['upload_path'] . 'doc/' . $nowfileinfo['savepath'];

$destfile = $conf['upload_path'] . 'output/' . $nowfileinfo['savename'];
$r = xn_copy($tmpfile, $destfile);
db_update('doccon',array('id'=>$dataid),array('status'=>2));



}			



return true;

}

function thread_attach_post($tid,$filelist,$scorelist,$method='add'){
	global $uid, $time, $conf,$plugin;
	$post = topic__read($tid);
	
    preg_match_all('/\<img.*?data-id\=\"(.*?)\"[^>]*>/i',htmlspecialchars_decode($post['content']),$matches);//本地图片
    
    foreach ($matches[1] as $key => $value) {
         $img_arr = explode('-',$value);
          $imginfo = db_find_one('file',array('id'=>$img_arr[0],'sha1'=>$img_arr[1]));
          if($imginfo['tid']!=0&&$imginfo['tid']!=$tid){
              
          }else{
if($imginfo['tid']!=0){

}else{
			if($conf['sy_type']>0){
            


        	setWater($conf['upload_url'].'image/'.$imginfo['savepath'],'./public/common/images/water.png',$conf['shuiyin'],'171,171,171',9,'./data/FZZYJW.TTF',$conf['sy_type']);
             }
              db_update('file',array('id'=>$img_arr[0],'sha1'=>$img_arr[1]),array('tid'=>$tid,'type'=>1));
}



          
          }

    	
    	
    }
    $oldcontent = htmlspecialchars_decode($post['content']);
    preg_match_all('/\<img.*?src\=\"(.*?)\"[^>]*>/i',htmlspecialchars_decode($post['content']),$srcmatches);//所有图片
   
    
	foreach ($srcmatches[1] as $key => $value) {



        $url = trim($value,"\"'");

		if(strpos($url,http_url_path()) === false && (substr($url,0,7) == 'http://'||substr($url,0,8) == 'https://')){//网络图片可以实现本地化，下载保存，先判断是否为正经图片
           
             $oldcontent = $plugin->run('localimg','localdown',array('img'=>$url,'content'=>$oldcontent,'tid'=>$tid));

		}
        


    }




     
preg_match_all('/\<img.*?src\=\"(.*?)\"[^>]*>/i',$oldcontent,$srcmatchesnew);//所有图片


    $img_num = count($srcmatchesnew[1]);
    if($img_num>0){
    	$first_img = $srcmatchesnew[1][0];
    }else{
    	$first_img = '';
    }
    if(!empty($filelist)){//如果存在附件
       $file_num = count($filelist);
       
       foreach ($filelist as $key => $value) {
          $fileinfo = db_find_one('file',array('id'=>$value));
          if($fileinfo['tid']!=0&&$fileinfo['tid']!=$tid){
              
          }else{
            db_update('file',array('id'=>$value),array('score'=>$scorelist[$key],'tid'=>$tid,'type'=>1));
          }
       	  

       }
    }else{
       $file_num = 0;
    }



if($oldcontent == htmlspecialchars_decode($post['content'])){
  	topic__update($tid, array('img_num'=>$img_num,'file_num'=>$file_num,'first_img'=>$first_img,'filelist'=>implode(',',$filelist)));
}else{
	if($oldcontent){



		topic__update($tid, array('img_num'=>$img_num,'file_num'=>$file_num,'first_img'=>$first_img,'filelist'=>implode(',',$filelist),'content'=>htmlspecialchars($oldcontent)));
	}
	
}




}



?>