<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends Controller {
   /**
    * 留言列表
    * @Author:xiaoming
    * @DateTime        2017-02-07T15:48:15+0800
    * @return          [type]                   [description]
    */
   public function messageList(){
   	  $where['is_show'] = ['eq',1];
      $rs = D('HwmAdmin/Message')->getMessageList($where);
      $this->assign('messageList',$rs);
      $this->display();
   }
   /**
    * [addMessage 添加留言信息]
    * @Author:xiaoming
    * @DateTime        2017-02-07T15:48:37+0800
    */
   public function addMessage(){
     	$message = I('POST.message') OR fail('留言内容不能为空');
     	$d['u_message'] = $message;
     	$d['u_ip']      = getIPaddress();
     	$d['add_time']  = time();
     	$rs = D('HwmAdmin/Message')->addMessage($d);
     	$rs ? success('添加成功') : fail('添加失败，未知错误');
   }


       


}