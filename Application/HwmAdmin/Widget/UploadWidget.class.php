<?php

namespace HwmAdmin\Widget;
use Think\Controller;
class UploadWidget extends Controller {
	//单图上传
    public function imgOne($parameters){
    	//<{:W('Upload/imgOne',array(array('name'=>'thumb','value'=>'')))}>
    	$parameters['id'] = $parameters['id'] ? $parameters['id'] : 1;
    	$parameters['value'] = $parameters['value'] ? $parameters['value'] : 'http://www.baidu.com/img/bdlogo.png';
    	$this->assign('parameters',$parameters);
    	$this->display('Widget/Upload/imgOne');
    }

    //多图上传
    public function imgMany($parameters=array()){
        $this->assign('parameters',$parameters);
    	$this->display('Widget/Upload/imgMany');
    }

    //编辑器
    public function editor($parameters=array()){
        $parameters['id'] = $parameters['id'] ? $parameters['id'] : 1;
        $this->assign('parameters',$parameters);
        $this->display('Widget/Upload/editor');
    }
}