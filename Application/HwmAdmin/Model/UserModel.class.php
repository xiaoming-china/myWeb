<?php
/*
 *会员模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel{
	
	protected $_link = array(
		'type'	=>	array(
			'mapping_type'	=>	self::BELONGS_TO,
			'foreign_key'	=>	'class',
			'mapping_fields' => 'type_name',
			'condition' =>'t_type=1',
			'as_fields' => 'type_name:typeName'
			),
		);
	/**
	 * [getOnlyUser 获取单条用户数据]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function getOnlyUser($where,$field = '*'){
		return $this->where($where)->field($field)->find();
	}
	/**
	 * [countUser 统计用户]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function countUser($where){
		return $this->where($where)->count();
	}
	/**
	 * [editUser 编辑会员]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function editUser($where,$data){
		return $this->where($where)->save($data) ? true : false;
	}
	/**
	 * [editUser 删除会员]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function delUser($where){
		return $this->where($where)->delete() ? true : false;
	}


}