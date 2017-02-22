<?php
/*
 *会员类型模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class TypeModel extends RelationModel{
	/**
	 * [userType 类型列表]
	 * @param  [type] $where [description]
	 * @param  string $field [description]
	 * @return [type]        [description]
	 */
	public function Type($where,$field='*',$order ='type_sort DESC'){
		return $this->where($where)->field($field)->order($order)->select();
	}
	/**
	 * [getOnlyType 获取单条类型]
	 * @param  [type] $where [description]
	 * @param  string $field [description]
	 * @return [type]        [description]
	 */
	public function getOnlyType($where,$field = '*'){
		return $this->where($where)->field($field)->find();
	}
	/**
	 * [FunctionName 添加会员类型]
	 * @param string $value [description]
	 */
	public function addType($data){
		return $this->add($data);
	}
	/**
	 * [editType 编辑类型]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function editType($where,$data){
		return $this->where($where)->save($data);
	}
	/**
	 * [editType 删除类型]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function delType($where){
		 return $this->where($where)->delete() ? true : false;
	}


}