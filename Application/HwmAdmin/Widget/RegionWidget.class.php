<?php

namespace HwmAdmin\Widget;
use Think\Controller;
class RegionWidget extends Controller {
	//地区
	public function region($parameters){
		//<{:W('Upload/imgOne',array(array('region'=>'省级数据','select'=>array(1,2,3))))}>
		//select(1省,2市,3区)
		$this->assign('parameters',$parameters);
		$this->display('Widget/Region/region');
	}

	//查询条件
	public function region_seach($parameters){
		$this->assign('parameters',$parameters);
		$this->display('Widget/Region/region_seach');
	}

	//开通城市
	public function city($parameters){
		$this->assign('parameters',$parameters);
		$this->display('Widget/Region/city');
	}
}