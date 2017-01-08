<?php

namespace Org\Util;
use Think\Model;

class Wx {

    private $app_id = 'wx634c0a00eeac8dd4';
    private $app_secret = '350838f4081bae5c740226f88ee34b68';
    private $access_token = '';
    private $agree_access_token = '';

    //存储access_token
    public function access_token(){
        $access_token = S('access_token');
        if($access_token){
            $this->access_token = $access_token;
        }else{
            $result = $this->get("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->app_id}&secret={$this->app_secret}");
            $result = json_decode($result, true);
            if(isset($result['access_token']) && isset($result['expires_in'])){
                S('access_token', $result['access_token'], 7200);
                $this->access_token = $result['access_token'];
            }
        }
    }

    public function jsapi_ticket(){
        $this->access_token();
        $jsapi_ticket = S('jsapi_ticket');
        if(!$jsapi_ticket){
            $result = $this->get("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$this->access_token}&type=jsapi");
            $result = json_decode($result, true);
            if(isset($result['ticket'])){
                S('jsapi_ticket', $result['ticket'], 7200);
                $jsapi_ticket = $result['ticket'];
            }
        }
        $time = time();
        $noncestr = md5($time.rand(100,999));
        $timestamp = $time;
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $str = "jsapi_ticket={$jsapi_ticket}&noncestr={$noncestr}&timestamp={$timestamp}&url={$url}";
        $signature = sha1($str);
        $d['app_id'] = $this->app_id;
        $d['timestamp'] = $timestamp;
        $d['nonceStr'] = $noncestr;
        $d['signature'] = $signature;
        return $d;
    }

    //网页授权
    public function web_agree(){
        $open_id = $_COOKIE['openid'];
        $access_token = $_COOKIE['agree_access_token'];
        if($access_token){
            $this->agree_access_token = $access_token;
        }
        if(!$open_id || !$access_token){
            if(!$_GET['code']){
                $urlObj = array();
                $urlObj['appid'] = $this->app_id;
                $urlObj['redirect_uri'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $urlObj['response_type'] = 'code';
                $urlObj['scope'] = 'snsapi_userinfo';//snsapi_base
                $urlObj['state'] = 'STATE' . '#wechat_redirect';
                $queryStr = http_build_query($urlObj);
                $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . $queryStr;
                header("Location:{$url}");
            }else{
                $res = $this->get("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$_GET['code']}&grant_type=authorization_code");
                $res = json_decode($res, true);
                setcookie('openid', $res['openid'], time()+7200);
                setcookie('agree_access_token', $res['access_token'], time()+7200);
                $this->agree_access_token = $res['access_token'];
                return $res['openid'];
            }
        }else{
            return $open_id;
        }
    }

    //获取用户信息
    public function user_info(){
        $openid = $this->web_agree();
        $res = $this->get("https://api.weixin.qq.com/sns/userinfo?access_token={$this->agree_access_token}&openid={$openid}&lang=zh_CN");
        $res = json_decode($res, true);
        return $res;
    }

    private function get($url = ''){
        if(!$url) return null;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}