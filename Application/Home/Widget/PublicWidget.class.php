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
     /**
     * [labelList 前台导航数据列表显示]
     * @Author:xiaoming
     * @DateTime        2017-02-13T22:36:32+0800
     * @param           [type]                   $num [标签数量]
     * @return          [type]                        [description]
     */
    public function sidebar(){
        $where['ishome']  = ['eq',1];
        $where['parent']  = ['eq',0];
        $navList = D('HwmAdmin/Type')->Type($where);
        // var_dump($navList);
        $this->assign('navList',$navList);
        $this->display('Widget/Sidebar/Sidebar');
    }
    /**
     * [left 前台页面左边二级导航]
     * @Author:xiaoming
     * @DateTime        2017-02-20T15:49:18+0800
     * @return          [type]                   [description]
     */
    public function left(){
        $type = I('GET.type','','intval');
        $tree = new \Org\Util\Indefinitely();//无限级类
        $tMap['t_type']      = ['eq',2];
        $tMap['type_status'] = ['eq',1];
        //所有分类
        $typeList = D('HwmAdmin/Type')->Type($tMap,'t_id,type_name,parent');
        //所有的二级导航
        $sub =  $tree->manyArray($typeList,$type);
        // var_dump($sub);
        $this->assign('sub',$sub);
        $this->display('Widget/Left/nav-sub');
    }


}