<?php
/**
 * name:文章和文章类型中间表
 * daat:16-11-24
 * by:xiaoming
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class ArticleRelationModel extends RelationModel{
	/**
	 * [addRelation 添加文章和类型关联数据]
	 * @param [type] $data [description]
	 */
	public function addRelation($data){
		return $this->addAll($data);
	}
	/**
	 * [getArticleId 获取文章id]
	 * @param  [type] $ids [description]
	 * @return [type]      [description]
	 */
	public function getArticleId($where){
		return $rs = $this->distinct(true)->where($where)->field('a_id')->select();
	}
	/**
	 * [getTypeId 获取文章的分类id]
	 * @param  [type] $aIds [description]
	 * @return [type]       [description]
	 */
	public function getTypeId($aIds){
		$rs = $this->alias("r")
				   ->join("w_type AS t ON r.t_id = t.t_id ")
				   ->where(array('r.a_id'=>$aIds))
				   ->select();
		return $rs;
	}
	/**
	 * [editType 删除文章和分类]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function delRelation($where){
		 return $this->where($where)->delete() ? true : false;
	}




}