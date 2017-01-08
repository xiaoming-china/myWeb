<?php
 
 /**
 * 微信授权相关接口
 * 
 * 
 */

namespace Org\Util;
use Think\Model;
 
class Wechatauth {
    
    private $app_id = 'wx634c0a00eeac8dd4';
    private $app_secret = '350838f4081bae5c740226f88ee34b68';
    
    //通过code换取网页授权access_token
    public function access_token(){
        $code = $_GET['code'];
        $state = $_GET['state'];
        if($code == 'authdeny'){
            echo "授权失败!";
            exit;
        }
        $res = \Org\Util\Curl::get("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx634c0a00eeac8dd4&secret=350838f4081bae5c740226f88ee34b68&code={$code}&grant_type=authorization_code");
        $res = json_decode($res, true);
        if($res['errcode'] > 0){
            echo json_encode($res);exit;
        }
        return $this->getUserInfo($res['access_token'], $res['openid']);
    }

    //获取用户信息
    public function getUserInfo($access_token, $openid){
        $res = \Org\Util\Curl::get("https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN");
        $res = json_decode($res, true);
        if($res['errcode'] > 0){
            echo json_encode($res);exit;
        }
        return $res;
    }
 
 }