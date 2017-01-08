<?php

namespace HwmAdmin\Controller;

class UploadController extends AdminBasicController{
	//单图上传
	public function imgOne(){
		//配置信息
		$config = F('config');
		$imageSize = $config['image_size']?$config['image_size']:1024;
		$thumbSize = explode(',',$config['thumb_image_size']);
		//$thumbW = $thumbSize[0]?$thumbSize[0]:150;
		//$thumbH = $thumbSize[1]?$thumbSize[1]:90;

		$upload = new \Think\Upload();
		$upload->rootPath = 'Uploads/';
		$upload->maxSize = $imageSize*1024;

		$upload->exts = array('jpg','jpeg','png','gif');
		if(!is_dir($upload->rootPath)){
			!mkdir($upload->rootPath) && return_msg("{$upload->rootPath}目录创建失败!");
		}
		if($info = $upload->uploadOne($_FILES['imgFile'])){
			$img = new \Think\Image();
			$path = $upload->rootPath.$info['savepath'];
			//缩略图
			$img->open($path.$info['savename']);
			$img->thumb($img->width()/2, $img->height()/2);

			$rs['url'] = '/'.$path.'thumb_'.$info['savename'];
			$rs['error'] = 0;
			!$img->save($path.'thumb_'.$info['savename']) && return_msg('缩略图生成失败!');

			if($config['is_watermark']==1){

				//水印图
				if(is_file(substr($config['watermark_image'],1))){
					$img->open($path.$info['savename']);
					$img->water(substr($config['watermark_image'],1),$config['watermark_position']);
					!$img->save($path.$info['savename']) && return_msg('添加水印失败!');
				}
			}
			return_msg($rs);
			
		}else{
			return_msg($upload->getError());
		}
	}

	//服务器图片管理
	public function fileManagerJson(){
		$current_path = 'Uploads/'.$_GET['path'];
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'mp3');
		$file_list = array();
		if ($handle = opendir($current_path)) {
			$i = 0;
			while (false !== ($filename = readdir($handle))) {
				if ($filename{0} == '.')
					continue;
				$file = $current_path . "/" . $filename;
				if (is_dir($file)) {
					$file_list[$i]['is_dir'] = true;
					//是否文件夹
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2);
					//文件夹是否包含文件
					$file_list[$i]['filesize'] = 0;
					//文件大小
					$file_list[$i]['is_photo'] = false;
					//是否图片
					$file_list[$i]['filetype'] = '';
					//文件类别，用扩展名判断
				} else {
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename;
				//文件名，包含扩展名
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));
				//文件最后修改时间
				$i++;
			}
			closedir($handle);
		}
		$result = array();
		//相对于根目录的上一级目录
		$result['moveup_dir_path'] = "";
		//相对于根目录的当前目录
		$result['current_dir_path'] = $_GET['path'];
		//当前目录的URL
		$result['current_url'] = '/' . $current_path;
		//文件数
		$result['total_count'] = count($file_list);
		//文件列表数组
		$result['file_list'] = $file_list;
		echo json_encode($result);
		exit;
	}

	//删除图片
	public function delImg(){
		$imgUrl = I('post.imgUrl');
		if(unlink('.'.$imgUrl) && unlink('.'.str_replace('thumb_','',$imgUrl))){
			return_msg('success');
		}
	}
}