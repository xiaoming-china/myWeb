<?php

function p($arr){
  echo "<pre>";
  print_r($arr);
  exit;
}
/**
 * 获取当前页面完整URL地址
 * @return type 地址
 */
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self     = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info    = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url   = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}
/**
 * 字符截取
 * @param $string 需要截取的字符串
 * @param $length 长度
 * @param $dot
 */
function str_cut($sourcestr, $length, $dot = '...') {
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr); //字符串的字节数 
    while (($n < $length) && ($i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码 
        if ($ascnum >= 224) {//如果ASCII位高与224，
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符         
            $i = $i + 3; //实际Byte计为3
            $n++; //字串长度计1
        } elseif ($ascnum >= 192) { //如果ASCII位高与192，
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
            $i = $i + 2; //实际Byte计为2
            $n++; //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        } else {//其他情况下，包括小写字母和半角标点符号，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数计1个
            $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > strlen($returnstr)) {
        $returnstr = $returnstr . $dot; //超过长度时在尾处加上省略号
    }
    return $returnstr;
}

//管理密码加密
function encryption($password){
	return md5($password.C('KEY'));
}

/**
 * 验证用户密码的合法性（判断是否够安全）
 * @param  string $password 密码
 * @return string           密码
 */
function passwordLegality($password){
  $len = strlen($password);
  if($len<6 || $len>15 || !preg_match("/[a-zA-Z]/", $password)){
    errorCode(116);
  }else{
    return $password;
  }
}
//返回信息
function return_msg($msg){
  echo is_array($msg) ? json_encode($msg) : $msg;
  exit;
}

//返回信息
function return_json($data){
  echo json_encode($data);
  exit;
}

//成功返回
function success($info='success',$url='',$status='success'){
  $data['info'] = $info;
  $data['url'] = $url;
  $data['status'] = $status;
  echo json_encode($data);
  exit;
}
//失败返回
function fail($info='fail',$status='fail'){
  $data['info'] = $info;
  $data['status'] = $status;
  echo json_encode($data);
  exit;
}
//返回json错误信息
// function errorJson($info = '未知错误'){
//   $data[] = 
// }
//接口返回错误代码
function errorCode($codeId){
  $data['errorCode'] = $codeId;
  $data['errorMessage'] = C('errorCode.'.$codeId);
  echo json_encode($data);
  exit;
}
//返回原图
function return_original_img($thumb_img){
  return str_replace('thumb_','',$thumb_img);
}
//清除缓存
function clear_cache(){
  //Cache目录
  $Cache_dir = RUNTIME_PATH.'Cache/';
  $Cache = scandir($Cache_dir);
  foreach ($Cache as $k => $v) {
      if($v != '.' && $v != '..'){
          if (is_dir($Cache_dir.$v)) {
             foreach (scandir($Cache_dir.$v) as $key => $value) {
                 if($v != '.' && $v != '..'){
                      unlink($Cache_dir.$v.'/'.$value);
                 }
             }
          }
      }
  }

  //Temp目录
  $Temp_dir = RUNTIME_PATH.'Temp/';
  $Temp = scandir($Temp_dir);
  foreach ($Temp as $k => $v) {
      if($v != '.' && $v != '..'){
          unlink($Temp_dir.$v);
     }
  }
  //Data目录
  $Data_dir = RUNTIME_PATH.'Data/';
  $Data = scandir($Data_dir);
  foreach ($Data as $k => $v) {
      if($v != '.' && $v != '..'){
          unlink($Data_dir.$v);
     }
  }
}
function parentMenuName(){
  $url = CONTROLLER_NAME.'/'.ACTION_NAME;
  $pid = M('manage_menu')->where(array('url'=>$url))->getField('pid');
  $menuName = M('manage_menu')->where(array('id'=>$pid))->getField('name');
  return $menuName ? $menuName.' 》 ' : '';
}
/**
 * 生成SN码
 * @param  int $num 生成数量
 * @return [type]      [description]
 */
function create_sn($num){
  $time = date('ymdis',time());
  for ($i=0; $i < $num; $i++) { 
    $sn[] = $time.rand(10000,99999);
  }
  return $sn;
}
/**
 * 生成随机数
 * @param  int $num 生成数量
 * @return [type]      [description]
 */
function randNum($num){
  for ($i=0; $i < $num; $i++) { 
    $sn .= rand(0,9);
  }
  return $sn;
}
/**
 * 空数据列表模版
 * @param  json $data JSON字符串
 * @return [type]       [description]
 */
function emptyListTemplate($data){
  // $data = json_decode($data);
  if(count($data)<1){
    echo '<div class="emptydata" style="text-align:center;margin: 10px 0;font-size: 18px;">没有数据!</div>';
  }
}
/**
 * 验证手机的合法性
 * @param  string $phone 手机号
 * @return bool        有效返回true
 */
function phoneLegality($phone){
  return preg_match("/^13[0-9]\d{8}$|15[0-9]\d{8}$|18[0-9]\d{8}$|170\d{8}$/", $phone) ? true : false;
}

/**
 * 返回完整路径
 * @param  string $url 地址
 * @return string      完整地址
 */
function completeUrl($url){
  if(!$url){
    $url = '/Public/App/xiaolian.png';
  }
  return stristr($url, 'http://') ? $url : "http://{$_SERVER['HTTP_HOST']}/{$url}";
}

/**
 * 获取配置值
 * @param  string $keyName name
 * @return string          value
 */
function getConfig($keyName){
  $config = D('Config');
  $data = $config->getConfig();
  return $data[$keyName];
}



/**
 * [timediff 计算时间相隔，参数为时间戳]
 * @param  [type] $begin_time [description]
 * @param  [type] $end_time   [description]
 * @return [type]             [description]
 */
function timediff( $begin_time, $end_time ){
    $cle = $end_time - $begin_time; //得出时间戳差值
    $date['d'] = floor($cle/3600/24);
    $date['h'] = floor(($cle%(3600*24))/3600); 
    $date['m'] = floor(($cle%(3600*24))%3600/60);
    $date['s'] = floor(($cle%(3600*24))%60);
    //echo "两个时间相差 $d 天 $h 小时 $m 分 $s 秒"
    return $date;
}
/**
 * [location 百度地图进行定位，根据位置获取经度和纬度]
 * @param  [type] $address [description]
 * @return [type]          [description]
 */
function location($address){
    $Curl = new \Org\Util\Curl;
    $url  = 'http://api.map.baidu.com/place/v2/suggestion?query='.$address.'&region=131&output=json&output=json&ak='.C('BAIDU_IP_API');
    $d    = $Curl->get($url);
    $data = json_decode($d,true);
    if($data['status'] == 0 ){
      $rs['lat'] = $data['result'][0]['location']['lat'];
      $rs['lng'] = $data['result'][0]['location']['lng'];
    }else{
      $rs['lat'] = '';
      $rs['lng'] = '';
    }
    return $rs;
}
/**
 * [sendMessage 发送短信]
 * @param  [type] $code  [验证码]
 * @param  [type] $phone [手机号码]
 * @param  [type] $model [短信模板id，在阿里大于管理中心设置]
 * @param  [type] $type  [短信类型1注册2找回密码，3修改密码]
 * @return [type]        [description]
 */
function sendMessage($phone,$type){
    Vendor('sendMessage.TopSdk');
    $c = new \TopClient;
    $c->format    = 'json';
    $c->appkey    = '23470203';
    $c->secretKey = '12df4646ee1d12d82b2180257617962d';
    $c->simplify  = true;
    $req = new \AlibabaAliqinFcSmsNumSendRequest;
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("E路帮");
    $code = randNum(4);
    $req->setSmsParam("{\"code\":\"".$code."\"}");
    $req->setRecNum($phone);
    switch ($type) {
      case '1':
       $model = "SMS_16690592";
        break;
      case '2':
       $model = "SMS_16725663";
        break;
      case '3':
       $model = "SMS_16700595";
        break;
    }
    $req->setSmsTemplateCode($model);
    $resp = $c->execute($req);

    if(isset($resp->result->success)) {
      //将发送的数据写入到数据库
      $d['phone']    = $phone;
      $d['type']     = $type;
      $d['code']     = $code;
      $d['add_time'] = time();
      $rs = M('sms')->add($d);
      $rs ? success('发送成功') : fail('数据写入失败');
    }else{
      fail('失败代码：'.$resp->sub_code.'。失败原因：'.$resp->sub_msg);
    }
}
/**
 * [checkCode 校验验证码]
 * @param  [type] $where [description]
 * @return [type]        [description]
 */
function checkCode($where){
      $checkCode = D('Home/Public')->checkCode($where);
      if(!$checkCode){
        fail('验证码错误');
      }else{
        $time     = time();
        $sendTime = $checkCode['add_time'];
        $timediff = timediff($sendTime,$time);
        if($timediff['m'] > C('CHECK_MESSAGE_TIME')){
          fail('验证码已失效');
        }else if($checkCode['status'] == 1){
          fail('验证码已被校验');
        }else{
          $map['id']      = array('eq',$checkCode['id']);
          $data['status'] = 1;
          $rs = D('Home/Public')->updataCode($map,$data);//更新验证码状态
          return $rs ?  true : fail('验证码更新失败');
        }
      }
}
/**
 * [formatTime 人性化显示时间]
 * @param  [type] $time [时间戳]
 * @return [type]       [str]
 */
function formatTime($time){     
    $rtime = date("Y-m-d H:i",$time);     
    $htime = date("H:i",$time);           
    $time  = time() - $time;       
    if ($time < 60){         
      $str = '刚刚';     
    }elseif($time < 60 * 60){         
      $min = floor($time / 60);         
      $str = $min.'分钟前';     
    }elseif($time < 60 * 60 * 24){         
      $h = floor($time / (60*60));         
      $str = $h.'小时前 '.$htime;     
    }elseif($time < 60 * 60 * 24 * 3){         
      $d = floor($time / (60 * 60 * 24));         
      if($d == 1){
        $str = '昨天 '.$rtime;
      }else{
        $str = '前天 '.$rtime;     
      }
    }else{         
      $str = $rtime;     
    }     
    return $str; 
}

/**
 * 生成订单号
 * @return string 当前时间+5位随机数
 */
function createOrderSN(){
  $order_sn = date('ymdHis',time()).rand(10000,99999);
  return checkRepeatField('order_info','order_sn',$order_sn) ? createOrderSN() : $order_sn;
}
/**
 * [getIPaddress 获取ip地址]
 * @Author:xiaoming
 * @DateTime        2017-02-07T15:37:41+0800
 * @return          [type]                   [description]
 */
function getIPaddress(){
    $IPaddress='';
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $IPaddress = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $IPaddress = getenv("HTTP_CLIENT_IP");
        } else {
            $IPaddress = getenv("REMOTE_ADDR");
        }
    }
    return $IPaddress;
}
/**
 * [taobaoIP 调用新浪ip地址查询]
 * @Author:xiaoming
 * @DateTime        2017-02-07T15:38:27+0800
 * @param           [type]                   $clientIP [description]
 * @return          [type]                             [description]
 */
function sinaIP($clientIP){
    $url  = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$clientIP;
    $rs   = json_decode(file_get_contents($url));
    if($rs.ret == 1){
      $data = $rs->province.'省'.$rs->province.'市 的网友';
    }else{
      $data = '来自火星的网友';
    }
    return $data;
}
/**
 * [replaceEmjoy 表情正则替换]
 * @Author:xiaoming
 * @DateTime        2017-02-07T16:49:39+0800
 * @param           [type]                   $content [description]
 * @return          [type]                            [description]
 */
function replaceEmjoy($content){
  $rs = preg_replace("/\[emjoy:(\d+)\]/","<img src='/Public/Home/images/emjoy/$1.gif'/>",$content);
  return $rs;
}

