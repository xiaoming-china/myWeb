<?php

/**
 * 站点配置信息
 */
namespace Common\Model;
use Think\Model;

class ConfigModel extends Model{
	protected $tableName = 'config';

	public function getConfig(){
		$data = F('config');
		if(!$data){
			$result = $this->select();
			$data = array();
			foreach ($result as $value) {
				$data[$value['name']] = $value['value'];
			}
			$data['holiday_price_date'] = explode(',',$data['holiday_price_date']);
			F('config',$data);
		}
		return $data;
	}
}