<?php
namespace Home\Controller;
use Think\Controller;
class PhotoController extends Controller {
   //相册列表
   public function photoList(){
   	  $p              = I('GET.p','1','intval');
   	  $map['is_show'] = ['eq',1];
   	  //分页
      $count     = D('HwmAdmin/PhoneType')->countPhone($map);
      $Page      = new \Think\Page($count,C('page_number'));
      $page_show = $Page->show();

      $pList = D('HwmAdmin/PhoneType')->getPhoneList($map);
      foreach ($pList as $key => $value) {
      	$w['p_id']    = ['eq',$value['p_id']];
      	$w['is_show'] = ['eq',1];
      	$pList[$key]['count'] = D('HwmAdmin/Phone')->countPhone($w);
      }
      //var_dump($pList);

      $this->assign('page_show',$page_show);
      $this->assign('pList',$pList);
      $this->display();
   }
   /**
    * [phoneDetail 图片列表]
    * @return [type] [description]
    */
   public function photoDetail(){
   		IS_POST ? '' : fail('非法操作');
	   	$pId  = I('POST.pId','','intval');//相册id
	   	$pass = I('POST.pass');
	   	$rs = D('HwmAdmin/PhoneType')->find($pId);
	   	if($rs['is_lock'] == 1){
	   		if($pass == $rs['p_password']){
	   			$data  = D('HwmAdmin/PhoneType')->photoList($pId);
	   		}else{
	   			fail('参数错误，请稍候再试'); 
	   		}
	   	}else{
	   		$data  = D('HwmAdmin/PhoneType')->photoList($pId);
	   	}
	   	//更新相册点击数
	   	$where['p_id']  = ['eq',$pId];
	   	$d['p_view'] = $rs['p_view'] + 1;
	   	$view = D('HwmAdmin/PhoneType')->editPhone($where,$d);
	   	return_json($data);
	}
	/**
	 * [isTrue 验证相册密码]
	 * @return boolean [description]
	 */
	public function isTrue(){
		$pId  = I('POST.pId','','intval') OR fail('相册不能为空');//相册id
		$pass = I('POST.pass','') OR fail('密码不能为空');
		$where['p_id'] = ['eq',$pId];
		$rs   = D('HwmAdmin/PhoneType')->getOnlyPhone($where);
		if($rs){
			if($pass == $rs['p_password']){
				success('密码正确');
			}else{
				fail('密码错误');
			}
		}
	}
}