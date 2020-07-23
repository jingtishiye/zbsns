<?php

!defined('DEBUG') AND exit('Access Denied.');






if(empty($action)) {

	$info = array();
	$info['disable_functions'] = ini_get('disable_functions');
	$info['allow_url_fopen'] = ini_get('allow_url_fopen') ? '是' : '否';
	$info['safe_mode'] = ini_get('safe_mode') ? '是' : '否';
	empty($info['disable_functions']) && $info['disable_functions'] = '无';
	$info['upload_max_filesize'] = ini_get('upload_max_filesize');
	$info['post_max_size'] = ini_get('post_max_size');
	$info['memory_limit'] = ini_get('memory_limit');
	$info['max_execution_time'] = ini_get('max_execution_time');
	$info['dbversion'] = $db->version();
	$info['SERVER_SOFTWARE'] = _SERVER('SERVER_SOFTWARE');
	$info['HTTP_X_FORWARDED_FOR'] = _SERVER('HTTP_X_FORWARDED_FOR');
	$info['REMOTE_ADDR'] = _SERVER('REMOTE_ADDR');

    $getinfo = http_post_curl('http://www.5isns.com/onlineupdate-now_ver',array('domain'=>$_SERVER["SERVER_NAME"],'ver'=>$conf['version']));

    $result = xn_json_decode($getinfo);
    
    $info['now_ver'] = $result['ver'];

    if($result['domain']['status']==1){
    	if($result['domain']['type']==1){
    		$typename = '初级授权';
    		$juli = '距离授权到期还有'.humanmiao($result['domain']['sq_time']+($result['domain']['xfnum']+1)*(24*366*3600)-time());
    	}
    	if($result['domain']['type']==2){
    		$typename = '高级授权';
    		$juli = '距离授权到期还有'.humanmiao($result['domain']['sq_time']+($result['domain']['xfnum']+1)*(24*366*3600)-time());
    	}
    	if($result['domain']['type']==3){
    		$typename = '永久授权';
    		$juli = '';
    	}
    	$info['domain'] = '<span class="text-success">您的授权信息:授权时间为'.date('Y-m-d',$result['domain']['sq_time']).'，授权类型为'.$typename.'。'.$juli.'</span>';
    }else{
    	$info['domain'] = '<span class="text-danger">你的域名尚未授权</span>';
    }
    

	$stat = array();
    $stat['docs'] = doc_count(array('status'=>array('>='=>0)));
    $stat['topics'] = topic_count(array('status'=>array('>='=>0)));
	$stat['users'] = user_count();
	$stat['attachs'] = file_count();
	$stat['disk_free_space'] = function_exists('disk_free_space') ? humansize(disk_free_space(ROOT_PATH)) : '未知';

	//$lastversion = get_last_version($stat);
        
	include ADMIN_PATH.'view/home.html';
	
} elseif($action == 'cache') {
if($method == 'GET') {
	
		
		$input = array();
		$input['clear_tmp'] = form_checkbox('clear_tmp', 1);
		$input['clear_log'] = form_checkbox('clear_log', 1);
		$input['clear_cache'] = form_checkbox('clear_cache', 1);
		$input['clear_file'] = form_checkbox('clear_file', 1);
		include ADMIN_PATH.'view/clear_cache.html';
		
	} else {
		$clear_log = param('clear_log');
		$clear_tmp = param('clear_tmp');
		$clear_cache = param('clear_cache');
		$clear_file = param('clear_file');


		$r1 = db_delete('message', array('type' => 2,'uid'=>0));


		$clear_cache AND cache_truncate();

		$clear_cache AND $runtime = NULL; // 清空
		
		$clear_tmp AND rmdir_recusive($conf['tmp_path'], 1);
        $clear_log AND rmdir_recusive($conf['log_path'], 1);

        $clear_file AND clear_file();

		message(0, '缓存清理成功');
	}
	
} elseif($action == 'login') {


} else {
	
}
function clear_file(){
	$topiclist = db_find_all('file',array('status'=>1));
foreach ($topiclist as $key => $value) {
    $unlink = false;
	if($value['type']==1){//帖子附件
		$topicinfo = db_find_one('topic',array('id'=>$value['tid']));
		if(!empty($topicinfo)){
			if($value['mime']=='image'){//帖子附件
               if(stripos(htmlspecialchars_decode($topicinfo['content']),$value['savename'])!==FALSE){
                  
               }else{
                    //echo '不存在的图片：'.$value['name'].'----'.$value['savename'].'<br>';
                    $unlink = true;
                    
               } 
		    }else{
		    	if($topicinfo['file_num']>0){
                     $filelist = explode(',',$topicinfo['filelist']);
               if(in_array($value['id'],$filelist)){
                    
               }else{
                    //echo '不存在的附件：'.$value['name'].'----'.$value['savename'].'<br>';
                    $unlink = true;
               } 
		    	}else{
		    		 //echo '附件数量为0，不存在的附件：'.$value['name'].'----'.$value['savename'].'<br>';
		    		 $unlink = true;
		    	}
               






		    }
            
            

	    }else{

		//echo '没有这个帖子：'.$value['name'].'----'.$value['savename'].'<br>';
         $unlink = true;
	   }
	}
 	if($value['type']==2){//文档附件
		$docconinfo = db_find_one('doccon',array('id'=>$value['tid']));
		if(!empty($docconinfo)){
			if($value['id']!=$docconinfo['fileid']){
				//说明不是这个文档的附件了
				// echo '不存在的文档：'.$value['name'].'----'.$value['savename'].'<br>';
				 $unlink = true;
			}else{
                
			}
	    }else{

		//echo '没有这个文档：'.$value['name'].'----'.$value['savename'].'<br>';
        $unlink = true;
	   }
	}   
 	if($value['type']==3){//话题附件
		$topiccateinfo = db_find_one('topiccate',array('id'=>$value['tid']));
		if(!empty($topiccateinfo)){
			if($value['id']!=$topiccateinfo['cover_id']){
				//说明不是这个话题的图标了
				//echo '不存在的话题图标：'.$value['name'].'----'.$value['savename'].'<br>';
				$unlink = true;
			}else{
				
			}
	    }else{

		//echo '没有这个话题：'.$value['name'].'----'.$value['savename'].'<br>';
		$unlink = true;

	   }
	}
	 	if($value['type']==5){//话题附件
		$rzuserinfo = db_find_one('rzuser',array('uid'=>$value['tid']));
		if(!empty($rzuserinfo)){
			if($value['id']!=$rzuserinfo['cover_id']){
				
				$unlink = true;
			}else{
				
			}
	    }else{

		
		$unlink = true;

	   }
	}
 	if($value['type']==6){//
		$addonsinfo = db_find_one('addons',array('id'=>$value['tid']));
		if(!empty($addonsinfo)){
			if($value['mime']=='image'){//帖子附件
               if(stripos(htmlspecialchars_decode($addonsinfo['content']),$value['savename'])!==FALSE){
                   
               }else{

                    //如果不在内容中也不在预览图中则删除
			if($value['id']!=$addonsinfo['coverid']){
				//说明不是这个话题的图标了
				//echo '不存在的预览图：'.$value['name'].'----'.$value['savename'].'<br>';
				$unlink = true;
			}else{
				

			}

               } 







		    }else{

		    if($value['id']!=$addonsinfo['fileid']){
				
				//echo '不存在的插件文件：'.$value['name'].'----'.$value['savename'].'<br>';
				$unlink = true;
			}else{
				
			}

		    }



	    }else{

		//echo '没有这个插件：'.$value['name'].'----'.$value['savename'].'<br>';
$unlink = true;
	   }
	}
	if($value['type']==7){//评论附件
		$commentinfo = db_find_one('comment',array('id'=>$value['tid']));
		if(!empty($commentinfo)){
			if($value['mime']=='image'){//帖子附件
               if(stripos(htmlspecialchars_decode($commentinfo['content']),$value['savename'])!==FALSE){
                  
               }else{
                    //echo '不存在的图片：'.$value['name'].'----'.$value['savename'].'<br>';
                    $unlink = true;
                    
               } 
		    }else{

                $unlink = true;


		    }
            
            

	    }else{

		//echo '没有这个帖子：'.$value['name'].'----'.$value['savename'].'<br>';
         $unlink = true;
	   }
	}
	 	if($value['type']==8){//帖子轮播图
		$topiccateinfo = db_find_one('topicslider',array('id'=>$value['tid']));
		if(!empty($topiccateinfo)){
			if($value['id']!=$topiccateinfo['cover_id']){
				
				$unlink = true;
			}else{
				
			}
	    }else{

		$unlink = true;

	   }
	}
	if($value['type']==9){//帖子轮播图
		$topiccateinfo = db_find_one('docslider',array('id'=>$value['tid']));
		if(!empty($topiccateinfo)){
			if($value['id']!=$topiccateinfo['cover_id']){
				
				$unlink = true;
			}else{
				
			}
	    }else{

		$unlink = true;

	   }
	}
   if($unlink){
   	unlink_file($value['id']);
   }
}
}
function get_last_version($stat) {
	global $conf, $time;
	$last_version = kv_get('last_version');
	if($time - $last_version > 86400) {
		kv_set('last_version', $time);
		$sitename = urlencode($conf['sitename']);
		$sitedomain = urlencode(http_url_path());
		$version = urlencode($conf['version']);
		$s = http_post_curl('http://www.5isns.com/onlineupdate-up_ver',array('ver'=>$conf['version']));
		return '<script src="http://www.5isns.com/version.htm?sitename='.$sitename.'&sitedomain='.$sitedomain.'&users='.$stat['users'].'&threads='.$stat['threads'].'&posts='.$stat['posts'].'&version='.$version.'"></script>';
	} else {
		return '';
	}
}

?>
