<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
   //主页
   public function index(){
      $tree = new \Org\Util\Indefinitely();
      $p      = I('GET.p','1','intval');
      $title  = I('GET.title');
      $type   = I('GET.type');
      $map['title'] = ['like','%'.$title.'%'];
      //获取分类下所有的文章id
      $aId = D("HwmAdmin/Article")->getArticleListId($type);
      //获取文章
      $map['a_id'] = ['in',$aId];
      $count = D('HwmAdmin/Article')->countArticle($map);
      $Page  = new \Think\Page($count,C('page_number'));
      $page_show = $Page->show();
      //文章列表
      $articleList = D('HwmAdmin/Article')->getArticleList($map,$p,C('page_number'));
      //将文章和分类合并
      foreach ($articleList as $key => $value) {
       $articleList[$key]['type'] = D('HwmAdmin/ArticleRelation')->getTypeId($value['a_id']);
      }
      //分类列表
      $this->assign('typeList',$tree->infinite($typeList));
      $this->assign('page_show',$page_show);
      $this->assign('articleList',$articleList);
      $this->display();
   }


}