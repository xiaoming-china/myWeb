<?php
/*
 *文章模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class ArticleModel extends RelationModel{

	/**
	 * [addArticle 添加文章]
	 * @Author:xiaoming
	 * @DateTime        2017-01-21T15:43:47+0800
	 * @param           [type]                   $data [description]
	 */
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

		$article['info'] = $this->where($where)->field($field)->find();
		$article['comment'] = D('HwmAdmin/Comment')->GetArticleComment($where['a_id']);
		return $article;
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
	/**
	 * [getArticleList 获取满意分类下所有的文章id，如果是父级，则包括了所有子级的id]
	 * @Author:xiaoming
	 * @DateTime        2017-01-21T15:44:26+0800
	 * @return          [type]                   [description]
	 */
	public function getArticleListId($tId){
		  //分类显示条件
	      $tree = new \Org\Util\Indefinitely();
	      $tMap['isadmin']     = ['eq',1];
	      $tMap['t_type']      = ['eq',2];
	      $tMap['type_status'] = ['eq',1];
	      //所有分类
	      $typeList = D('HwmAdmin/Type')->Type($tMap,'t_id,type_name,parent');
		  //判断当前分类是否有子类,如果有则查询子类及自身，否则查询全部数据
	      if($type != ""){
	        $allChilds =  $tree->getAllChilds($typeList,$type);
	        //判断是否有子级
	        $childsCount = count($allChilds);
	        //echo $childsCount;exit;
	        if($childsCount != 0){
	          $tId = $type.','.implode(",", $allChilds);
	        }else{
	          $tId = $type;
	        }
	        $where['t_id'] = ['in',$tId];
	      }else{
	        $where['t_id'] = ['neq',''];
	      }
	      //获取中间表的文章id
	      $aIds = D('HwmAdmin/ArticleRelation')->getArticleId($where);
	      $aId = '';
	      foreach ($aIds as $key => $value) {
	        $aId .= $value['a_id'].',';
	      }
	      $aId = rtrim($aId,',');
	      return $aId;

	}


}