<?php





function arr_strtoinval($array){

foreach ($array as $key => $value) {
    $int = intval($value);
    if($int<0){
        $int = 0;
    }
    $array[$key]=$int;
}

return $array;
}

function send_sys_message($uid,$content){
$data['uid']=0;
$data['touid']=$uid;
$data['content']=$content;
$data['type']=1;
$data['status']=1;
$data['create_time']=time();
$r = db_create('message',$data);
return $r;
}
function wi_check_field($name, $data, $field_arr, $type = 'add', $table_key = 'id',$info=array())
{
/*     $field_arr = array(
'name'=>array(array('length','菜单名称在3到10个字符之间',array(3,10)),array('empty','菜单名称为空'),array('uniqid','已存在该菜单名称'),array('func','菜单名称非字符','is_string',false)),
'icon'=>array(array('empty','图标为空')),
'module'=>array(array('empty','模块名称为空')),
);*/
if($type=='edit'&&empty($data[$table_key])){
  return array(false, '主键缺失',$table_key);
}
if($type=='edit'&&empty($info)){
  return array(false, '请传入原有信息info');
}



    foreach ($field_arr as $key => $value) {

if($type=='edit'){
     
       if($info[$key]==$data[$key]&&$key!=$table_key){
       
        unset($data[$key]);
        unset($field_arr[$key]);
        continue;
       }         
}

        foreach ($value as $k => $v) {

            if ($v[0] == 'length') {
                $length = xn_strlen($data[$key]);
                if ($length < $v[2][0] || $length > $v[2][1]) {
                    //默认是包含该长度
                    return array(false, $v[1],$key);
                }
            }
            if ($v[0] == 'empty') {

                if (empty($data[$key])) {
                    return array(false, $v[1],$key);
                }

            }
    

            if ($v[0] == 'func') {
                //如果存在第三个元素并且为true，则表示函数需要传递两个参数，第二个参数为整个数据
                if (isset($v[3])&&$v[3]) {
                    $r = call_user_func($v[2], $data[$key], $data);
                } else {
                    $r = call_user_func($v[2], $data[$key]);
                }

                if (!$r) {
                    return array(false, $v[1],$key);
                }
            }
            if ($v[0] == 'uniqid') {
                if ($type == 'add') {
                    $result = db_find($name, array($key => $data[$key]));
                } else {
                    $result = db_find_one($name, array($key => $data[$key],$table_key => array('!='=>$data[$table_key])));

                }
                if ($result) {
                    return array(false, $v[1],$key);
                }
            }

        }

    }

    return array(true, $data);
}
function array_map_arr($array,$name){
return array_map(function ($array) use ($name) {return $array[$name];},$array);
}
function redirect($url){
header('Location:'.$url);
exit();
}
function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE)
{
    $label = (null === $label) ? '' : rtrim($label) . ':';
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    if (IN_CMD) {
        $output = PHP_EOL . $label . $output . PHP_EOL;
    } else {
        if (!extension_loaded('xdebug')) {
            $output = htmlspecialchars($output, $flags);
        }
        $output = '<pre>' . $label . $output . '</pre>';
    }
    if ($echo) {
        echo($output);
        return;
    } else {
        return $output;
    }

}
function xn_message($code, $message)
{
    $ajax = $_SERVER['ajax'];
    echo $ajax ? xn_json_encode(array('code' => $code, 'message' => $message)) : $message;
    exit;
}

function xn_log_post_data()
{
    $method = $_SERVER['method'];
    if ($method != 'POST') {
        return;
    }

    $post                                                  = $_POST;
    isset($post['password']) and $post['password']         = '******'; // 干掉密码信息
    isset($post['password_new']) and $post['password_new'] = '******'; // 干掉密码信息
    isset($post['password_old']) and $post['password_old'] = '******'; // 干掉密码信息

    xn_log(xn_json_encode($post), 'post_data');
}
function my_error_handler($errno,$errstr,$errfile,$errline)
{
   //如果不是管理员就过滤实际路径
    global $conf;
        $errfile = str_replace(getcwd(), "", $errfile);
        $errstr = str_replace(getcwd(), "", $errstr);
   
 
    switch ($errno) {
        case E_ERROR:
           // echo "ERROR: [ID $errno] $errstr (Line: $errline of $errfile)";
            //echo "程序已经停止运行，请联系管理员。";
            //遇到Error级错误时退出脚本
            exit;
        break;
        case E_WARNING:
            //echo "WARNING: [ID $errno] $errstr (Line: $errline of $errfile)";
        break;
        default:
        //不显示Notice级的错误
        break;
    }
    $time              = $_SERVER['time'];
    $ajax              = $_SERVER['ajax'];
    IN_CMD and $errstr = str_replace('<br>', "\n", $errstr);
   

    $subject = "Error[$errno]: $errstr, File: $errfile, Line: $errline";
    $message = array();
    xn_log($subject, 'php_error'); // 所有PHP错误报告都记录日志

    $arr = debug_backtrace();
    array_shift($arr);
    foreach ($arr as $v) {
        $args = '';
        if (!empty($v['args']) && is_array($v['args'])) {
            foreach ($v['args'] as $v2) {
                $args .= ($args ? ' , ' : '') . (is_array($v2) ? 'array(' . count($v2) . ')' : (is_object($v2) ? 'object' : $v2));
            }
        }

        !isset($v['file']) and $v['file'] = '';
        !isset($v['line']) and $v['line'] = '';

        $message[]                        = "File: $v[file], Line: $v[line], $v[function]($args) ";
    }
    $message = str_replace(getcwd(), "", $message);
   
    $txt  = $subject . "\r\n" . implode("\r\n", $message);
    $html = $s = "<div class=\"fieldset notice\">
            <style>
            body{background:#f0eeef;}
            .fieldset{position:relative;height:326px;width:700px;background:url('/public/common/images/404bg.png') no-repeat;margin:0 auto;}
                .text-center{text-align:center;}
                .showinfo{position:absolute;left:0;top:40%;}
                .error_p_con{}
                #showerror{color:#1e8a73;text-decoration:none;}
                #errordiv{display:none;}
            </style>
          
            <div class='showinfo'>
            <h1>5iSNS 内容付费系统</h1>
          

           <p class=\"error_p_con\">官网地址：www.5isns.com</p>
           <p class=\"error_p_con\">※ 加入官方讨论群726549695或者官方社区寻求答案  <a href='javascript:void(0);' id='showerror'>点击显示错误信息</a></p>
<p style=\"color:#bbb;\" id='errordiv' >错误提示：".$subject."</p>
            
           </div>
           
        </div>
<script>
var flag=0;
document.getElementById('showerror').onclick = function() {
    
    if(flag==0){
        document.getElementById('errordiv').style.display='block';
        flag=1;
        }else{
        document.getElementById('errordiv').style.display='none';  
        flag=0;   
        }
    
}


</script>
        ";
    echo $html;
    

    exit();
}
// 中断流程很危险！可能会导致数据问题，线上模式不允许中断流程！
function error_handle($errno, $errstr, $errfile, $errline)
{

    // PHP 内部默认处理
    if (DEBUG == 0) {
        return false;
    }

    // 如果放在 register_shutdown_function 里面，文件句柄会被关闭，然后这里就写入不了文件了！
    // if(strpos($s, 'error_log(') !== FALSE) return TRUE;
    $time              = $_SERVER['time'];
    $ajax              = $_SERVER['ajax'];
    IN_CMD and $errstr = str_replace('<br>', "\n", $errstr);
    $errfile=str_replace(getcwd(),"",$errfile);
    $errstr=str_replace(getcwd(),"",$errstr);

    $subject = "Error[$errno]: $errstr, File: $errfile, Line: $errline";
    $message = array();
    xn_log($subject, 'php_error'); // 所有PHP错误报告都记录日志

    $arr = debug_backtrace();

    array_shift($arr);
    foreach ($arr as $v) {
        $args = '';
        if (!empty($v['args']) && is_array($v['args'])) {
            foreach ($v['args'] as $v2) {
                $args .= ($args ? ' , ' : '') . (is_array($v2) ? 'array(' . count($v2) . ')' : (is_object($v2) ? 'object' : $v2));
            }
        }

        !isset($v['file']) and $v['file'] = '';
        !isset($v['line']) and $v['line'] = '';
        $message[]                        = "File: $v[file], Line: $v[line], $v[function]($args) ";
    }
    $message=str_replace(getcwd(),"",$message);
    $txt  = $subject . "\r\n" . implode("\r\n", $message);
    $html = $s = "<div class=\"fieldset notice\">
            <style>
                .notice{}
                .error_p_con{}
                .bold{font-weight:600;}
            </style>
            <b>$subject</b>
            <div class='showinfo'>" . implode("<br>\r\n", $message) . "</div>
           
        </div>";
    echo ($ajax || IN_CMD) ? $txt : $html;
      


    DEBUG == 2 and xn_log($txt, 'debug_error');
    exit();//return true;
}

// 使用全局变量记录错误信息
function xn_error($no, $str, $return = false)
{
    global $errno, $errstr;
    $errno  = $no;
    $errstr = $str;
    return $return;
}

/*
param(1);
param(1, '');
param(1, 0);
param(1, array());
param(1, array(''));
param(1, array(0));
 */
function param($key, $defval = '', $htmlspecialchars = true, $addslashes = false)
{
    if (!isset($_REQUEST[$key]) || ($key === 0 && empty($_REQUEST[$key]))) {
        if (is_array($defval)) {
            return array();
        } else {
            return $defval;
        }
    }

    $val = $_REQUEST[$key];
    $val = param_force($val, $defval, $htmlspecialchars, $addslashes);

    return $val;
}
function param_post($key = '', $htmlspecialchars = true, $addslashes = false)
{
    if (!empty($key)) {
        $val = $_POST[$key];
    } else {
        $val = $_POST;
    }
    if (!isset($val) || ($key === 0 && empty($val))) {
        return array();

    }

    $val = param_post_force($val, $htmlspecialchars, $addslashes);

    return $val;
}
// 安全获取单词类参数
function param_word($key, $len = 32)
{
    $s = param($key);
    $s = xn_safe_word($s, $len);
    return $s;
}

function param_base64($key, $len = 0)
{
    $s = param($key, '', false);
    if (empty($s)) {
        return '';
    }

    $s          = substr($s, strpos($s, ',') + 1);
    $s          = base64_decode($s);
    $len and $s = substr($s, 0, $len);
    return $s;
}

function param_json($key)
{
    $s = param($key, '', false);
    if (empty($s)) {
        return '';
    }

    $arr = xn_json_decode($s);
    return $arr;
}

function param_url($key)
{
    $s   = param($key, '', false);
    $arr = xn_urldecode($s);
    return $arr;
}

// 安全过滤字符串，仅仅保留 [a-zA-Z0-9_]
function xn_safe_word($s, $len)
{
    $s = preg_replace('#\W+#', '', $s);
    $s = substr($s, 0, $len);
    return $s;
}

/*
仅支持一维数组的类型强制转换。
param_force($val);
param_force($val, '');
param_force($val, 0);
param_force($arr, array());
param_force($arr, array(''));
param_force($arr, array(0));
 */
function param_post_force($val, $htmlspecialchars = true, $addslashes = false)
{

    $get_magic_quotes_gpc = _SERVER('get_magic_quotes_gpc');
    if (is_array($val)) {
        foreach ($val as $k => $v) {

            if (is_string($v)) {

                $addslashes and !$get_magic_quotes_gpc && $val[$k] = addslashes($v);
                !$addslashes and $get_magic_quotes_gpc && $val[$k] = stripslashes($v);
                $htmlspecialchars and $val[$k]                     = htmlspecialchars($v);

            } else {
               
if(is_array($v)){
   foreach ($v as $k1 => $v1) {
           if (is_string($v1)) {

                $addslashes and !$get_magic_quotes_gpc && $v[$k1] = addslashes($v1);
                !$addslashes and $get_magic_quotes_gpc && $v[$k1] = stripslashes($v1);
                $htmlspecialchars and $v[$k1]                     = htmlspecialchars($v1);

            } else {

               $v[$k1] = intval($v1); 
            }
   }
}else{
   $val[$k] = intval($v); 
}
 
                
            }
           
        }
    } else {
        if (is_string($val)) {

            $addslashes and !$get_magic_quotes_gpc && $val = addslashes($val);
            !$addslashes and $get_magic_quotes_gpc && $val = stripslashes($val);
            $htmlspecialchars and $val                     = htmlspecialchars($val);
        } else {
            $val = intval($val);
        }

    }

    return $val;
}
function param_force($val, $defval, $htmlspecialchars = true, $addslashes = false)
{
    $get_magic_quotes_gpc = _SERVER('get_magic_quotes_gpc');
    if (is_array($defval)) {
        $defval = empty($defval) ? '' : $defval[0]; // 数组的第一个元素，如果没有则为空字符串
        if (is_array($val)) {
            foreach ($val as &$v) {
                if (is_array($v)) {
                    $v = $defval;
                } else {
                    if (is_string($defval)) {
                        //$v = trim($v);
                        $addslashes and !$get_magic_quotes_gpc && $v = addslashes($v);
                        !$addslashes and $get_magic_quotes_gpc && $v = stripslashes($v);
                        $htmlspecialchars and $v                     = htmlspecialchars($v);
                    } else {
                        $v = intval($v);
                    }
                }
            }
        } else {
            return array();
        }
    } else {
        if (is_array($val)) {
            //$val = $defval;
        } else {
            if (is_string($defval)) {
                //$val = trim($val);
                $addslashes and !$get_magic_quotes_gpc && $val = addslashes($val);
                !$addslashes and $get_magic_quotes_gpc && $val = stripslashes($val);
                $htmlspecialchars and $val                     = htmlspecialchars($val);
            } else {
                $val = intval($val);
            }
        }
    }
    return $val;
}

/*
lang('mobile_length_error');
lang('mobile_length_error', array('mobile'=>$mobile));
 */
function lang($key, $arr = array())
{
    $lang = $_SERVER['lang'];
    if (!isset($lang[$key])) {
        return 'lang[' . $key . ']';
    }

    $s = $lang[$key];
    if (!empty($arr)) {
        foreach ($arr as $k => $v) {
            $s = str_replace('{' . $k . '}', $v, $s);
        }
    }
    return $s;
}

function jump($message, $url = '', $delay = 3)
{
    $ajax = $_SERVER['ajax'];
    if ($ajax) {
        return $message;
    }

    if (!$url) {
        return $message;
    }

    $url == 'back' and $url = 'javascript:history.back()';
    $htmladd                = '<script>setTimeout(function() {window.location=\'' . $url . '\'}, ' . ($delay * 1000) . ');</script>';
    return '<a href="' . $url . '">' . $message . '</a>' . $htmladd;
}

function xn_strlen($s)
{
    return mb_strlen($s, 'UTF-8');
}
function remove_xss($html) {
    $html = htmlspecialchars_decode($html);
    preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

    $searchs[] = '<';
    $replaces[] = '&lt;';
    $searchs[] = '>';
    $replaces[] = '&gt;';

    if ($ms[1]) {
        $allowtags = 'video|attach|img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|strike|pre|code|embed';
        $ms[1] = array_unique($ms[1]);
        foreach ($ms[1] as $value) {
            $searchs[] = "&lt;".$value."&gt;";

            $value = str_replace('&amp;', '_uch_tmp_str_', $value);
            $value = string_htmlspecialchars($value);
            $value = str_replace('_uch_tmp_str_', '&amp;', $value);

            $value = str_replace(array('\\', '/*'), array('.', '/.'), $value);
            $skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
                    'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
                    'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
                    'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
                    'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
                    'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
                    'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
                    'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
                    'onsubmit','onunload','javascript','script','eval','behaviour','expression');
            $skipstr = implode('|', $skipkeys);
            $value = preg_replace(array("/($skipstr)/i"), '.', $value);
            if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
                $value = '';
            }
            $replaces[] = empty($value) ? '' : "<" . str_replace('&quot;', '"', $value) . ">";
        }
    }
    $html = str_replace($searchs, $replaces, $html);
    $html=htmlspecialchars($html);
    return $html;
}

function string_htmlspecialchars($string, $flags = null) {
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = string_htmlspecialchars($val, $flags);
        }
    } else {
        if ($flags === null) {
            $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
            if (strpos($string, '&amp;#') !== false) {
                $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
            }
        } else {
            if (PHP_VERSION < '5.4.0') {
                $string = htmlspecialchars($string, $flags);
            } else {
                if (!defined('CHARSET') || (strtolower(CHARSET) == 'utf-8')) {
                    $charset = 'UTF-8';
                } else {
                    $charset = 'ISO-8859-1';
                }
                $string = htmlspecialchars($string, $flags, $charset);
            }
        }
    }

    return $string;
}
function string_remove_xss($val)
{
    
    $val = htmlspecialchars_decode($val);
    $val = strip_tags($val, '<img><attach><u><p><b><i><a><strike><pre><code><font><blockquote><span><ul><li><table><tbody><tr><td><ol><iframe><embed>');

    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    

    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';



    for ($i = 0; $i < strlen($search); $i++) {
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);
        $val = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $val);
    } 

    $ra1 = array('embed', 'iframe', 'frame', 'script', 'ilayer', 'layer', 'javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'object', 'frameset', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true;
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(�{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
            $val = preg_replace($pattern, $replacement, $val);
            if ($val_before == $val) {
                $found = false;
            }
        }
    }
    $val=htmlspecialchars($val);
    return $val;
}
/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {



    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    //截取内容时去掉图片，仅保留文字
    $strlen = mb_strlen($str,'utf-8');
    if($strlen<$length){
        $suffix=false;
    }
    return $suffix ? $slice.'...' : $slice;
}
function clearcontent($content){

    $content=htmlspecialchars_decode($content);
//在这里先处理不需要的div及a链接等
    $content=preg_replace("/(<a [^>]*>(.*)<\/a>)/i","",$content);
    $content=preg_replace("/\[pic[^\]]*\]([^\[]*)\[\/pic\]/i","",$content);
    $content=preg_replace("/\[attach[^\]]*\]([^\[]*)\[\/attach\]/i","",$content);

    $content=preg_replace("/&lt;/i","<",$content);


    $content=preg_replace("/&gt;/i",">",$content);

    $content=preg_replace("/&amp;/i","&",$content);
    

    $content=strip_tags($content);
    $content=preg_replace("/\n/is",'',$content);
    $content=preg_replace("/\r\n/is",'',$content);
    
    $content=preg_replace('/ |　/is','',$content);
    $content=preg_replace('/&nbsp;/is','',$content);
    $content=preg_replace('/&emsp;/is','',$content);
    
    return $content;
}


function clearHtml($content){
    $content = preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/i',"",$content);
    $content=preg_replace("/<a[^>]*>/i","",$content);
    $content=preg_replace("/<\/a>/i","",$content);
    $content=preg_replace("/<p>/i","",$content);
    $content=preg_replace("/<\/p>/i","",$content);
    $content=preg_replace("/<div[^>]*>/i","",$content);
    $content=preg_replace("/<\/div>/i","",$content);
    $content=preg_replace("/<!--[^>]*-->/i","",$content);//注释内容
    $content=preg_replace("/style=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/class=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/id=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/lang=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/width=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/height=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/border=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/face=.+?['|\"]/i",'',$content);//去除样式
    $content=preg_replace("/face=.+?['|\"]/",'',$content);//去除样式 只允许小写 正则匹配没有带 i 参数
    $content=preg_replace("/\n/is",'',$content);
    $content=preg_replace("/\r\n/is",'',$content);
    
    $content=preg_replace('/ |　/is','',$content);
    $content=preg_replace('/&nbsp;/is','',$content);
    $content=preg_replace('/&emsp;/is','',$content);
    $content=strip_tags($content);
    return $content;
}
function cutstr_html($string,$length=0,$ellipsis='…'){

    $string=strip_tags($string);
    $string=preg_replace("/\n/is",'',$string);
    $string=preg_replace("/\r\n/is",'',$string);

    $string=preg_replace('/ |　/is','',$string);
    $string=preg_replace('/&nbsp;/is','',$string);
    $string=preg_replace('/&emsp;/is','',$string);

    if(mb_strlen($string,'utf-8')<=$length){
        $ellipsis='';
    }
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/",$string,$string);
    if(is_array($string)&&!empty($string[0])){
        if(is_numeric($length)&&$length){



            $string=join('',array_slice($string[0],0,$length)).$ellipsis;
        }else{
            $string=implode('',$string[0]);
        }
    }else{
        $string='';
    }
    return $string;
}
/*私有文章简介截取方法*/
function wi_getSummary($content,$s = 0,$e = 150,$char = 'utf-8')
    {
        if (empty($content)) {
            return null;
        }

        $content = clearcontent($content);
        
   

        return (mb_substr($content, $s, $e, $char));
    }

    /*私有文章图片提取方法*/
function wi_getImg($str)
    {
        if (empty($str)) {
            return null;
        }
        $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern,$str,$match);
        return $match[1][0];
    }
function xn_substr($s, $start, $len)
{
    return mb_substr($s, $start, $len, 'UTF-8');
}

// txt 转换到 html
function xn_txt_to_html($s)
{
    $s = htmlspecialchars($s);
    $s = str_replace(" ", '&nbsp;', $s);
    $s = str_replace("\t", ' &nbsp; &nbsp; &nbsp; &nbsp;', $s);
    $s = str_replace("\r\n", "\n", $s);
    $s = str_replace("\n", '<br>', $s);
    return $s;
}

function xn_urlencode($s)
{
    $s = urlencode($s);
    $s = str_replace('_', '_5f', $s);
    $s = str_replace('-', '_2d', $s);
    $s = str_replace('.', '_2e', $s);
    $s = str_replace('+', '_2b', $s);
    $s = str_replace('=', '_3d', $s);
    $s = str_replace('%', '_', $s);
    return $s;
}

function xn_urldecode($s)
{
    $s = str_replace('_', '%', $s);
    $s = urldecode($s);
    return $s;
}

function xn_json_encode($data, $pretty = false, $level = 0)
{
    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    $tab  = $pretty ? str_repeat("\t", $level) : '';
    $tab2 = $pretty ? str_repeat("\t", $level + 1) : '';
    $br   = $pretty ? "\r\n" : '';
    switch ($type = gettype($data)) {
        case 'NULL':
            return 'null';
        case 'boolean':
            return ($data ? 'true' : 'false');
        case 'integer':
        case 'double':
        case 'float':
            return $data;
        case 'string':
            $data = '"' . str_replace(array('\\', '"'), array('\\\\', '\\"'), $data) . '"';
            $data = str_replace("\r", '\\r', $data);
            $data = str_replace("\n", '\\n', $data);
            $data = str_replace("\t", '\\t', $data);
            return $data;
        case 'object':
            $data = get_object_vars($data);
        case 'array':
            $output_index_count = 0;
            $output_indexed     = array();
            $output_associative = array();
            foreach ($data as $key => $value) {
                $output_indexed[]     = xn_json_encode($value, $pretty, $level + 1);
                $output_associative[] = $tab2 . '"' . $key . '":' . xn_json_encode($value, $pretty, $level + 1);
                if ($output_index_count !== null && $output_index_count++ !== $key) {
                    $output_index_count = null;
                }
            }
            if ($output_index_count !== null) {
                return '[' . implode(",$br", $output_indexed) . ']';
            } else {
                return "{{$br}" . implode(",$br", $output_associative) . "{$br}{$tab}}";
            }
        default:
            return ''; // Not supported
    }
}

function xn_json_decode($json)
{
    $json = trim($json, "\xEF\xBB\xBF");
    $json = trim($json, "\xFE\xFF");
    return json_decode($json, 1);
}

// ---------------------> encrypt function end

function pagination_tpl($url, $text, $active = '')
{
    global $g_pagination_tpl;
    empty($g_pagination_tpl) and $g_pagination_tpl = '<li class="page-item{active}"><a href="{url}" class="page-link">{text}</a></li>';
    return str_replace(array('{url}', '{text}', '{active}'), array($url, $text, $active), $g_pagination_tpl);
}

// bootstrap 翻页，命名与 bootstrap 保持一致
function pagination($url, $totalnum, $page, $pagesize = 20)
{
    $totalpage = ceil($totalnum / $pagesize);
    if ($totalpage < 2) {
        return '';
    }

    $page    = min($totalpage, $page);
    $shownum = 5; // 显示多少个页 * 2

    $start = max(1, $page - $shownum);
    $end   = min($totalpage, $page + $shownum);

    // 不足 $shownum，补全左右两侧
    $right               = $page + $shownum - $totalpage;
    $right > 0 && $start = max(1, $start -= $right);
    $left                = $page - $shownum;
    $left < 0 && $end    = min($totalpage, $end -= $left);

    $s = '';
    $page != 1 && $s .= pagination_tpl(str_replace('pagenum', $page - 1, $url), '<i class="fa fa-angle-double-left"></i>', '');
    if ($start > 1) {
        $s .= pagination_tpl(str_replace('pagenum', 1, $url), '1 ' . ($start > 2 ? '...' : ''));
    }

    for ($i = $start; $i <= $end; $i++) {
        $s .= pagination_tpl(str_replace('pagenum', $i, $url), $i, $i == $page ? ' active' : '');
    }
    if ($end != $totalpage) {
        $s .= pagination_tpl(str_replace('pagenum', $totalpage, $url), ($totalpage - $end > 1 ? '...' : '') . $totalpage);
    }

    $page != $totalpage && $s .= pagination_tpl(str_replace('pagenum', $page + 1, $url), '<i class="fa fa-angle-double-right"></i>');
    return $s;
}

// 简单的上一页，下一页，比较省资源，不用count(), 推荐使用，命名与 bootstrap 保持一致
function pager($url, $totalnum, $page, $pagesize = 20)
{
    $totalpage = ceil($totalnum / $pagesize);
    if ($totalpage < 2) {
        return '';
    }

    $page = min($totalpage, $page);

    $s = '';
    $page > 1 and $s .= '<li><a href="' . str_replace('pagenum', $page - 1, $url) . '">上一页</a></li>';
    $s .= " $page / $totalpage ";
    $totalnum >= $pagesize and $page != $totalpage and $s .= '<li><a href="' . str_replace('pagenum', $page + 1, $url) . '">下一页</a></li>';
    return $s;
}

function mid($n, $min, $max)
{
    if ($n < $min) {
        return $min;
    }

    if ($n > $max) {
        return $max;
    }

    return $n;
}
function humanmiao($dTime) {
    $dTime = intval($dTime);
    $lan = array(
       
        'day'    => '天',
        'hour'   => '小时',
        'minute' => '分钟',
        'second' => '秒',
    );
     if ($dTime > 86400) {
        $days = floor($dTime/86400);
        $hours = floor(($dTime-$days*86400)/3600);
        $minus = floor(($dTime-$days*86400-$hours*3600)/60);
        $miaos = $dTime-$days*86400-$hours*3600-$minus*60;
        return $days . $lan['day'].$hours . $lan['hour'].$minus . $lan['minute'].$miaos . $lan['second'];
    } elseif ($dTime > 3600) {
       
        $hours = floor($dTime/3600);
        $minus = floor(($dTime-$hours*3600)/60);
        $miaos = $dTime-$hours*3600-$minus*60;
        return $hours . $lan['hour'].$minus . $lan['minute'].$miaos . $lan['second'];
    } elseif ($dTime > 60) {
       
        $minus = floor($dTime/60);
        $miaos = $dTime-$minus*60;
        return $minus . $lan['minute'].$miaos . $lan['second'];
    } else {
       
        
        return $dTime . $lan['second'];
    }
    

}
function humandate($timestamp)
{
    $time = $_SERVER['time'];
   

    static $custom_humandate = null;
    if ($custom_humandate === null) {
        $custom_humandate = function_exists('custom_humandate');
    }

    if ($custom_humandate) {
        return custom_humandate($timestamp);
    }

    $seconds             = $time - $timestamp;
   
    $lan = array(
        'month_ago'  => '月前',
        'day_ago'    => '天前',
        'hour_ago'   => '小时前',
        'minute_ago' => '分钟前',
        'second_ago' => '秒前',
    );
    if ($seconds > 31536000) {
        return date('Y-n-j', $timestamp);
    } elseif ($seconds > 2592000) {
        return floor($seconds / 2592000) . $lan['month_ago'];
    } elseif ($seconds > 86400) {
        return floor($seconds / 86400) . $lan['day_ago'];
    } elseif ($seconds > 3600) {
        return floor($seconds / 3600) . $lan['hour_ago'];
    } elseif ($seconds > 60) {
        return floor($seconds / 60) . $lan['minute_ago'];
    } else {
        return $seconds . $lan['second_ago'];
    }
}

function humannumber($num)
{

    static $custom_humannumber = null;
    if ($custom_humannumber === null) {
        $custom_humannumber = function_exists('custom_humannumber');
    }

    if ($custom_humannumber) {
        return custom_humannumber($num);
    }

    $num > 100000 && $num = ceil($num / 10000) . '万';
    return $num;
}

function humansize($num)
{

    static $custom_humansize = null;
    if ($custom_humansize === null) {
        $custom_humansize = function_exists('custom_humansize');
    }

    if ($custom_humansize) {
        return custom_humansize($num);
    }

    if ($num > 1073741824) {
        return number_format($num / 1073741824, 2, '.', '') . 'G';
    } elseif ($num > 1048576) {
        return number_format($num / 1048576, 2, '.', '') . 'M';
    } elseif ($num > 1024) {
        return number_format($num / 1024, 2, '.', '') . 'K';
    } else {
        return $num . 'B';
    }
}

// 不安全的获取 IP 方式，在开启 CDN 的时候，如果被人猜到真实 IP，则可以伪造。
function ip()
{
    $conf = _SERVER('conf');
    $ip   = '127.0.0.1';

    if (empty($conf['cdn_on'])) {
        $ip = _SERVER('REMOTE_ADDR');
    } else {
        if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
            $ip = $_SERVER['HTTP_CDN_SRC_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENTIP'])) {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip  = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $arr = array_filter(explode(',', $ip));
            $ip  = trim(end($arr));
        } else {
            $ip = _SERVER('REMOTE_ADDR');
        }
    }
    return long2ip(ip2long($ip));
}

// 安全获取用户IP，信任 CDN 发过来的 X-FORWARDED-FOR
/*
function ip() {
global $conf;
$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'; // 如果有 CDN 的时候，为离服务器最近的 IP
if(empty($conf['cdn_ip']) || empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
return $ip;
} else {
// 判断 cdnip 合法性，严格过滤 HTTP_X_FORWARDED_FOR
// X-Forwarded-For: client1, proxy1, proxy2, ...
// 离服务器最最近的为最后一个 proxy2，应该在 $conf['cdn_ip'] 当中才安全可信
foreach($conf['cdn_ip'] as $cdnip) {
$pos1 = strrpos($cdnip, '.');
$pos2 = strrpos($ip, '.');
// 合法 CDN IP 段
if($ip == $cdnip || ($pos1 == $pos2 && substr($cdnip, $pos1) == '.*' && substr($cdnip, 0, $pos1) == substr($ip, 0, $pos2))) {
$userips = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['HTTP_X_REAL_IP'];
if(empty($userips)) return $ip; // 此处 CDN 未转发 userip，有错误，可能需要记录日志
$arr = array_values(array_filter(explode(',', $userips)));
return long2ip(ip2long(end($arr)));
}
}
return $ip;
}
}
 */

// 日志记录
function xn_log($s, $file = 'error')
{
    if (DEBUG == 0 && strpos($file, 'error') === false) {
        return;
    }

    $time    = $_SERVER['time'];
    $ip      = $_SERVER['ip'];
    $conf    = _SERVER('conf');
    $uid     = intval(G('uid')); // xiunophp 未定义 $uid
    $day     = date('Ym', $time); // 按照月存放，否则 Ymd 目录太多。
    $mtime   = date('Y-m-d H:i:s'); // 默认值为 time()
    $url     = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $logpath = $conf['log_path'] . $day;
    !is_dir($logpath) and mkdir($logpath, 0777, true);

    $s = str_replace(array("\r\n", "\n", "\t"), ' ', $s);
    $s = "<?php exit;?>\t$mtime\t$ip\t$url\t$uid\t$s\r\n";

    @error_log($s, 3, $logpath . "/$file.php");
}

/*
中国国情下的判断浏览器类型，简直就是五代十国，乱七八糟，对博主的收集表示感谢

参考：
http://www.cnblogs.com/wangchao928/p/4166805.html
http://www.useragentstring.com/pages/Internet%20Explorer/
https://github.com/serbanghita/Mobile-Detect/blob/master/Mobile_Detect.php

Mozilla/4.0 (compatible; MSIE 5.0; Windows NT)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)
Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2)
Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)

Win7+ie9：
Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; Tablet PC 2.0; .NET4.0E)

win7+ie11，模拟 78910 头是一样的
mozilla/5.0 (windows nt 6.1; wow64; trident/7.0; rv:11.0) like gecko

Win7+ie8：
Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; InfoPath.3)

WinXP+ie8：
Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB7.0)

WinXP+ie7：
Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)

WinXP+ie6：
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)

傲游3.1.7在Win7+ie9,高速模式:
Mozilla/5.0 (Windows; U; Windows NT 6.1; ) AppleWebKit/534.12 (KHTML, like Gecko) Maxthon/3.0 Safari/534.12

傲游3.1.7在Win7+ie9,IE内核兼容模式:
Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E)

搜狗
搜狗3.0在Win7+ie9,IE内核兼容模式:
Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)

搜狗3.0在Win7+ie9,高速模式:
Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.33 Safari/534.3 SE 2.X MetaSr 1.0

360
360浏览器3.0在Win7+ie9:
Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E)

QQ 浏览器
QQ 浏览器6.9(11079)在Win7+ie9,极速模式:
Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.41 Safari/535.1 QQBrowser/6.9.11079.201

QQ浏览器6.9(11079)在Win7+ie9,IE内核兼容模式:
Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E) QQBrowser/6.9.11079.201

阿云浏览器
阿云浏览器 1.3.0.1724 Beta 在Win7+ie9:
Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)

MIUI V5
Mozilla/5.0 (Linux; U; Android <android-version>; <location>; <MODEL> Build/<ProductLine>) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 XiaoMi/MiuiBrowser/1.0
 */
function get__browser()
{
    // 默认为 chrome 标准浏览器
    $browser = array(
        'device'  => 'pc', // pc|mobile|pad
        'name'    => 'chrome', // chrome|firefox|ie|opera
        'version' => 30,
    );
    $agent = _SERVER('HTTP_USER_AGENT');
    // 主要判断是否为垃圾 IE6789
    if (strpos($agent, 'msie') !== false || stripos($agent, 'trident') !== false) {
        $browser['name']    = 'ie';
        $browser['version'] = 8;
        preg_match('#msie\s*([\d\.]+)#is', $agent, $m);
        if (!empty($m[1])) {
            if (strpos($agent, 'compatible; msie 7.0;') !== false) {
                $browser['version'] = 8;
            } else {
                $browser['version'] = intval($m[1]);
            }
        } else {
            // 匹配兼容模式 Trident/7.0，兼容模式下会有此标志 $trident = 7;
            preg_match('#Trident/([\d\.]+)#is', $agent, $m);
            if (!empty($m[1])) {
                $trident                              = intval($m[1]);
                $trident == 4 and $browser['version'] = 8;
                $trident == 5 and $browser['version'] = 9;
                $trident > 5 and $browser['version']  = 10;
            }
        }
    }

    if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap") || stripos($agent, 'phone') || stripos($agent, 'mobile') || strpos($agent, 'ipod'))) {
        $browser['device'] = 'mobile';
    } elseif (strpos($agent, 'pad') !== false) {
        $browser['device']  = 'pad';
        $browser['name']    = '';
        $browser['version'] = '';
    } else {
        $robots = array('bot', 'spider', 'slurp');
        foreach ($robots as $robot) {
            if (strpos($agent, $robot) !== false) {
                $browser['name'] = 'robot';
                return $browser;
            }
        }
    }
    return $browser;
}

function check_browser($browser)
{
    
    if ($browser['name'] == 'ie' && $browser['version'] < 8) {
        include APP_PATH . 'common/view/browser.htm';
        exit;
    }

}

function is_robot()
{
    $agent  = _SERVER('HTTP_USER_AGENT');
    $robots = array('bot', 'spider', 'slurp');
    foreach ($robots as $robot) {
        if (strpos($agent, $robot) !== false) {
            return true;
        }
    }
    return false;
}

function browser_lang()
{
    // return 'zh-cn';
    $accept = _SERVER('HTTP_ACCEPT_LANGUAGE');
    $accept = substr($accept, 0, strpos($accept, ';'));
    if (strpos($accept, 'ko-kr') !== false) {
        return 'ko-kr';
        // } elseif(strpos($accept, 'en') !== FALSE) {
        //     return 'en';
    } else {
        return 'zh-cn';
    }
}

// 安全请求一个 URL
// ini_set('default_socket_timeout', 60);
function http_get($url, $cookie = '', $timeout = 30, $times = 3)
{
    //return '';
    //    $arr = array(
    //            'ssl' => array (
    //            'verify_peer'   => TRUE,
    //            'cafile'        => './cacert.pem',
    //            'verify_depth'  => 5,
    //            'method'      => 'GET',
    //            'timeout'      => $timeout,
    //            'CN_match'      => 'secure.example.com'
    //        )
    //    );
    if (substr($url, 0, 8) == 'https://') {
        return https_get($url, $cookie, $timeout, $times);
    }
    $arr = array(
        'http' => array(
            'method'  => 'GET',
            'timeout' => $timeout,
        ),
    );
    $stream = stream_context_create($arr);

    while ($times-- > 0) {
        $s = file_get_contents($url, null, $stream, 0, 4096000);
        
        if ($s !== false) {
            return $s;
        }

    }
    return false;
}
function http_post_file($url, $post = '',$filePath){

$ch = curl_init();

//兼容5.0-5.6版本的curl
if (class_exists('\CURLFile')) {
    $post['file'] = new \CURLFile(realpath($filePath));
} else {
    if (defined('CURLOPT_SAFE_UPLOAD')) {
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, FALSE);
    }
}

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$data = curl_exec($ch);




curl_close($ch);
}

function sock_data($url,$port=80,$t=30,$method='get',$data=null)
{
    $info=parse_url($url);
   
    $fp = fsockopen($info["host"],$port, $errno, $errstr,$t);

    // 判断是否有数据
    if(isset($data) && !empty($data))
    {
        $query = http_build_query($data); // 数组转url 字符串形式
    }else
    {
        $query=null;
    }
    if(!isset($info['path']) || empty($info['path']))
    {
       $info['path']="/";
    }
    // 判断 请求方式
    if($method=='post')
    {
        $head = "POST ".$info['path']." HTTP/1.0".PHP_EOL;
    }else
    {
        $head = "GET ".$info['path']."?".$query." HTTP/1.0".PHP_EOL;
    }

   $head .= "Host: ".$info['host'].PHP_EOL; // 请求主机地址
   $head .= "Referer: http://".$info['host'].$info['path'].PHP_EOL;

    if(isset($data) && !empty($data) && ($method=='post'))
    {
        $head .= "Content-type: application/x-www-form-urlencoded".PHP_EOL;
        $head .= "Content-Length: ".strlen(trim($query)).PHP_EOL;
        $head .= PHP_EOL;
        $head .= trim($query);
    }else
    {
        $head .= PHP_EOL;
    } 
   
    $write = fputs($fp, $head); //写入文件(可安全用于二进制文件)。 fputs() 函数是 fwrite() 函数的别名

    fclose($fp); 

 
}
function http_post_curl($url, $post = '', $cookie = '', $timeout = 30)
{
    is_array($post) and $post     = http_build_query($post);
    is_array($cookie) and $cookie = http_build_query($cookie);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 2); // 1/2
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'x-requested-with: XMLHttpRequest'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, _SERVER('HTTP_USER_AGENT'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $header = array('Content-type: application/x-www-form-urlencoded', 'X-Requested-With: XMLHttpRequest');
    if ($cookie) {
        $header[] = "Cookie: $cookie";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    (!ini_get('safe_mode') && !ini_get('open_basedir')) && curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转, 安全模式不允许
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        return xn_error(-1, 'Errno' . curl_error($ch));
    }
    if (!$data) {
        curl_close($ch);
        return '';
    }

    list($header, $data) = explode("\r\n\r\n", $data);
    $http_code           = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 301 || $http_code == 302) {
        $matches = array();
        preg_match('/Location:(.*?)\n/', $header, $matches);
        $url = trim(array_pop($matches));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $data = curl_exec($ch);
    }
    curl_close($ch);
    
    return $data;
}
function http_post($url, $post = '', $cookie = '', $timeout = 30, $times = 3)
{
    if (substr($url, 0, 8) == 'https://') {
        return https_post($url, $post, $cookie, $timeout, $times);
    }
    is_array($post) and $post     = http_build_query($post);
    is_array($cookie) and $cookie = http_build_query($cookie);
    $stream                       = stream_context_create(array('http' => array('header' => "Content-type: application/x-www-form-urlencoded\r\nx-requested-with: XMLHttpRequest\r\nCookie: $cookie\r\n", 'method' => 'POST', 'content' => $post, 'timeout' => $timeout)));
    while ($times-- > 0) {
        $s = file_get_contents($url, null, $stream, 0, 4096000);
        if ($s !== false) {
            return $s;
        }

    }
    return false;
}
function write_svg($str,$file,$path,$filename,$x,$y,$img=true){


$obj = file_get_contents($file);


if($img){
 // $obj = str_replace('</svg>','<image xlink:href.baseVal="'.$str.'" x="'.$x.'" y="'.$y.'" transform="scale(1)" height="100" width="100" /></svg>',$obj);

  $str ='<image xlink:href.baseVal="'.$str.'" x="'.$x.'" y="'.$y.'" transform="scale(1)" height="100" width="100" />';
}else{
   //$obj = str_replace('</svg>','<text style="font-size: 1.2rem;" x="'.$x.'" y="'.$y.'" transform="scale(1)" fill="rgba(0,0,0,.2)">'.$str.'</text></svg>',$obj); 
   $str ='<text style="font-size: 1.2rem;" x="'.$x.'" y="'.$y.'" transform="scale(1)" fill="rgba(0,0,0,.2)">'.$str.'</text>';
}

$obj = preg_replace('/\<use xlink\:href\=\"(.*?)\"(.)mask\=\"url\(\#mask0\)\"(.)transform\=\"matrix\((.*?)\)\"\/\>/i',$str,$obj);






$imageName =$path.$filename;

//保存到一个名叫svgimage的文件夹中，图片名称是随机生成的数字
$fp=fopen($imageName,"w+");

fputs($fp,$obj);
fclose($fp);

}
function https_get($url, $cookie = '', $timeout = 30, $times = 1)
{
    if (substr($url, 0, 7) == 'http://') {
        return http_get($url, $cookie, $timeout, $times);
    }
    return https_post($url, '', $cookie, $timeout, $times, 'GET');
}

function https_post($url, $post = '', $cookie = '', $timeout = 30, $times = 1, $method = 'POST')
{
    if (substr($url, 0, 7) == 'http://') {
        return http_post($url, $post, $cookie, $timeout, $times);
    }
    is_array($post) and $post     = http_build_query($post);
    is_array($cookie) and $cookie = http_build_query($cookie);
    $w                            = stream_get_wrappers();
    $allow_url_fopen              = strtolower(ini_get('allow_url_fopen'));
    $allow_url_fopen              = (empty($allow_url_fopen) || $allow_url_fopen == 'off') ? 0 : 1;
    if (extension_loaded('openssl') && in_array('https', $w) && $allow_url_fopen) {
        $stream = stream_context_create(array('http' => array('header' => "Content-type: application/x-www-form-urlencoded\r\nx-requested-with: XMLHttpRequest\r\nCookie: $cookie\r\n", 'method' => $method, 'content' => $post, 'timeout' => $timeout)));
        $s      = file_get_contents($url, null, $stream, 0, 4096000);
        return $s;
    } elseif (!function_exists('curl_init')) {
        return xn_error(-1, 'server not installed curl.');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 2); // 1/2
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'x-requested-with: XMLHttpRequest'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, _SERVER('HTTP_USER_AGENT'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在，默认可以省略
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $header = array('Content-type: application/x-www-form-urlencoded', 'X-Requested-With: XMLHttpRequest');
    if ($cookie) {
        $header[] = "Cookie: $cookie";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    (!ini_get('safe_mode') && !ini_get('open_basedir')) && curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转, 安全模式不允许
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        return xn_error(-1, 'Errno' . curl_error($ch));
    }
    if (!$data) {
        curl_close($ch);
        return '';
    }

    list($header, $data) = explode("\r\n\r\n", $data);
    $http_code           = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 301 || $http_code == 302) {
        $matches = array();
        preg_match('/Location:(.*?)\n/', $header, $matches);
        $url = trim(array_pop($matches));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $data = curl_exec($ch);
    }
    curl_close($ch);
    return $data;
}

// 多线程抓取数据，需要CURL支持，一般在命令行下执行，经过测试，会导致 CPU 100%。
function http_multi_get($urls)
{
    // 如果不支持，则转为单线程顺序抓取
    $data = array();
    if (!function_exists('curl_multi_init')) {
        foreach ($urls as $k => $url) {
            $data[$k] = https_get($url);
        }
        return $data;
    }

    $multi_handle = curl_multi_init();
    foreach ($urls as $i => $url) {
        $conn[$i] = curl_init($url);
        curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
        $timeout = 3;
        curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT, $timeout); // 超时 seconds
        curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, 1);
        //curl_easy_setopt(curl, CURLOPT_NOSIGNAL, 1);
        curl_multi_add_handle($multi_handle, $conn[$i]);
    }
    do {
        $mrc = curl_multi_exec($multi_handle, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active and $mrc == CURLM_OK) {
        if (curl_multi_select($multi_handle) != -1) {
            do {
                $mrc = curl_multi_exec($multi_handle, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }
    foreach ($urls as $i => $url) {
        $data[$i] = curl_multi_getcontent($conn[$i]);
        curl_multi_remove_handle($multi_handle, $conn[$i]);
        curl_close($conn[$i]);
    }
    return $data;
}

// 将变量写入到文件，根据后缀判断文件格式，先备份，再写入，写入失败，还原备份
function file_replace_var($filepath, $replace = array(), $pretty = false)
{
    
    $ext = file_ext($filepath);
    if ($ext == 'php') {
        $arr = include $filepath;
        $arr = array_merge($arr, $replace);
        $s   = "<?php\r\nreturn " . var_export($arr, true) . ";\r\n?>";
        // 备份文件
       file_backup($filepath);
        $r = file_put_contents_try($filepath, $s);
        $r != strlen($s) ? file_backup_restore($filepath) : file_backup_unlink($filepath);
        return $r;
    } elseif ($ext == 'js' || $ext == 'json') {
        $s   = file_get_contents_try($filepath);
        $arr = xn_json_decode($s);
        if (empty($arr)) {
            return false;
        }

        $arr = array_merge($arr, $replace);
        $s   = xn_json_encode($arr, $pretty);
        file_backup($filepath);
        $r = file_put_contents_try($filepath, $s);
        $r != strlen($s) ? file_backup_restore($filepath) : file_backup_unlink($filepath);
        return $r;
    }
}

function file_backname($filepath)
{

    $dirname = dirname($filepath);
    //$filename = file_name($filepath);
    $filepre = file_pre($filepath);
    $fileext = file_ext($filepath);
    $s       = "$filepre.backup.$fileext";
    return $s;
}

function is_backfile($filepath)
{
    return strpos($filepath, '.backup.') !== false;
}

// 备份文件
function file_backup($filepath)
{
    $backfile = file_backname($filepath);
    if (is_file($backfile)) {
        return true;
    }
    // 备份已经存在
    $r = xn_copy($filepath, $backfile);
    clearstatcache();
    return $r && filesize($backfile) == filesize($filepath);
}

// 还原备份
function file_backup_restore($filepath)
{
    $backfile = file_backname($filepath);
    $r        = xn_copy($backfile, $filepath);
    clearstatcache();
    $r && filesize($backfile) == filesize($filepath) && xn_unlink($backfile);
    return $r;
}

// 删除备份
function file_backup_unlink($filepath)
{
    $backfile = file_backname($filepath);
    $r        = xn_unlink($backfile);
    return $r;
}

function file_get_contents_try($file, $times = 3)
{
    while ($times-- > 0) {
        $fp = fopen($file, 'rb');
        if ($fp) {
            $size = filesize($file);
            if ($size == 0) {
                return '';
            }

            $s = fread($fp, $size);
            fclose($fp);
            return $s;
        } else {
            sleep(1);
        }
    }
    return false;
}

function file_put_contents_try($file, $s, $times = 3)
{
    while ($times-- > 0) {
        $fp = fopen($file, 'wb');
        if ($fp and flock($fp, LOCK_EX)) {
            $n = fwrite($fp, $s);
            version_compare(PHP_VERSION, '5.3.2', '>=') and flock($fp, LOCK_UN);
            fclose($fp);
            clearstatcache();
            return $n;
        } else {
            sleep(1);
        }
    }
    return false;
}

// 判断一个字符串是否在另外一个字符串里面，分隔符 ,
function in_string($s, $str)
{
    if (!$s || !$str) {
        return false;
    }

    $s   = ",$s,";
    $str = ",$str,";
    return strpos($str, $s) !== false;
}

function move_upload_file($srcfile, $destfile)
{
    //$r = move_uploaded_file($srcfile, $destfile);
    $r = xn_copy($srcfile, $destfile);
    return $r;
}

// 文件后缀名，不包含 .
function file_ext($filename, $max = 16)
{
    $ext                         = strtolower(substr(strrchr($filename, '.'), 1));
    $ext                         = xn_urlencode($ext);
    strlen($ext) > $max and $ext = substr($ext, 0, $max);
    if (!preg_match('#^\w+$#', $ext)) {
        $ext = 'attach';
    }

    return $ext;
}

// 文件的前缀，不包含最后一个 .
function file_pre($filename, $max = 32)
{
    return substr($filename, 0, strrpos($filename, '.'));
}

// 获取路径中的文件名
function file_name($path)
{
    return substr($path, strrpos($path, '/') + 1);
}

// 在 header 头中发送DEBUG信息
/*function t($name = '') {
global $starttime;
header("Time $name:".substr(microtime(1) - $starttime, 0, 7));
}*/

// 获取 http://xxx.com/path/
function http_url_path()
{
    $port = _SERVER('SERVER_PORT');
    //$portadd = ($port == 80 ? '' : ':'.$port);
    $host  = _SERVER('HTTP_HOST'); // host 里包含 port
    $https = strtolower(_SERVER('HTTPS', 'off'));
    $proto = strtolower(_SERVER('HTTP_X_FORWARDED_PROTO'));
    $path  = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
    $http  = (($port == 443) || $proto == 'https' || ($https && $https != 'off')) ? 'https' : 'http';
    return "$http://$host$path/";
}

/**
 * URL format: http://www.domain.com/demo/?user-login.htm?a=b&c=d
 * URL format: http://www.domain.com/demo/?user-login.htm&a=b&c=d
 * URL format: http://www.domain.com/demo/user-login.htm?a=b&c=d
 * URL format: http://www.domain.com/demo/user-login.htm&a=b&c=d
 * array(
 *     0 => user,
 *     1 => login
 *     a => b
 *     c => d
 * )
 */

function xn_url_parse($request_url)
{
    // 处理: /demo/user-login.htm?a=b&c=d
    //http://newsns.localhost/?m=docs&c=index
    //http://newsns.localhost/docs-index.htm
    $conf = _SERVER('conf');
    $nstr = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
   // $request_url = str_replace('/?', '/', $request_url);
    if($nstr!='/'){
        $request_url = str_replace($nstr, '', $request_url);
    }
    

  
    substr($request_url, 0, 1) == '/' and $request_url = substr($request_url, 1);
    
    
    $arr = parse_url($request_url); 
    $front = array_value($arr, 'path');
    
    $url_rewrite_on=1;

    if (substr($front, -4) == '.htm') {
        $front = substr($front, 0, -4);
        $url_rewrite_on=2;
    }
    if(stripos($front,'-')!==FALSE){//判断链接中是否含有-
      
        $url_rewrite_on=2;
    }



$arr2 = array();
    if (!empty($arr['query'])) {
        parse_str($arr['query'], $arr2);
    } else {
        !empty($_GET) and $_GET = array();
    }
if($url_rewrite_on==1){


if(!empty($arr2['m'])){
  //说明有模块 
  //
   if(!in_array($arr2['m'],$conf['module_arr'])){
      $arr1 = $conf['module_index'];

   }else{
      $arr1 = $arr2['m'];
      
   }
   unset($arr2['m']);
}else{

    $arr1 = $conf['module_index'];
}
    $r = array();
    if(isset($arr2['c'])){
        $r[0]=$arr2['c'];
        unset($arr2['c']);
    }
    if(isset($arr2['a'])){
        $r[1]=$arr2['a'];
        unset($arr2['a']);
    }      
    if(isset($arr2['e'])){
        $e_arr = explode('-',$arr2['e']);
        foreach ($e_arr as $key => $value) {
            array_push($r,$value);
        }
        
        
       
        unset($arr2['e']);
    } 


array_unshift($r, $arr1);//将模块放到第一位


}else{

$r = $front ? (array) explode('-', $front) : array(); 
if(stripos($r[0],'m_')!==FALSE){

$r[0] = str_replace('m_', '', $r[0]);
if(!in_array($r[0],$conf['module_arr'])){
      $r[0] = $conf['module_index'];

}

}else{
    array_unshift($r, $conf['module_index']);//将模块放到第一位
}




}


    if ($arr2) {
        count($arr2) != count($_GET) and $_GET = $arr2;
    } else {
        !empty($_GET) and $_GET = array();
    }

    $r += $arr2;

    $_SERVER['REQUEST_URI_NO_PATH'] = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);

    return $r;
}

// 将参数添加到 URL
function xn_url_add_arg($url, $k, $v)
{
    $pos = strpos($url, '.htm');
    if ($pos === false) {
        return strpos($url, '?') === false ? $url . "&$k=$v" : $url . "?$k=$v";
    } else {
        return substr($url, 0, $pos) . '-' . $v . substr($url, $pos);
    }
}

/**
 * 支持 URL format: http://www.domain.com/user/login?a=1&b=2
 * array(
 *     0 => user,
 *     1 => login,
 *     a => 1,
 *     b => 2
 * )
 */
function xn_url_parse_path_format($s)
{
    $get                           = array();
    substr($s, 0, 1) == '/' and $s = substr($s, 1);
    $arr                           = explode('/', $s);
    $get                           = $arr;
    $last                          = array_pop($arr);
    if (strpos($last, '?') !== false) {
        $get  = $arr;
        $arr1 = explode('?', $last);
        parse_str($arr1[1], $arr2);
        $get[] = $arr1[0];
        $get   = array_merge($get, $arr2);
    }
    return $get;
}
function get_dir($dir_name)
{

    $dir_array = array();

    if (false != ($handle = opendir($dir_name))) {

        $i = 0;

        while (false !== ($file = readdir($handle))) {

            if ($file != "." && $file != ".."&&!strpos($file,".")) {

                $dir_array[$i] = $file;

                $i++;
            }
        }

        closedir($handle);
    }

    return $dir_array;
}
// 递归遍历目录
function glob_recursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

// 递归删除目录，这个函数比较危险，传参一定要小心
function rmdir_recusive($dir, $keepdir = 0)
{
    if ($dir == '/' || $dir == './' || $dir == '../') {
        return false;
    }
// 不允许删除根目录，避免程序意外删除数据。
    if (!is_dir($dir)) {
        return false;
    }

    substr($dir, -1) != '/' and $dir .= '/';

    $files = glob($dir . '*'); // +glob($dir.'.*')
    foreach (glob($dir . '.*') as $v) {
        if (substr($v, -1) != '.' && substr($v, -2) != '..') {
            $files[] = $v;
        }

    }
    $filearr = $dirarr = array();
    if ($files) {
        foreach ($files as $file) {
            if (is_dir($file)) {
                $dirarr[] = $file;
            } else {
                $filearr[] = $file;
            }
        }
    }
    if ($filearr) {
        foreach ($filearr as $file) {
            xn_unlink($file);
        }
    }
    if ($dirarr) {
        foreach ($dirarr as $file) {
            rmdir_recusive($file);
        }
    }
    if (!$keepdir) {
        xn_rmdir($dir);
    }

    return true;
}

function xn_copy($src, $dest)
{
    $r = is_file($src) ? copy($src, $dest) : false;
    return $r;
}

function xn_mkdir($dir, $mod = null, $recusive = null)
{
    $r = !is_dir($dir) ? mkdir($dir, $mod, $recusive) : false;
    return $r;
}

function xn_rmdir($dir)
{
    $r = is_dir($dir) ? rmdir($dir) : false;
    return $r;
}

function xn_unlink($file)
{
    $r = is_file($file) ? unlink($file) : false;
    return $r;
}

function xn_filemtime($file)
{
    return is_file($file) ? filemtime($file) : 0;
}

/*
实例：
xn_set_dir(123, APP_PATH.'upload');

000/000/1.jpg
000/000/100.jpg
000/000/100.jpg
000/000/999.jpg
000/001/1000.jpg
000/001/001.jpg
000/002/001.jpg
 */
function xn_set_dir($id, $dir = './')
{

    $id   = sprintf("%09d", $id);
    $s1   = substr($id, 0, 3);
    $s2   = substr($id, 3, 3);
    $dir1 = $dir . $s1;
    $dir2 = $dir . "$s1/$s2";

    !is_dir($dir1) && mkdir($dir1, 0777);
    !is_dir($dir2) && mkdir($dir2, 0777);
    return "$s1/$s2";
}

// 取得路径：001/123
function xn_get_dir($id)
{
    $id = sprintf("%09d", $id);
    $s1 = substr($id, 0, 3);
    $s2 = substr($id, 3, 3);
    return "$s1/$s2";
}

// 递归拷贝目录
function copy_recusive($src, $dst)
{
    substr($src, -1) == '/' and $src = substr($src, 0, -1);
    substr($dst, -1) == '/' and $dst = substr($dst, 0, -1);
    $dir                             = opendir($src);
    !is_dir($dst) and mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copy_recusive($src . '/' . $file, $dst . '/' . $file);
            } else {
                xn_copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// 随机字符
function xn_rand($n = 16)
{
    $str    = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
    $len    = strlen($str);
    $return = '';
    for ($i = 0; $i < $n; $i++) {
        $r = mt_rand(1, $len);
        $return .= $str[$r - 1];
    }
    return $return;
}

// 检测文件是否可写，兼容 windows
function xn_is_writable($file)
{

    if (PHP_OS != 'WINNT') {
        return is_writable($file);
    } else {
        // 如果是 windows，比较麻烦，这也只是大致检测，不够精准。
        if (is_file($file)) {
            $fp = fopen($file, 'a+');
            if (!$fp) {
                return false;
            }

            fclose($fp);
            return true;
        } elseif (is_dir($file)) {
            $tmpfile = $file . uniqid() . '.tmp';
            $r       = touch($tmpfile);
            if (!$r) {
                return false;
            }

            if (!is_file($tmpfile)) {
                return false;
            }

            xn_unlink($tmpfile);
            return true;
        } else {
            return false;
        }
    }
}

function xn_shutdown_handle()
{
}

function xn_debug_info()
{
    $db        = $_SERVER['db'];
    $starttime = $_SERVER['starttime'];
    $s         = '';
    if (DEBUG > 1) {
        $s .= '<fieldset class="fieldset small debug break-all">';
        $s .= '<p>Processed Time:' . (microtime(1) - $starttime) . '</p>';
        if (IN_CMD) {
            foreach ($db->sqls as $sql) {
                $s .= "$sql\r\n";
            }
        } else {
            $s .= "\r\n<ul>\r\n";
            foreach ($db->sqls as $sql) {
                $s .= "<li>$sql</li>\r\n";
            }
            $s .= "</ul>\r\n";
            $s .= '_REQUEST:<br>';
            $s .= xn_txt_to_html(print_r($_REQUEST, 1));
            if (!empty($_SESSION)) {
                $s .= '_SESSION:<br>';
                $s .= xn_txt_to_html(print_r($_SESSION, 1));
            }
            $s .= '';
        }
        $s .= '</fieldset>';
    }
    return $s;
}

// 解码客户端提交的 base64 数据
function base64_decode_file_data($data)
{
    if (substr($data, 0, 5) == 'data:') {
        $data = substr($data, strpos($data, ',') + 1); // 去掉 data:image/png;base64,
    }
    $data = base64_decode($data);
    return $data;
}

// 输出
function http_404()
{
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    echo '<h1>404 Not Found</h1>';
    exit;
}

// 无权限访问
function http_403()
{
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    echo '<h1>403 Forbidden</h1>';
    exit;
}

function http_location($url)
{
    header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
    header('Location:' . $url);
    exit;
}

// 获取 referer
function http_referer()
{
    $len                         = strlen(http_url_path());
    $referer                     = param('referer');
    empty($referer) and $referer = _SERVER('HTTP_REFERER');
    $referer2                    = substr($referer, $len);
    if (strpos($referer, url('user-login')) !== false || strpos($referer, url('user-logout')) !== false || strpos($referer, url('user-create')) !== false) {
        $referer = './';
    }
    // 安全过滤，只支持站内跳转，不允许跳到外部，否则可能会被 XSS
    // $referer = str_replace('\'', '', $referer);
    if (!preg_match('#^\\??[\w\-/]+\.htm$#', $referer2) && !preg_match('#^[\w\/]*$#', $referer2)) {
        $referer = './';
    }
    return $referer;
}

function str_push($str, $v, $sep = '_')
{
    if (empty($str)) {
        return $v;
    }

    if (strpos($str, $v . $sep) === false) {
        return $str . $sep . $v;
    }
    return $str;
}

function y2f($rmb)
{
    $rmb = floor($rmb * 10 * 10);
    return $rmb;
}

// $round: float round ceil floor
function f2y($rmb, $round = 'float')
{
    $rmb = floor($rmb * 100) / 10000;
    if ($round == 'float') {
        $rmb = number_format($rmb, 2, '.', '');
    } elseif ($round == 'round') {
        $rmb = round($rmb);
    } elseif ($round == 'ceil') {
        $rmb = ceil($rmb);
    } elseif ($round == 'floor') {
        $rmb = floor($rmb);
    }
    return $rmb;
}
function check_safe_image($file){
    if (!function_exists( 'exif_imagetype' ) ) {
            list($width, $height, $type2, $attr) = getimagesize($file);
           $type = $type2;
        
    }else{
           
          $type = exif_imagetype($file);
   }

return $type;


}
/* 需要兼容，请自行打开开这段注释，默认不兼容

// 兼容 3.0，如果没有使用过，可以砍掉
function array_to_sqladd($arr) {
return db_array_to_update_sqladd($arr);
}

// 兼容 3.0，如果没有使用过，可以砍掉
function cond_to_sqladd($cond) {
return db_cond_to_sqladd($cond);
}

// 兼容 3.0，如果没有使用过，可以砍掉
function orderby_to_sqladd($orderby) {
return db_orderby_to_sqladd($orderby);
}
 */

// 无 Notice 方式的获取超级全局变量中的 key
function _GET($k, $def = null)
{return isset($_GET[$k]) ? $_GET[$k] : $def;}
function _POST($k, $def = null)
{return isset($_POST[$k]) ? $_POST[$k] : $def;}
function _COOKIE($k, $def = null)
{return isset($_COOKIE[$k]) ? $_COOKIE[$k] : $def;}
function _REQUEST($k, $def = null)
{return isset($_REQUEST[$k]) ? $_REQUEST[$k] : $def;}
function _ENV($k, $def = null)
{return isset($_ENV[$k]) ? $_ENV[$k] : $def;}
function _SERVER($k, $def = null)
{return isset($_SERVER[$k]) ? $_SERVER[$k] : $def;}
function GLOBALS($k, $def = null)
{return isset($GLOBALS[$k]) ? $GLOBALS[$k] : $def;}
function G($k, $def = null)
{return isset($GLOBALS[$k]) ? $GLOBALS[$k] : $def;}
function _SESSION($k, $def = null)
{
    global $g_session;
    return isset($_SESSION[$k]) ? $_SESSION[$k] : (isset($g_session[$k]) ? $g_session[$k] : $def);
}
