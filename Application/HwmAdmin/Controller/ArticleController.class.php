<?php
/**
 * name：文章控制器
 * data:2016-11-22
 * user:xiaoming
 */
namespace HwmAdmin\Controller;
use Think\Controller;

class ArticleController extends AdminBasicController {
   /**
    * [index 文章列表]
    * @return [type] [description]
    */
   public function articleList(){
      $p      = I('GET.p','1','intval');
      $title  = I('GET.title');
      $status = I('GET.status');
      $type   = I('GET.type');
      $map['title'] = ['like','%'.$title.'%'];
      if($status != ''){
        $map['status'] = ['eq',$status];
      }
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
      //分类列表
      $this->assign('typeList',$tree->infinite($typeList));
      $this->assign('page_show',$page_show);
      $this->assign('articleList',$articleList);
      $this->display();
   }
   /**
    * [addArticle 添加文章]
    */
   public function articleAdd(){
     if(IS_POST){
      $articleModel = D('HwmAdmin/Article');
      $articleType  = D('HwmAdmin/ArticleRelation');
      $articleModel->startTrans();
        //文章数据
      $a['title']       = I('POST.title') OR fail('标题不能为空');
      $a['keyword']     = I('POST.keyword');
      $a['tag']         = I('POST.tags');
      $a['desc']        = I('POST.desc') OR fail('描述不能为空');
      $a['covour']      = I('POST.article_thumb');
      $a['status']      = I('POST.status') OR fail('状态不能为空');
      $a['contents']    = I('POST.articleContent') OR fail('内容不能为空');
      $a['add_time']    = time();
      $a['update_time'] = time();

      $addArticle = $articleModel->addArticle($a);
      //文章分类数据
      $type  = I('POST.typeNumber');
      $count = count($type);
      if($count <= 0 ){
        fail('文章分类不能为空');
      }else{
        $t = [];
        foreach ($type as $key => $value) {
          $d['t_id'] = $value;
          $d['a_id'] = $addArticle;
          $t[] = $d;
        }
      }
      $articleType = $articleType->addRelation($t);
      if($addArticle && $articleType){
          $articleModel->commit();
          success('添加成功',U('Article/articleList'));
      }else{
          $articleModel->rollback(); 
          fail('添加失败，未知错误');
      }
     }
     //分类显示条件
      $tree = new \Org\Util\Indefinitely();
      $tMap['isadmin']     = ['eq',1];
      $tMap['t_type']      = ['eq',2];
      $tMap['type_status'] = ['eq',1];
      //所有分类
      $typeList = D('HwmAdmin/Type')->Type($tMap,'t_id,type_name,parent');
      $this->assign('typeList',$tree->infinite($typeList));
      $this->display();
   }
   /**
    * [delArticle 删除文章,删除文章后，还要把文章分类的数据删除]
    * @return [type] [description]
    */
   public function delArticle(){
      $id = I('POST.id') OR fail('参数错误');
      $map['a_id']  = ['in',$id];
      $articleModel =  D('HwmAdmin/Article');
      $RelationModel=  D('HwmAdmin/ArticleRelation');
      $articleModel->startTrans();

      $delArticle   = $articleModel->delArticle($map);
      $delRelation  = $RelationModel->delRelation($map);
      
      if($delArticle && $delRelation){
        $articleModel->commit();
        success('删除成功');
      }else{
        $articleModel->rollback(); 
        fail('删除失败，未知错误');
      }
   }
   /**
    * [articleEdit 编辑文章]
    * @return [type] [description]
    */
   public function articleEdit(){
    if(IS_POST){
      $id = I('POST.aId') OR fail('文章id不能为空');
      $map['a_id'] = ['eq',$id];

      $articleModel = D('HwmAdmin/Article');
      $articleType  = D('HwmAdmin/ArticleRelation');
      $articleModel->startTrans();
      //文章数据
      $a['title']       = I('POST.title') OR fail('标题不能为空');
      $a['keyword']     = I('POST.keyword');
      $a['tag']         = I('POST.tags');
      $a['desc']        = I('POST.desc') OR fail('描述不能为空');
      $a['thumb_img']   = I('POST.article_thumb');
      $a['status']      = I('POST.status') OR fail('状态不能为空');
      $a['contents']    = I('POST.articleContent') OR fail('内容不能为空');
      $a['update_time'] = time();

      $editArticle = $articleModel->editArticle($map,$a);

      //文章分类数据
      $type  = I('POST.typeNumber');
      $count = count($type);
      if($count <= 0 ){
        fail('文章分类不能为空');
      }else{
        $t = [];
        foreach ($type as $key => $value) {
          $d['t_id'] = $value;
          $d['a_id'] = $id;
          $t[] = $d;
        }
      } 
        //修改文章分类前，先将以前的全部删除
        $delType     = $articleType->delRelation($map);
        $articleType = $articleType->addRelation($t);
        
        if($editArticle && $delType && $articleType){
            $articleModel->commit();
            success('编辑成功',U('Article/articleList'));
        }else{
            $articleModel->rollback();
            fail('编辑失败，未知错误');
        }
      }
        //文章数据
        $id = I('GET.id','','intval');
        $map['a_id'] = ['eq',$id];
        $aInfo = D('HwmAdmin/Article')->getOnlyArticle($map);
        $aInfo['type'] = D('HwmAdmin/ArticleRelation')->getTypeId($aInfo['a_id']);
        $this->assign('aInfo',$aInfo);
        //分类显示条件
        $tree = new \Org\Util\Indefinitely();
        $tMap['isadmin']     = ['eq',1];
        $tMap['t_type']      = ['eq',2];
        $tMap['type_status'] = ['eq',1];
        //所有分类
        $typeList = D('HwmAdmin/Type')->Type($tMap,'t_id,type_name,parent');
        $this->assign('typeList',$tree->infinite($typeList));
        $this->display();
      }
    /**
    * [editUser 编辑文章状态]
    * @return [type] [description]
    */
   public function chageStatus(){
     $id = I('POST.id') OR fail('参数错误!');
     $articleInfo = D('HwmAdmin/Article')->getOnlyArticle(array('a_id'=>$id),'a_id,status');
     $map['a_id'] = ['in',$id];
     if($articleInfo['status'] == 0){
      $d['status'] = 1;
     }else{
      $d['status'] = 0;
     }
     $d['update_time'] = time();
     $rs = D('HwmAdmin/Article')->editArticle($map,$d);
     $rs ? success('修改成功') : fail('修改失败，未知错误');
   }
   //**************文章分类管理**************//
   /**
    * [userType 文章分类管理]
    * @return [type] [description]
    */
   public function articleType(){
    //记得把父级禁用，子级也要禁用加上，切记，现在没空
    $map['t_type']      = ['eq',2];
    $rs = D('HwmAdmin/Type')->Type($map);
    $tree = new \Org\Util\Indefinitely();
    $this->assign('typeList',$tree->navigation($rs));
    $this->display();
   }
   /**
    * [editType 禁用分类管理]
    * @return [type] [description]
    */
   public function chageTypeStatus(){
     $id  = I('POST.id') OR fail('参数错误!');
     $typeInfo    = D('HwmAdmin/Type')->getOnlyType(array('t_id'=>$id),'t_id,type_status');
     //所有分类
     $tree = new \Org\Util\Indefinitely();
     $tMap['isadmin']     = ['eq',1];
     $tMap['t_type']      = ['eq',2];
     $tMap['type_status'] = ['eq',1];
     $typeList = D('HwmAdmin/Type')->Type($tMap,'t_id,type_name,parent');
     $allChilds =  $tree->getAllChilds($typeList,$id);
     if(count($allChilds) != 0){
       $ids = $id.','.implode(",", $allChilds);
     }else{
       $ids = $id;
     }
     $map['t_id'] = ['in',$ids];
     if($typeInfo['type_status'] == 0){
      $d['type_status'] = 1;
     }else{
      $d['type_status'] = 0;
     }
     $d['update_time'] = time();
     $rs = D('HwmAdmin/Type')->editType($map,$d);
     $rs ? success('修改成功') : fail('修改失败，未知错误');
   }
    /**
    * [delUser 删除类型]
    * @return [type] [description]
    */
   public function delType(){
    $id = I('POST.id') OR fail('参数错误');
     //所有分类
     $tree = new \Org\Util\Indefinitely();
     $tMap['isadmin']     = ['eq',1];
     $tMap['t_type']      = ['eq',2];
     $tMap['type_status'] = ['eq',1];
     $typeList = D('HwmAdmin/Type')->Type($tMap,'t_id,type_name,parent');
     $allChilds =  $tree->getAllChilds($typeList,$id);
     if(count($allChilds) != 0){
       $ids = $id.','.implode(",", $allChilds);
     }else{
       $ids = $id;
     }
    $map['t_id'] = ['in',$ids];
    $rs = D('HwmAdmin/Type')->delType($map);
    $rs ? success('删除成功') : fail('删除失败，未知错误');
   }
   /**
    * [addType 添加类型]
    */
   public function addType(){
      if(IS_POST){
        $d['type_name']   = I('POST.name') OR fail('名称不能为空');
        $d['type_desc']   = I('POST.desc') OR fail('描述不能为空');
        $d['parent']      = I('POST.parent');
        $d['type_status'] = I('POST.status');
        $d['type_sort']   = I('POST.sort');
        $d['t_type']      = 2;
        $d['add_time']    = time();
        $d['update_time'] = time();
        $rs = D('HwmAdmin/Type')->addType($d);
        $rs ? success('添加成功',U('HwmAdmin/Article/articleType')) : fail('添加失败，未知错误');
      }else{
        $where['t_type']      = ['eq',1];
        $where['type_status'] = ['eq',1];
        $rs = D('HwmAdmin/Type')->Type($where,'t_id,type_name,parent');
        $tree = new \Org\Util\Indefinitely();
        $this->assign('typeList',$tree->infinite($rs));
        $this->display();
      }
   }
    /**
    * [addType 编辑类型]
    */
   public function editType(){
      if(IS_POST){
        $d['type_name']   = I('POST.name') OR fail('名称不能为空');
        $d['type_desc']   = I('POST.desc') OR fail('描述不能为空');
        $d['parent']      = I('POST.parent');
        $d['type_status'] = I('POST.status');
        $d['type_sort']   = I('POST.sort');
        $d['update_time'] = time();
        $map['t_id'] = ['eq',I('POST.tId','','intval')];
        $rs = D('HwmAdmin/Type')->editType($map,$d);
        $rs ? success('编辑成功',U('HwmAdmin/Article/articleType')) : fail('编辑失败，未知错误');
      }else{
        $id = I('GET.id','','intval');
        $map['t_id'] = ['eq',$id];
        $rs = D('HwmAdmin/Type')->getOnlyType($map);
        $this->assign('typeInfo',$rs);
        //所有类型
        $tMap['isadmin']      = ['eq',1];
        $where['t_type']      = ['eq',2];
        $where['type_status'] = ['eq',1];
        $typeList = D('HwmAdmin/Type')->Type($where,'t_id,type_name,parent');
        $tree = new \Org\Util\Indefinitely();
        $this->assign('typeList',$tree->infinite($typeList));
        $this->display();
      }
   }
       


}