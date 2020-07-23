<?php
/**
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-03-30 18:27:46
 * @version 1.0.0
 */
!defined('DEBUG') and exit('Access Denied.');

if (empty($action) || $action == 'list') {

    $dir_list = get_dir(PLUGIN_PATH);
    $plugins_list = array();
    foreach ($dir_list as $v) {

        $pluginfile = PLUGIN_PATH . $v . '/' . $v . '.php';

        if (file_exists($pluginfile)) {
            include_once $pluginfile;
            $object     = new $v();
            $addon_info = $object->addonInfo();

            $info = db_find_one('plugins', array('name' => $v));

            if (!empty($info)) {
                $addon_info['is_install'] = 1;
                $addon_info               = $addon_info + $info;
            } else {
                $addon_info['is_install'] = 0;
            }
            $addon_info['has_config'] = is_file(PLUGIN_PATH . strtolower($v) . '/' . 'config.php') ? 1 : 0;
            $addon_info['has_admin'] = is_file(PLUGIN_PATH . strtolower($v) . '/' . 'admin.php') ? 1 : 0;
           

            $plugins_list[] = $addon_info;
        }
    }
    include ADMIN_PATH . "view/plugins_list.html";

} else if ($action == 'install') {

    $install    = param('install');
    $name       = param('name');
    $pluginfile = PLUGIN_PATH . $name . '/' . $name . '.php';
    if ($install) {
        message(-1, '插件已经安装过');
    }

    if (file_exists($pluginfile)) {
        include_once $pluginfile;
        $object = new $name();

        $object->install();
        message(0, '插件安装完成');
    } else {
        message(-1, '未找到该插件安装文件');
    }
} else if ($action == 'uninstall') {

    $id    = param('id');
    $name       = param('name');
    $pluginfile = PLUGIN_PATH . $name . '/' . $name . '.php';
    

    if (file_exists($pluginfile)) {
        include_once $pluginfile;
        $object = new $name();

        $object->uninstall();
        db_delete('plugins',array('id'=>$id));
        message(0, '插件卸载完成');
    } else {
        message(-1, '未找到该插件安装文件');
    }

} else if ($action == 'cstatus') {
    if ($method == 'POST') {
        $id      = param('id');
        $field   = param('field');
        $value   = param('value');
        $message = param('message');
        $result  = db_update('plugins',array('id'=>$id), [$field => $value, 'update_time' => $time]);
        if ($result) {
            message(0, $message . '成功');
        } else {
            message(-1, $message . '失败');
        }

    }
} else if ($action == 'config') {
    
    $id     = param('id');
    $addon = db_find_one('plugins',array('id'=>$id));
    if ($method == 'POST') {
    

       $config =  json_encode(param('config'));
       
         $result  = db_update('plugins',array('id'=>$id), ['config' => $config, 'update_time' => $time]);
        if ($result) {
            message(0, '配置成功');
        } else {
            message(-1, '配置失败');
        }


    }else{

       

       $db_config = $addon['config'];
      
        $config = include PLUGIN_PATH . strtolower($addon['name']) . '/' . 'config.php';
       
        if($db_config){
            $db_config = json_decode($db_config, true);
           
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
        $addon['config'] =$config;

    include ADMIN_PATH . "view/plugins_config.html";
}
} else if ($action == 'manage') {
$plugin_name     = param('plugin_name');
$plugin_action = param('action');
if(!empty($plugin_action)){
    $plugin->run($plugin_name,$plugin_action);
}
include PLUGIN_PATH . strtolower($plugin_name) . '/' . 'admin.php';
}
