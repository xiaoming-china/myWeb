<?php

namespace HwmAdmin\Widget;
use Think\Controller;
class MapWidget extends Controller {
	//地区
    public function baiduMap($parameters){
    	//<{:W('Upload/imgOne',array(array('region'=>'省级数据','select'=>array(1,2,3))))}>
    	//select(1省,2市,3区)
    	$this->assign('parameters',$parameters);
    	$this->display('Widget/Map/baiduMap');
    }
}