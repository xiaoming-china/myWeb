<?php
/**
 * name：后台基类
 * data:2016-11-22
 * user:xiaoming
 */
namespace HwmAdmin\Controller;
use Think\Controller;

class AdminBasicController extends Controller {

   public function _initialize(){
      header("Content-type:text/html;charset=utf-8");
      define(UID,$_SESSION['AdminInfo']);
      $this->isLogin();
    }
    /**
     * [isLogin 判断是否登录]
     * @return boolean [description]
     */
    public function isLogin(){
      if(!UID){
        $this->redirect('/HwmAdmin/Public/login');
      }
    }
    /**
     * 获取菜单
     */
    public function getMenu(){

      $this->assign();
    }


}