<?php
/*
 *相册列表模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class PhoneTypeModel extends RelationModel{
	protected $_link = array(
	'phone'	=>	array(
		'mapping_type'	=>	self::HAS_MANY,
		'foreign_key'	=>	'p_id',
		'condition' =>'is_show=1',
		),
	);
	/**
	 * [getArticleList 获取相册列表]
	 * @param  [type] $where    [description]
	 * @param  [type] $page     [description]
	 * @param  [type] $pageSize [description]
	 * @param  string $field    [description]
	 * @param  string $order    [description]
	 * @return [type]           [description]
	 */
	public function getPhoneList($where,$page,$pageSize ,$field = '*',$order = 'add_time DESC'){
	   $rs = $this->where($where)->field($field)->page($page,$pageSize)->select();
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
	/**
	 * [photoList 相册列表]
	 * @param  [type] $pId [description]
	 * @return [type]      [description]
	 */
	public function photoList($pId){
		$rs             = $this->Relation(true)->find($pId);
	   	//组装数据
	   	$data['title']  = $rs['p_name'];
	   	$data['id']	    = $rs['p_id'];
	   	$phone = $rs['phone'];
	   	$t     = [];
	   	foreach ($phone as $key => $value) {
	   		$d['alt']   = $value['name'];
	   		$d['pid']   = $value['p_id'];
	   		$d['src']   = $value['path'];
	   		$d['thumb'] = $value['path'];
	   		$t[]        = $d;
	   	}
	   	$data['data']   = $t;
	   	return $data;
	}


}