<?php

!defined('DEBUG') AND exit('Access Denied.');
function makeXML(){
  global $conf;
   $content='<?xml version="1.0" encoding="UTF-8"?>
   <urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
   ';


   $list = db_find('topic',array('status'=>1),array('id'=>-1),1,1000);

   foreach($list as $data){
    $content.=create_item($data);
   }

   $doclist = db_find('doccon',array('status'=>1),array('id'=>-1),1,1000);
   foreach($doclist as $data){
    $content.=create_item_doc($data);
   }
   $content.='</urlset>';
   $fp=fopen('sitemap.xml','w+');
   fwrite($fp,$content);
   fclose($fp);

 }

 function create_item_doc($data){
  global $conf;

    $item="<url>\n";
    $item.="<loc>".r_url('doc-'.$data['id'],'','docs')."</loc>\n";
    $item.="<priority>0.5</priority>\n";
    $item.="<lastmod>".date('Y-m-d H:i:s',$data['create_time'])."</lastmod>\n";
   // $item.="<changefreq>".$data['changefreq']."</changefreq>\n";
    $item.="</url>\n";
    return $item;

}
function create_item($data){
  global $conf;

    $item="<url>\n";
    $item.="<loc>".r_url('thread-'.$data['id'],'','topics')."</loc>\n";
    $item.="<priority>0.5</priority>\n";
    $item.="<lastmod>".date('Y-m-d H:i:s',$data['create_time'])."</lastmod>\n";
   // $item.="<changefreq>".$data['changefreq']."</changefreq>\n";
    $item.="</url>\n";
    return $item;

}
makeXML();
echo '站点文章地图已生成';