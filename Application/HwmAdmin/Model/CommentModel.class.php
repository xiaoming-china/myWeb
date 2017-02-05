<?php
/*
 *文章评论模型
 */
namespace HwmAdmin\Model;
use Think\Model\RelationModel;

class CommentModel extends RelationModel{
	/**
	 * [GetArticleComment 获取文章评论数据]
	 * @param [type] $aId [description]
	 */
	public function GetArticleComment($aId){
		if($aId == ''){
			return FALSE;
		}else{
			$list = $this->where(['c_id'=>$aId])->select();
			return $list;
		}
	}




}