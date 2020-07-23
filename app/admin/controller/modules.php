<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');

if (empty($action) || $action == 'list') {

    $dir_list = get_dir(ROOT_PATH.'app/');
    $plugins_list = array();
    $module_index = $conf['module_index'];
    foreach ($dir_list as $v) {

        $pluginfile = ROOT_PATH.'app/' . $v . '/info.php';

        if (file_exists($pluginfile)) {
            $module_info = include_once $pluginfile;
            
            if (!in_array($module_info['info']['name'],$conf['module_arr'])) {
                $module_info['is_install'] = 0;
               
            } else {
                $module_info['is_install'] = 1;
            }
            if($module_index==$v){
                $module_info['index'] = 1;
            }else{
                $module_info['index'] = 0;
            }
           
           

            $plugins_list[] = $module_info;
        }
    }
    
    include ADMIN_PATH . "view/modules_list.html";


} else if ($action == 'install') {

   
    $name       = param('name');
    $module_arr = $conf['module_arr'];
    if (in_array($name,$module_arr)) {
      message(-1, '模块已经安装过');
               
    } 
     $module_theme = $conf['module_theme'];
     $module_theme[$name] = 'default';
   
    array_push($module_arr,$name);

    $replace['module_arr'] = $module_arr;
    $replace['module_theme'] = $module_theme;
        
    file_replace_var(DATA_PATH.'config/conf.default.php', $replace);
      


  module_exec_sql($name);






     message(0, '模块安装完成');



} else if ($action == 'uninstall') {

    $name       = param('name');
    $module_arr = $conf['module_arr'];
    if (!in_array($name,$module_arr)) {
      message(-1, '模块并未安装');
               
    } 
     $module_theme = $conf['module_theme'];
    $n = array_search($name,$module_arr);
unset($module_arr[$n]);
unset($module_theme[$name]);

    $replace['module_arr'] = $module_arr;
    $replace['module_theme'] = $module_theme;
        
    file_replace_var(DATA_PATH.'config/conf.default.php', $replace);
     module_exec_sql($name,false);
     message(0, '模块卸载完成');



} else if ($action == 'config') {
	$name     = param('name');
    $module_info = include_once ROOT_PATH.'app/' . $name . '/info.php';

    if ($method == 'POST') {

    $replace['configvalue'] =  json_encode(param('config'));



        file_replace_var(ROOT_PATH.'app/' . $name . '/info.php', $replace);
        message(0, '修改配置成功');

    }else{
      
        $config = $module_info['config'];
        if(!empty($module_info['configvalue'])){
            $db_config = json_decode($module_info['configvalue'], true);
           
            foreach ($config  as $key => $value) {
                
                if($value['type'] != 'group'){
                   $config[$key]['value'] =$db_config[$key];
                }else{
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $config[$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
                
               
            }
        }

            include ADMIN_PATH . "view/modules_config.html";
    }
    

    
}elseif($action == 'setindex'){
      $name       = param('name');
      $module_index = $conf['module_index'];
      $default = 0;
      if($module_index==$name){
      	$name = 'index';
      	$default = 1;
      }
      $replace['module_index'] =  $name;
      file_replace_var(DATA_PATH.'config/conf.default.php', $replace);
       if($default){
         message(0, '已还原为默认首页');
       }else{
       	 message(0, '设置完成');
       }
      
}