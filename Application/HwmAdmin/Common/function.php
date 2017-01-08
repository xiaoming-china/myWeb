<?php

function wxsq(){
	return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx634c0a00eeac8dd4&redirect_uri='.urlencode('http://www.520ejia.com/Wx/loginForChannel').'&response_type=code&scope=snsapi_userinfo&state=123456789#wechat_redirect';
}

function pingxx($url){
    //return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx634c0a00eeac8dd4&redirect_uri='.urlencode('http://www.baidu.com').'&response_type=code&scope=snsapi_base&state=123456789#wechat_redirect';
    $urlObj = array();
    $urlObj['appid'] = 'wx634c0a00eeac8dd4';
    $urlObj['redirect_uri'] = $url;
    $urlObj['response_type'] = 'code';
    $urlObj['scope'] = 'snsapi_userinfo';//snsapi_base
    $urlObj['state'] = 'STATE' . '#wechat_redirect';
    $queryStr = http_build_query($urlObj);
    return 'https://open.weixin.qq.com/connect/oauth2/authorize?' . $queryStr;
}