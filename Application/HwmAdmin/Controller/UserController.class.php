<?php
/**
 * name：用户管理主页
 * data:2016-11-13
 * user:xiaoming
 */
namespace HwmAdmin\Controller;
use Think\Controller;
class UserController extends AdminBasicController {
   /**
    * [UserList 用户列表]
    */
   public function UserList(){
      $mobile = I('GET.mobile');
      $status = I('GET.status');
      $type   = I('GET.type');
      $map['mobile'] = ['like','%'.$mobile.'%'];
      if($type != ''){
        $map['class']  = ['eq',$type];
      }
      if($status != ''){
        $map['status'] = ['eq',$status];
      }
      //分页
      $count = D('HwmAdmin/User')->countUser($map);
      $Page  = new \Think\Page($count,C('page_number'));
      $page_show = $Page->show();
      $rs = D('HwmAdmin/User')
            ->relation(true)
            ->where($map)
            ->order('reg_time DESC')
            ->limit($Page->firstRow,$Page->listRows)
            ->select();
      /*会员类型*/
      $tMap['t_type'] = ['eq',1];
      $type = D('HwmAdmin/Type')->Type($tMap,'t_id,type_name');

      $this->assign('page_show',$page_show);
      $this->assign('userList',$rs);
      $this->assign('userType',$type);
      $this->display();
   }
   /**
    * [addUser 添加会员]
    */
   public function addUser(){

   	$this->display();
   }
    /**
    * [viewUser 查看会员]
    * @return [type] [description]
    */
   public function viewUser(){
    $id = I('POST.id') OR fail('参数错误');
   
    $rs = D('HwmAdmin/User')->relation(true)->find($id);
    $rs['reg_time']    = date('Y-m-d H:i:s',$rs['reg_time']);
    $rs['update_time'] = date('Y-m-d H:i:s',$rs['update_time']);
    unset($rs['password']);
    return_json($rs);
   }
   /**
    * [editUser 编辑会员状态]
    * @return [type] [description]
    */
   public function chageStatus(){
     $id  = I('POST.id') OR fail('参数错误!');
     $userInfo   = D('HwmAdmin/User')->getOnlyUser(array('uid'=>$id),'uid,status');
     $map['uid'] = ['in',$id];

     if($userInfo['status'] == 0){
      $d['status'] = 1;
     }else{
      $d['status'] = 0;
     }
     $d['update_time'] = time();
     $rs = D('HwmAdmin/User')->editUser($map,$d);
     $rs ? success('修改成功') : fail('修改失败，未知错误');
   }
   /**
    * [delUser 删除会员]
    * @return [type] [description]
    */
   public function delUser(){
    $id = I('POST.id') OR('参数错误');
    $map['uid'] = ['in',$id];
    $rs = D('HwmAdmin/User')->delUser($map);
    $rs ? success('删除成功') : fail('删除失败，未知错误');
   }
   //**************分类管理**************//
   /**
    * [userType 会员分类管理]
    * @return [type] [description]
    */
   public function userType(){
    $map['t_type']      = ['eq',1];
    $map['type_status'] = ['neq',3];
    $rs = D('HwmAdmin/Type')->Type($map);
    $this->assign('typeList',$rs);
    $this->display();
   }
   /**
    * [editType 禁用分类管理]
    * @return [type] [description]
    */
   public function chageTypeStatus(){
     $id  = I('POST.id') OR fail('参数错误!');
     $typeInfo    = D('HwmAdmin/Type')->getOnlyType(array('t_id'=>$id),'t_id,type_status');
     $map['t_id'] = ['in',$id];

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
    $id = I('POST.id') OR('参数错误');
    $map['t_id'] = ['in',$id];
    $rs = D('HwmAdmin/Type')->delType($map);
    $rs ? success('删除成功') : fail('删除失败，未知错误');
   }
   /**
    * [addType 添加类型]
    */
   public function addType(){
    if(IS_POST){
      $d['type_name']   = I('POST.name') OR('名称不能为空');
      $d['type_desc']   = I('POST.desc') OR('描述不能为空');
      $d['type_status'] = I('POST.status');
      $d['type_sort']   = I('POST.sort');
      $d['t_type']      = 1;
      $d['add_time']    = time();
      $d['update_time'] = time();
      $rs = D('HwmAdmin/Type')->addType($d);
      $rs ? success('添加成功',U('HwmAdmin/User/UserType')) : fail('添加失败，未知错误');
    }
    $this->display();
   }
    /**
    * [addType 编辑类型]
    */
   public function editType(){
      if(IS_POST){
        $d['type_name']   = I('POST.name') OR('名称不能为空');
        $d['type_desc']   = I('POST.desc') OR('描述不能为空');
        $d['type_status'] = I('POST.status');
        $d['type_sort']   = I('POST.sort');
        $d['update_time'] = time();
        $map['t_id'] = ['eq',I('POST.tId','','intval')];
        $rs = D('HwmAdmin/Type')->editType($map,$d);
        $rs ? success('编辑成功',U('HwmAdmin/User/UserType')) : fail('编辑失败，未知错误');
      }else{
        $id = I('GET.id','','intval');
        $map['t_id'] = ['eq',$id];
        $rs = D('HwmAdmin/Type')->getOnlyType($map);
        $this->assign('typeInfo',$rs);
        $this->display();
      }
   }

       


}