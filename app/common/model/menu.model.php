<?php
$menuhtml;
$menuselect = array(0=>'无上级');

function menu__create($arr) {
	
	$r = db_create('menu', $arr);
	
	return $r;
}

function menu__update($id, $arr) {
	
	$r = db_update('menu', array('keyname'=>$id), $arr);
	
	return $r;
}

function menu__read($id) {
	
	$menu = db_find_one('menu', array('keyname'=>$id));
	
	return $menu;
}

function menu__delete($id) {
	
	$r = db_delete('menu', array('keyname'=>$id));
	
	return $r;
}

function menu__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 1000) {

	$menulist = db_find('menu', $cond, $orderby, $page, $pagesize,'keyname');

	return $menulist;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function menu_create($arr) {

	$r = menu__create($arr);

	return $r;
}

function menu_update($id, $arr) {

	$r = menu__update($id, $arr);
	return $r;
}

function menu_read($id) {


		$menu = menu__read($id);
		menu_format($menu);
		return $menu;


}



function menu_find($cond = array(), $orderby = array('sort'=>-1), $page = 1, $pagesize = 1000) {

	$menulist = menu__find($cond, $orderby, $page, $pagesize);
	if($menulist) foreach ($menulist as &$menu) menu_format($menu);

	return $menulist;
}

// ------------> 其他方法
function menu_format(&$menu) {
    global $conf;
	if(empty($menu)) return;

}

function menu_getlevel($menuarr){

global $menuhtml;



foreach ($menuarr as $key => $value) {
	
	$menuhtml .= '<li>';
    
	$count = menu_count(array('module'=>$value['keyname'],'pid'=>$value['pid']+1,'is_hide'=>0));


	if($count>0){
		$child = menu_find(array('module'=>$value['keyname'],'pid'=>$value['pid']+1,'is_hide'=>0),array('sort'=>-1),'',$count);
		
		$menuhtml .= '<a href="javascript:;" class="dropdown-toggle">';
        
        if($value['pid']==0){
		
		$menuhtml .= '<i class="fa '.$value['icon'].'" data-icon="'.$value['icon'].'"></i>';
        $menuhtml .= '<span class="menu-text normal">'.$value['name'].'</span>';
		
		$menuhtml .= '<b class="arrow fa fa-angle-right normal"></b>';
        }else{
        	
        	$menuhtml .= '<i class="fa fa-caret-right"></i>';
        	$menuhtml .= '<span class="menu-text ">'.$value['name'].'</span>';
		
		$menuhtml .= '<b class="arrow fa fa-angle-right "></b>';
        }


		
       
		
		$menuhtml .= '</a>';
		$menuhtml .= '<ul class="submenu">';
		
		menu_getlevel($child);
		
		$menuhtml .="</ul>";

	}else{
		
		$menuhtml .= '<a href="javascript:;" data-title="'.$value['name'].'" data-href="'.r_url($value['controller'].'-'.$value['action']).'">';
		if($value['pid']==0){
            $menuhtml .= '<i class="fa '.$value['icon'].'" data-icon="'.$value['icon'].'"></i>';
            $menuhtml .= '<span class="menu-text normal">'.$value['name'].'</span></a>';
		}else{

            $menuhtml .= '<i class="fa fa-angle-double-right"></i>';
            $menuhtml .= '<span class="menu-text">'.$value['name'].'</span></a>';
		}
		
		
		continue;
	}
	$menuhtml .= '</li>';
}


return $menuhtml;

	
}
function menu_count($cond = array()) {

	$n = db_count('menu', $cond);

	return $n;
}

function menu_maxid() {

	$n = db_maxid('menu', 'keyname');

	return $n;
}






?>