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
			$list = $this->where(['article_id'=>$aId,'partent_id'=>0])->order('add_time DESC')->select();
			//查找评论是否有回复
			foreach ($list as $key => $value) {
				$list[$key]['reply'] = $this->where(['partent_id'=>$value['c_id']])->order('add_time DESC')->select();
			}
			return $list;
		}
	}
	/**
	 * [addComment 添加文章评论]
	 * @Author:xiaoming
	 * @DateTime        2017-02-08T11:42:53+0800
	 * @param           [type]                   $data [description]
	 */
	public function addComment($data){
		return $this->add($data);
	}




}