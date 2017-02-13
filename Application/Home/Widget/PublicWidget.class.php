<?php
/**
 * 公共内容
 */
namespace Home\Widget;
use Think\Controller;
class PublicWidget extends Controller {
	/**
	 * [hotList 热门内容列表]
	 * @Author:xiaoming
	 * @DateTime        2017-02-13T21:49:15+0800
	 * @param           [type]$pageSize [数据条数]
	 * @return          [type][description]
	 */
    public function hotList($pageSize = 10){
    	
    	$where['is_hot'] = ['eq',1];
    	$where['status'] = ['eq',1];
    	$list = D('HwmAdmin/Article')->getArticleList($where,0,$pageSize ,$field = 'a_id,title');
    	// var_dump($list);
    	$this->assign('hotList',$list);
    	$this->display('Widget/HotList/hotList');
    }
    /**
     * [labelList 标签数据列表显示]
     * @Author:xiaoming
     * @DateTime        2017-02-13T22:36:32+0800
     * @param           [type]                   $num [标签数量]
     * @return          [type]                        [description]
     */
    public function labelList($num){
        
        $this->display('Widget/LabelList/labelList');
    }
}