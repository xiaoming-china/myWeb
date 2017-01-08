<?php
/*
 *name:会管理员模型
 *time:2016-12-1
 *by:xiaoming
 */
namespace HwmAdmin\Model;
use Think\Model;

class AdminModel extends Model{
	/**
	 * [getOnlyUser 获取单条用户数据]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function getOnlyUser($where,$field = '*'){
		return $this->where($where)->field($field)->find();
	}
}