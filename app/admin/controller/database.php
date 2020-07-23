<?php

!defined('DEBUG') AND exit('Access Denied.');


include MODEL_PATH.'databack.php';


if(empty($action)||$action=="list") {
	 $list = db_sql_find('SHOW TABLE STATUS');


     $table_list = array_map('array_change_key_case', $list);
     $tablepre =$conf['db']['pdo_mysql']['master']['tablepre'];
    $table_arr = array($tablepre.'session',$tablepre.'session_data',$tablepre.'kv',$tablepre.'cache',$tablepre.'queue');


	 include ADMIN_PATH . "view/database_export.html"; 
}else if($action=='optimize'){
	$tables = param('tables');
	 if (!empty($tables)) {
       	    if(is_array($tables)){
                $tables = implode('`,`', $tables);
	 	    }else{
	 	        $tables = $tables;	
	 	    }
       	    
           
            $list = db_sql_find("OPTIMIZE TABLE `{$tables}`");
            if ($list) {
              
              message(0, '数据表优化完成');
               
            } else {
               
               message(-1, '数据表优化出错请重试');
              
            }
        } else {
            
            message(-1, '请指定要优化的表');
            
        }
}else if($action=='repair'){
	$tables = param('tables');
	
	 if (!empty($tables)) {


	 	    if(is_array($tables)){
                $tables = implode('`,`', $tables);
	 	    }else{
	 	        $tables = $tables;	
	 	    }
       	    
            $list = db_sql_find("REPAIR TABLE `{$tables}`");
            if ($list) {
              
              message(0, '数据表修复完成');
            } else {
               
               message(-1, '数据表修复出错请重试');
               
            }
        } else {
           message(-1, '请指定要修复的表');
           
        }
}else if($action=='export'){
	    $tables = param('tables');

	    $tab_id = param('id');
	    $start = param('start');
        $now = param('now');
	 	if (!empty($tables)) {
            
            if (!is_dir($conf['batabase_export_config']['path'])) {
            	mkdir($conf['batabase_export_config']['path'], 0755, true);
            }
            $config = array('path' => realpath($conf['batabase_export_config']['path']).DS,
            		'part' => $conf['batabase_export_config']['DATA_BACKUP_PART_SIZE'],
            		'compress' => $conf['batabase_export_config']['DATA_BACKUP_COMPRESS'],
            		'level' => $conf['batabase_export_config']['DATA_BACKUP_COMPRESS_LEVEL']);
         
            $lock = "{$config['path']}backup.lock";
            if (is_file($lock)) {
            	message(-1, '检测到有一个备份任务正在执行，请稍后再试！');
              
              
            } else {
                file_put_contents($lock, time());
            }
            if(!is_writeable($config['path'])){
            	message(-1, '备份目录不存在或不可写，请检查后重试！');
            	
            	
            }
            $_SESSION['backup_config'] = $config;
            
            $file = ['name' => date('Ymd-His', time()), 'part' => 1];
            
            $_SESSION['backup_file'] = $file;
            
            $_SESSION['backup_tables'] = $tables;

            $Databack = new Databack($file, $config);
            if (false !== $Databack->create()) {
            	$tab = array('id' => 0, 'start' => 0,'now'=>1);
            	message(0, '初始化成功',array('tables' => $tables, 'tab' => $tab));
            	 
              
            } else {
            	message(-1, '初始化失败，备份文件创建失败！');
            	
            
            }
        } elseif (isset($start)&&isset($tab_id)&&$start!="") {

            $tables = _SESSION('backup_tables');

            $id = $tab_id;
            
            $Databack = new Databack(_SESSION('backup_file'), _SESSION('backup_config'));
            $r = $Databack->backup($tables[$id], $start);
            if (false === $r) {
            	message(-1, '备份出错！');
               
            
            } elseif (0 === $r) {
                if (isset($tables[++$id])) {
                	$tab = array('id' => $id, 'start' => 0);
                	
                	message(0, '备份完成！',array('tab' => $tab));
                	
                	
                  
                } else {
                	$config = _SESSION('backup_config');
                    @unlink($config['path'] . 'backup.lock');
                    _SESSION('backup_tables', null);
                    _SESSION('backup_file', null);
                    _SESSION('backup_config', null);
                   message(0, '备份完成！');
                   
                }
            } else {
                $rate = floor(100 * ($r[0] / $r[1]));
                $tab  = array('id' => $id, 'start' => $r[0]);
                message(0, "正在备份...({$rate}%)",array('tab' => $tab));
                
             
                
            }
        } else {
        	
        	message(-1, '请指定要备份的表！');
        }
}else if($action=='deletebak'){
	        $time = param('time');
	    	if ($time) {
    		$name = date('Ymd-His', $time) . '-*.sql*';
    		$path = realpath($conf['batabase_export_config']['path']) . DIRECTORY_SEPARATOR . $name;
    		array_map("unlink", glob($path));
    		if (count(glob($path))) {
    			message(-1, '备份文件删除失败，请检查权限！');
    			
    		} else {
    			message(0, '备份文件删除成功');
    			
    		}
    	} else {
    		message(-1, '参数错误！');
    		
    	}
}else if($action=='import'){
	
   $time = param('time');
   $part = param('part');
   $start = param('start');

		if (is_numeric($time) && empty($part) && empty($start)) { 
			//初始化
			 
			//获取备份文件信息
			$name  = date('Ymd-His', $time) . '-*.sql*';
			$path  = realpath($conf['batabase_export_config']['path']) . DIRECTORY_SEPARATOR . $name;
			$files = glob($path);
			
			$list  = array();
			foreach ($files as $name) {
				$basename        = basename($name);
				$match           = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
				$gz              = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
				$list[$match[6]] = array($match[6], $name, $gz);
			}
			ksort($list);
			//检测文件正确性
			$last = end($list);
		
			if (count($list) === $last[0]) {
			
                $_SESSION['backup_list']=$list;
         


                $data['data'] = array('part' => 1, 'start' => 0);
                message(0, '初始化完成！',$data);
				
			} else {
                message(-1, '备份文件可能已经损坏，请检查！');
				
			}
		} elseif (is_numeric($part) && is_numeric($start)) {
            
			$list = _SESSION('backup_list');



			$db = new Databack($list[$part], array('path' => realpath($conf['batabase_export_config']['path']) . DIRECTORY_SEPARATOR, 'compress' => $list[$part][2]));
			
			$r = $db->import($start);
			
			if (false === $r) {
                message(-1, '还原数据出错！');
				
			} elseif (0 === $r) {
				//下一卷
				if($start>0){
				
				}
				if (isset($list[++$part])) {
					
					$data['data'] = array('part' => $part, 'start' => 0);
                    message(0, "正在还原...#{$part}", $data);
					
				} else {
					//unset($_SESSION['backup_list']);
					message(0, '还原完成！');
					
				}
			} else {

				$data['data'] = array('part' => $part, 'start' => $r[0]);
				if ($r[1]) {
					$rate = floor(100 * ($r[0] / $r[1]));
                    message(0, "正在还原...#{$part} ({$rate}%)", $data);
					
				} else {
                    
					$data['gz'] = 1;
                    message(0, "正在还原...#{$part}",$data);
					
				}
			}
		} else {
            message(-1, '参数错误！');
			
		}
}else{


	            $path = realpath($conf['batabase_export_config']['path']);
                $flag = \FilesystemIterator::KEY_AS_FILENAME;
                $glob = new \FilesystemIterator($path,  $flag);
                $list = array();
                foreach ($glob as $name => $file) {
                    if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                        $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                        $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                        $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                        $part = $name[6];

                        if(isset($list["{$date} {$time}"])){
                            $info = $list["{$date} {$time}"];
                            $info['part'] = max($info['part'], $part);
                            $info['size'] = $info['size'] + $file->getSize();
                        } else {
                            $info['part'] = $part;
                            $info['size'] = $file->getSize();
                        }
                        $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                        $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                        $info['time']     = strtotime("{$date} {$time}");

                        $list["{$date} {$time}"] = $info;
                    }
                }
                krsort($list);
        include ADMIN_PATH . "view/database_import.html"; 
}


?>
