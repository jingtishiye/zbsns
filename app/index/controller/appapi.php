<?php

!defined('DEBUG') and exit('Access Denied.');

function ajaxmess($code, $message, $extra = array()){
    $arr = $extra;
    $arr['code'] = $code.'';
    $arr['message'] = $message;
    echo xn_json_encode($arr);
    exit;
}

$data['token'] = param('access_token');
$data['time'] = param('time');
$time1 = $data['time'];
$token = sha1($conf['auth_key'].$conf['appid'].$time1);

if($token!=$data['token']||$time-intval($time1)>60){
     //ajaxmess(-1,'非法请求');
}


if ($action == 'get_topiccate') {
   if($conf['allow_cate_show']>0){
       $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,$conf['allow_cate_show']);
    }else{
       $taglist = topiccate_find_all(array('status'=>1),array('sort'=>'-1'));
    }
    if($taglist){
      array_unshift($taglist, ['id'=>'focus','name'=>'关注'], ['id'=>'rec','name'=>'推荐']);
      ajaxmess(0,'获取话题成功',array('data'=>$taglist));
    }else{
      ajaxmess(-1,'无话题信息');
    }
    

}elseif ($action == 'get_topic_by_cid') {
     $page = param('page');
     $list_rows = param('list_rows');
     $id = param('cid',0);
     if($id!='focus'||$id!='rec'){
        $idarr = andother_find_arr('tagsandother',1,$id,'tid','did',true);
        empty($idarr) AND $idarr = 0;
        $where = array('status' => 1,'id'=>$idarr,'type'=>array('>'=>1));
     }else{
        $where = array('status' => 1,'type'=>array('>'=>1));
     }
     
    $topiclist = db_find('topic',$where,array('settop'=>-1,'id'=>-1),$page,$list_rows);
    
    if($topiclist){
      foreach ($topiclist as $key => $value) {

            $topiclist[$key]['view'] = humannumber($value['view']);  
            $topiclist[$key]['time'] = humandate($value['create_time']);
              
    if($value['img_num']>0){
          preg_match_all('/\<img.*?src\=\"(.*?)\"[^>]*>/i',htmlspecialchars_decode($value['content']),$srcmatches);//所有图片
               if($value['img_num']>2){
               $img[] = array();
               foreach ($srcmatches[1] as $k => $v) {



        $url = trim($v,"\"'");

        if(substr($url,0,7) == 'http://'||substr($url,0,8) == 'https://'){

            $img_src = $url;

        }else{
            $img_src = $conf['web_url'].'/'.$url;
        }
        $topiclist[$key]['imgarr'][] = $img_src;
        if(count($topiclist[$key]['imgarr'])==3){
            break;
        }

    }
            
               $topiclist[$key]['imgtype'] = 3;



            }else{
                 $url = trim($value['first_img'],"\"'");

        if(substr($url,0,7) == 'http://'||substr($url,0,8) == 'https://'){

            $img_src = $url;

        }else{
            $img_src = $conf['web_url'].'/'.$url;
        }
                $topiclist[$key]['imgarr'][] = $img_src;
                if($value['img_num']==1&&$value['choice']==1){
                    $topiclist[$key]['imgtype'] = 3;
                }else{
                    $topiclist[$key]['imgtype'] = 2;
                   // $topiclist[$key]['videoSrc'] = 1;
                }
               
            }

    }else{
        $topiclist[$key]['imgtype'] = 1;
    }
    

       
           
            $topiclist[$key]['userinfo'] = user_read($value['uid']);
            $topiclist[$key]['author'] = $topiclist[$key]['userinfo']['nickname'];
            $topiclist[$key]['avatar_url'] = $topiclist[$key]['userinfo']['avatar_url'];
            
      }
      ajaxmess(0,'获取文章成功',array('data'=>$topiclist));
    }else{
      ajaxmess(-1,'无文章信息');
    }
}elseif ($action == 'get_slider') { 
//同时获取话题和轮播图
$where = array('status' => 1,'img_num'=>array('>'=>0),'type'=>array('>'=>1)); 

$topiclist = db_find('topic',$where,array('choice'=>-1,'id'=>'-1'),1,5);


if($topiclist){
    $img = array();
foreach ($topiclist as $key => $value) {

$img[$key]['title']= $value['title'];
$img[$key]['id']= $value['id'];
$userinfo = user_read($value['uid']);
$img[$key]['author'] = $userinfo['nickname'];
$img[$key]['avatar_url'] = $userinfo['avatar_url'];
$img[$key]['view'] = humannumber($value['view']);  
$img[$key]['time'] = humandate($value['create_time']);
$img[$key]['description'] = $value['description'];

          $url = trim($value['first_img'],"\"'");

        if(substr($url,0,7) == 'http://'||substr($url,0,8) == 'https://'){//网络图片可以实现本地化，下载保存，先判断是否为正经图片
           $img[$key]['img']=$url;

        }else{

           $img[$key]['img']=$conf['web_url'].'/'.$url;
        }

}
   if($conf['allow_cate_show']>0){
       $taglist = topiccate_find(array('status'=>1),array('sort'=>'-1'),1,$conf['allow_cate_show']);
    }else{
       $taglist = topiccate_find_all(array('status'=>1),array('sort'=>'-1'));
    }

      array_unshift($taglist, ['id'=>'focus','name'=>'关注'], ['id'=>'rec','name'=>'推荐']);
      ajaxmess(0,'获取轮播图成功',array('data'=>$img,'tag'=>$taglist));
    }else{
      ajaxmess(-1,'无轮播图信息');
    }
}elseif($action=='get_topic_content'){
 $id = param('id');
 $nowuid = param('uid');
 $thread = topic_read($id);
   
 empty($thread) AND ajaxmess(-1,'帖子不存在');
 topic_inc_views($id,1);
 $thread['content'] = htmlspecialchars_decode($thread['content']);
 $thread['content'] = str_replace('\\"','',$thread['content']);

 ajaxmess(0,'获取轮播图成功',array('data'=>$thread));

}
