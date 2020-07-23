<?php

require EXTEND_PATH . 'phpqrcode/phpqrcode.php';
function downloaderweima($url,$uid){

	

	$model = new \QRcode();



	$value = $url; //二维码内容
	$errorCorrectionLevel = 'L';//容错级别
	$matrixPointSize = 6;//生成图片大小
	//生成二维码图片


	$model::png($value, './upload/tmp/'.$uid.'.png', $errorCorrectionLevel, $matrixPointSize, 2);
	$logo = './public/common/images/5ilogo.png';//准备好的logo图片
	$QR ='./upload/tmp/'.$uid.'.png';//已经生成的原始二维码图
    if ($logo !== FALSE) {
		$QR = imagecreatefromstring(file_get_contents($QR));
		$logo = imagecreatefromstring(file_get_contents($logo));
		$QR_width = imagesx($QR);//二维码图片宽度
		$QR_height = imagesy($QR);//二维码图片高度
		$logo_width = imagesx($logo);//logo图片宽度
		$logo_height = imagesy($logo);//logo图片高度
		$logo_qr_width = $QR_width / 5;
		$scale = $logo_width/$logo_qr_width;
		$logo_qr_height = $logo_height/$scale;
		$from_width = ($QR_width - $logo_qr_width) / 2;
		//重新组合图片并调整大小
		imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
		$logo_qr_height, $logo_width, $logo_height);
	}
	imagepng($QR, './upload/tmp/'.$uid.'.png');
	$QRimg ='/upload/tmp/'.$uid.'.png';//已经生成的原始二维码图
    return $QRimg;



}
// 不包含 .
function image_ext($filename) {
	return strtolower(substr(strrchr($filename, '.'), 1));
}

// 获取安全的文件名，如果文件存在，则加时间戳和随机数，避免重复
function image_safe_name($filename, $dir) {
	$time = $_SERVER['time'];
	// 最后一个 . 保留，其他的 . 替换
	$s1 = substr($filename, 0, strrpos($filename, '.'));
	$s2 = substr(strrchr($filename, '.'), 1);
	$s1 = preg_replace('#\W#', '_', $s1);
	$s2 = preg_replace('#\W#', '_', $s2);
	if(is_file($dir."$s1.$s2")) {
		$newname = $s1.$time.rand(1, 1000).'.'.$s2;
	} else {
		$newname = "$s1.$s2";
	}
	return $newname;
}

// 缩略图的名字
function image_thumb_name($filename) {
	return substr($filename, 0, strrpos($filename, '.')).'_thumb'.strrchr($filename, '.');
}

// 随即文件名
function image_rand_name($k) {
	$time = $_SERVER['time'];
	return $time.'_'.rand(1000000000, 9999999999).'_'.$k;
}

/*
	实例：
	image_set_dir(123, './upload');

	000/000/1.jpg
	000/000/100.jpg
	000/000/100.jpg
	000/000/999.jpg
	000/001/1000.jpg
	000/001/001.jpg
	000/002/001.jpg
*/
function image_set_dir($id, $dir) {

	$id = sprintf("%09d", $id);
	$s1 = substr($id, 0, 3);
	$s2 = substr($id, 3, 3);
	$dir = $dir."$s1/$s2";
	!is_dir($dir) && mkdir($dir, 0777, TRUE);

	return "$s1/$s2";
}

// 取得 user home 路径
function image_get_dir($id) {
	$id = sprintf("%09d", $id);
	$s1 = substr($id, 0, 3);
	$s2 = substr($id, 3, 3);
	return "$s1/$s2";
}

/*
	实例：
 	image_thumb('xxx.jpg', 'xxx_thumb.jpg', 200, 200);

 	返回：
 	array('filesize'=>0, 'width'=>0, 'height'=>0)
 */
function image_thumb($sourcefile, $destfile, $forcedwidth = 80, $forcedheight = 80) {
	$return = array('filesize'=>0, 'width'=>0, 'height'=>0);
	$destext = image_ext($destfile);
	if(!in_array($destext, array('gif', 'jpg', 'bmp', 'png'))) {
		return $return;
	}

	$imginfo = getimagesize($sourcefile);
	$src_width = $imginfo[0];
	$src_height = $imginfo[1];
	if($src_width == 0 || $src_height == 0) {
		return $return;
	}

	if(!function_exists('imagecreatefromjpeg')) {
		copy($sourcefile, $destfile);
		$return = array('filesize'=>filesize($destfile), 'width'=>$src_width, 'height'=>$src_height);
		return $return;
	}

	// 按规定比例缩略
	$src_scale = $src_width / $src_height;
	$des_scale = $forcedwidth / $forcedheight;
	if($src_width <= $forcedwidth && $src_height <= $forcedheight) {
		$des_width = $src_width;
		$des_height = $src_height;
	} elseif($src_scale >= $des_scale) {
		$des_width = ($src_width >= $forcedwidth) ? $forcedwidth : $src_width;
		$des_height = $des_width / $src_scale;
		$des_height = ($des_height >= $forcedheight) ? $forcedheight : $des_height;
	} else {
		$des_height = ($src_height >= $forcedheight) ? $forcedheight : $src_height;
		$des_width = $des_height * $src_scale;
		$des_width = ($des_width >= $forcedwidth) ? $forcedwidth : $des_width;
	}

	switch ($imginfo['mime']) {
		case 'image/jpeg':
			$img_src = imagecreatefromjpeg($sourcefile);
			!$img_src && $img_src = imagecreatefromgif($sourcefile);
			break;
		case 'image/gif':
			$img_src = imagecreatefromgif($sourcefile);
			!$img_src && $img_src = imagecreatefromjpeg($sourcefile);
			break;
		case 'image/png':
			$img_src = imagecreatefrompng($sourcefile);
			break;
		case 'image/wbmp':
			$img_src = imagecreatefromwbmp($sourcefile);
			break;
		default :
			return $return;
	}

	if(!$img_src) return $return;

	$img_dst = imagecreatetruecolor($des_width, $des_height);
	imagefill($img_dst, 0, 0 , 0xFFFFFF);
	imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $des_width, $des_height, $src_width, $src_height);

	$conf = _SERVER('conf');
	$tmppath = isset($conf['tmp_path']) ? $conf['tmp_path'] : ini_get('upload_tmp_dir').'/';
	$tmppath == '/' AND $tmppath = './tmp/';

	$tmpfile = $tmppath.md5($destfile).'.tmp';
	switch($destext) {
		case 'jpg': imagejpeg($img_dst, $tmpfile, 90); break;
		case 'gif': imagegif($img_dst, $tmpfile); break;
		case 'png': imagepng($img_dst, $tmpfile); break;
	}
	$r = array('filesize'=>filesize($tmpfile), 'width'=>$des_width, 'height'=>$des_height);;
	copy($tmpfile, $destfile);
	is_file($tmpfile) && unlink($tmpfile);
	imagedestroy($img_dst);
	return $r;
}


/**
 * 图片裁切
 *
 * @param string $sourcefile	原图片路径(绝对路径/abc.jpg)
 * @param string $destfile 		裁切后生成新名称(绝对路径/rename.jpg)
 * @param int $clipx 			被裁切图片的X坐标
 * @param int $clipy 			被裁切图片的Y坐标
 * @param int $clipwidth 		被裁区域的宽度
 * @param int $clipheight 		被裁区域的高度
 * image_clip('xxx/x.jpg', 'xxx/newx.jpg', 10, 40, 150, 150)
 */
function image_clip($sourcefile, $destfile, $clipx, $clipy, $clipwidth, $clipheight) {
	$getimgsize = getimagesize($sourcefile);
	if(empty($getimgsize)) {
		return 0;
	} else {
		$imgwidth = $getimgsize[0];
		$imgheight = $getimgsize[1];
		if($imgwidth == 0 || $imgheight == 0) {
			return 0;
		}
	}

	if(!function_exists('imagecreatefromjpeg')) {
		copy($sourcefile, $destfile);
		return filesize($destfile);
	}
	switch($getimgsize[2]) {
		case 1 :
			$imgcolor = imagecreatefromgif($sourcefile);
			break;
		case 2 :
			$imgcolor = imagecreatefromjpeg($sourcefile);
			break;
		case 3 :
			$imgcolor = imagecreatefrompng($sourcefile);
			break;
	}

	if(!$imgcolor) return 0;

	$img_dst = imagecreatetruecolor($clipwidth, $clipheight);
	imagefill($img_dst, 0, 0 , 0xFFFFFF);
	imagecopyresampled($img_dst, $imgcolor, 0, 0, $clipx, $clipy, $imgwidth, $imgheight, $imgwidth, $imgheight);

	$conf = _SERVER('conf');
	$tmppath = isset($conf['tmp_path']) ? $conf['tmp_path'] : ini_get('upload_tmp_dir').'/';
	$tmppath == '/' AND $tmppath = './tmp/';

	$tmpfile = $tmppath.md5($destfile).'.tmp';
	imagejpeg($img_dst, $tmpfile, 100);
	$n = filesize($tmpfile);
	copy($tmpfile, $destfile);
	is_file($tmpfile) && @unlink($tmpfile);
	return $n;
}

// 先裁切后缩略，因为确定了，width, height, 不需要返回宽高。
function image_clip_thumb($sourcefile, $destfile, $forcedwidth = 80, $forcedheight = 80) {
	// 获取原图片宽高
	$getimgsize = getimagesize($sourcefile);
	if(empty($getimgsize)) {
		return 0;
	} else {
		$src_width = $getimgsize[0];
		$src_height = $getimgsize[1];
		if($src_width == 0 || $src_height == 0) {
			return 0;
		}
	}

	$src_scale = $src_width / $src_height;
	$des_scale = $forcedwidth / $forcedheight;

	if($src_width <= $forcedwidth && $src_height <= $forcedheight) {
		$des_width = $src_width;
		$des_height = $src_height;
		$n = image_clip($sourcefile, $destfile, 0, 0, $des_width, $des_height);
		return filesize($destfile);
	// 原图为横着的矩形
	} elseif($src_scale >= $des_scale) {
		// 以原图的高度作为标准，进行缩略
		$des_height = $src_height;
		$des_width = $src_height / $des_scale;
		$n = image_clip($sourcefile, $destfile, 0, 0, $des_width, $des_height);
		if($n <= 0) return 0;
		$r = image_thumb($destfile, $destfile, $forcedwidth, $forcedheight);
		return $r['filesize'];
	// 原图为竖着的矩形
	} else {
		// 以原图的宽度作为标准，进行缩略
		$des_width = $src_width;
		$des_height = $src_width / $des_scale;

		// echo "src_scale: $src_scale, src_width: $src_width, src_height: $src_height \n";
		// echo "des_scale: $des_scale, forcedwidth: $forcedwidth, forcedheight: $forcedheight \n";
		// echo "des_width: $des_width, des_height: $des_height \n";
		// exit;

		$n = image_clip($sourcefile, $destfile, 0, 0, $des_width, $des_height);
		if($n <= 0) return 0;
		$r = image_thumb($destfile, $destfile, $forcedwidth, $forcedheight);
		return $r['filesize'];
	}
}

function image_safe_thumb($sourcefile, $id, $ext, $dir1, $forcedwidth, $forcedheight, $randomname = 0) {
	$time = $_SERVER['time'];
	$ip = $_SERVER['ip'];
	$dir2 = image_set_dir($id, $dir1);
	$filename = $randomname ? md5(rand(0, 1000000000).$time.$ip).$ext : $id.$ext;
	$filepath = "$dir1$dir2/$filename";
	$arr = image_thumb($sourcefile, $filepath, $forcedwidth, $forcedheight);
	$arr['fileurl'] = "$dir2/$filename";
	return $arr;
}
function setWater($imgSrc,$markImg,$markText,$TextColor,$markPos,$fontType,$markType)

{
  
 

  $srcInfo = @getimagesize($imgSrc);

  $srcImg_w  = $srcInfo[0];

  $srcImg_h  = $srcInfo[1];

  if($srcImg_w<200&&$srcImg_h<200){
  	return;
  }

  switch ($srcInfo[2]) 

  { 

    case 1: 

      $srcim =imagecreatefromgif($imgSrc); 

      break; 

    case 2: 

      $srcim =imagecreatefromjpeg($imgSrc); 

      break; 

    case 3: 

      $srcim =imagecreatefrompng($imgSrc); 

      break; 

    default: 

      die("不支持的图片文件类型"); 

      exit; 

  }

     

  if($markType==2)

  {

    if(!file_exists($markImg) || empty($markImg))

    {

      return;

    }

       

    $markImgInfo = @getimagesize($markImg);

    $markImg_w  = $markImgInfo[0];

    $markImg_h  = $markImgInfo[1];

       

    if($srcImg_w < $markImg_w || $srcImg_h < $markImg_h)

    {

      return;

    }

       

    switch ($markImgInfo[2]) 

    { 

      case 1: 

        $markim =imagecreatefromgif($markImg); 

        break; 

      case 2: 

        $markim =imagecreatefromjpeg($markImg); 

        break; 

      case 3: 

        $markim =imagecreatefrompng($markImg); 

        break; 

      default: 

        die("不支持的水印图片文件类型"); 

        exit; 

    }

       

    $logow = $markImg_w;

    $logoh = $markImg_h;

  }

     

  if($markType==1)

  {

    $fontSize = 16;

    if(!empty($markText))

    {

      if(!file_exists($fontType))

      {

        return;

      }

    }

    else {

      return;

    }

       

    $box = @imagettfbbox($fontSize, 0, $fontType,$markText);

    $logow = max($box[2], $box[4]) - min($box[0], $box[6]);

    $logoh = max($box[1], $box[3]) - min($box[5], $box[7]);

  }

     

  if($markPos == 0)

  {

    $markPos = rand(1, 9);

  }

     

  switch($markPos)

  {

    case 1:

      $x = +5;

      $y = +5;

      break;

    case 2:

      $x = ($srcImg_w - $logow) / 2;

      $y = +5;

      break;

    case 3:

      $x = $srcImg_w - $logow - 5;

      $y = +15;

      break;

    case 4:

      $x = +5;

      $y = ($srcImg_h - $logoh) / 2;

      break;

    case 5:

      $x = ($srcImg_w - $logow) / 2;

      $y = ($srcImg_h - $logoh) / 2;

      break;

    case 6:

      $x = $srcImg_w - $logow - 5;

      $y = ($srcImg_h - $logoh) / 2;

      break;

    case 7:

      $x = +5;

      $y = $srcImg_h - $logoh - 5;

      break;

    case 8:

      $x = ($srcImg_w - $logow) / 2;

      $y = $srcImg_h - $logoh - 5;

      break;

    case 9:

      $x = $srcImg_w - $logow - 5;

      $y = $srcImg_h - $logoh -5;

      break;

    default: 

      die("此位置不支持"); 

      exit;

  }

     

  $dst_img = @imagecreatetruecolor($srcImg_w, $srcImg_h);

     

  imagecopy ( $dst_img, $srcim, 0, 0, 0, 0, $srcImg_w, $srcImg_h);

     

  if($markType==2)

  {

    imagecopy($dst_img, $markim, $x, $y, 0, 0, $logow, $logoh);

    imagedestroy($markim);

  }

     

  if($markType==1)

  {

    $rgb = explode(',', $TextColor);

       

    $color = imagecolorallocate($dst_img, $rgb[0], $rgb[1], $rgb[2]);

    imagettftext($dst_img, $fontSize, 0, $x, $y, $color, $fontType,$markText);

  }

     

  switch ($srcInfo[2]) 

  { 

    case 1:

      imagegif($dst_img, $imgSrc); 

      break; 

    case 2: 

      imagejpeg($dst_img, $imgSrc); 

      break; 

    case 3: 

      imagepng($dst_img, $imgSrc); 

      break;

    default: 

      die("不支持的水印图片文件类型"); 

      exit; 

  }

     

  imagedestroy($dst_img);

  imagedestroy($srcim);

}

?>