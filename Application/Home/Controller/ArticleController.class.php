<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller {
   //主页 
   public function index(){    
      $p      = I('GET.p','1','intval');
      $title  = I('GET.title');
      $type   = I('GET.type');
      $map['title'] = ['like','%'.$title.'%'];
      
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
      foreach ($aIds as $key => $value) {
        $aId .= $value['a_id'].',';
      }
      $aId = rtrim($aId,',');
      var_dump($aId);exit;
      //获取文章
      $map['a_id'] = ['in',$aId];
      //分页
      $count = D('HwmAdmin/Article')->countArticle($map);
      $Page  = new \Think\Page($count,C('page_number'));
      $page_show = $Page->show();
      //文章列表
      $articleList = D('HwmAdmin/Article')->getArticleList($map,$p,C('page_number'));
      //将文章和分类合并
     foreach ($articleList as $key => $value) {
       $articleList[$key]['type'] = D('HwmAdmin/ArticleRelation')->getTypeId($value['a_id']);
     }

     //p($articleList);

      //分类列表
      $this->assign('typeList',$tree->infinite($typeList));
      $this->assign('page_show',$page_show);
      $this->assign('articleList',$articleList);
      $this->display();
   }
   /**
    * [detail 文章详情]
    * @return [type] [description]
    */
   public function detail(){
    $id          = I('GET.aId','','intval');
    $map['a_id'] = $id;
    $rs          = D('HwmAdmin/Article')->getOnlyArticle($map);
    // var_dump($rs);
    $this->assign('aInfo',$rs);
    $this->display();

   }

       


}