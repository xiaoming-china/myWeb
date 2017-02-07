<?php
/*
 *留言模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class MessageModel extends RelationModel{
	/**
	 * [getMessageList 获取留言列表数据]
	 * @Author:xiaoming
	 * @DateTime        2017-02-07T15:13:44+0800
	 * @param           [type]                   $where    [description]
	 * @param           [type]                   $page     [description]
	 * @param           [type]                   $pageSize [description]
	 * @param           string                   $field    [description]
	 * @return          [type]                             [description]
	 */
	public function getMessageList($where,$field = '*',$page = 1,$pageSize = 20){
		return $this->where($where)->field($field)->page($page,$pageSize)->select();
	}
	/**
	 * [addMessage 添加留言数据]
	 * @Author:xiaoming
	 * @DateTime        2017-02-07T15:47:34+0800
	 * @param           [type]                   $data [description]
	 */
	public function addMessage($data){
		return $this->add($data);
	}



}