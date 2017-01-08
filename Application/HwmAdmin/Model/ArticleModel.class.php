<?php
/*
 *会员模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class ArticleModel extends RelationModel{

	// public $_link = array(
	// 	//文章类型
	// 	'type'	=>	array(
	// 		'mapping_type'	=>	self::MANY_TO_MANY,
	// 		'foreign_key'	=>	'a_id',
	// 		'relation_foreign_key'  =>  't_id',
	// 		// 'mapping_fields' => 'type_name',
	// 		'relation_table'    =>  'w_article_relation'
	// 		// 'as_fields' => 'type_name:typeName'
	// 		),
	// 	);
	public function addArticle($data){
		return $this->add($data);
	}
	/**
	 * [getArticleList 查询文章列表]
	 * @return [type] [description]
	 */
	public function getArticleList($where,$page,$pageSize ,$field = '*',$order = 'add_time DESC'){
	   $rs = $this->where($where)->field($field)->page($page,$pageSize)->select();
	   return $rs;
	}
	/**
	 * [getArticleList 统计文章]
	 * @return [type] [description]
	 */
	public function countArticleList($where){
	   return $this->where($where)->count();
	}
	/**
	 * [getOnlyUser 获取单条文章数据]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function getOnlyArticle($where,$field = '*'){
		return $this->where($where)->field($field)->find();
	}
	/**
	 * [countUser 统计文章]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function countArticle($where){
		return $this->where($where)->count();
	}
	/**
	 * [editUser 编辑文章]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function editArticle($where,$data){
		return $this->where($where)->save($data) ? true : false;
	}
	/**
	 * [editUser 删除文章]
	 * @param  [type] $where [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function delArticle($where){
		return $this->where($where)->delete() ? true : false;
	}

}