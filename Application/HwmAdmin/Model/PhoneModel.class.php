<?php
/*
 *相册模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class PhoneModel extends RelationModel{
	/**
	 * [getArticleList 获取相册列表]
	 * @param  [type] $where    [description]
	 * @param  [type] $page     [description]
	 * @param  [type] $pageSize [description]
	 * @param  string $field    [description]
	 * @param  string $order    [description]
	 * @return [type]           [description]
	 */
	public function getPhoneList($where,$order = 'add_time DESC'){
	   $rs = $this->where($where)->order($order)->select();
	   return $rs;
	}
	/**
	 * [getOnlyUser 获取单条相册数据]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function getOnlyPhone($where,$field = '*'){
		return $this->where($where)->field($field)->find();
	}
	/**
	 * [countUser 统计相册]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function countPhone($where){
		return $this->where($where)->count();
	}
	/**
	 * [editUser 编辑相册]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function editPhone($where,$data){
		return $this->where($where)->save($data) ? true : false;
	}
	/**
	 * [editUser 删除相册]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function delPhone($where){
		return $this->where($where)->delete() ? true : false;
	}


}