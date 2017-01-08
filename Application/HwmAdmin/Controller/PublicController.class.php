<?php
/**
 * name：公共控制器
 * data:2016-12-1
 * user:xiaoming
 */
namespace HwmAdmin\Controller;
use Think\Controller;
class PublicController extends Controller{
   /**
    * [login 登录]
    * @return [type] [description]
    */
   public function login(){
      if(IS_POST){
      	$map['mobile']   = I('AdminName') OR fail('账号不能为空');
      	$map['password'] = I('AdminPass') OR fail('密码不能为空');
        $map['status']   = ['eq',1];
      	$rs   = D('HwmAdmin/Admin')->getOnlyUser($map,'id,mobile,name,photo');
      	if($rs){
          $_SESSION['AdminInfo'] = $rs;
      		success('登录成功',U('HwmAdmin/Index/index'));
      	}else{
      		fail('登录失败,账号或密码错误');
      	}
      }
      $this->display();
   }

       


}