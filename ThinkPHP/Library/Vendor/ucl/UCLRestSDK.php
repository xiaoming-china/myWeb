<?php

/*
 *  Copyright (c) 2015 The UCLBRT project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Guangzhou Changlian Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.uclbrt.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

class REST {

    private $AccountAccess;
    private $AccountToken;
    private $ServerIP;
    private $ServerPort;
    private $Batch;  //时间戳
    private $enabeLog = true;  //日志开关
    private $Filename = "../log.txt";  //日志文件
    private $Handle;

    function __construct($ServerIp, $ServerPort) {
        $this->Batch = date("YmdHis");
        $this->ServerIP = $ServerIp;
        $this->ServerPort = $ServerPort;
        $this->Handle = fopen($this->Filename, 'a');
    }

    /**
     * 设置帐号
     * @param type $AccountAccess
     * @param type $AccountToken
     */
    function setAccount($AccountAccess = '', $AccountToken) {
        $this->AccountAccess = $AccountAccess;
        $this->AccountToken = $AccountToken;
    }

    /**
     * 打印日志
     * @param type $log
     */
    function showlog($log) {
        if ($this->enabeLog) {
            //fwrite($this->Handle, $log . "\r\n");
        }
    }

    function curl_post($url, $header, $post = 1) {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res = curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if ($post)
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        //连接失败
        if ($result == FALSE) {
            $result = "{\"status\":\"403\",\"info\":\"网络错误\"}";
        }
        curl_close($ch);
        return $result;
    }

    /**
     * 获取二维码接口
     * @param type $communityNo  集群编号
     * @param type $buildNo  楼栋编号
     * @param type $floorNo  楼层编号
     * @param type $roomNo  房间编号
     * @param type $path  生成二维码图片路径
     * @param type $filename  生成二维码文件名
     * @return type  返回Json
     */
    function getQrcode($communityNo, $buildNo, $floorNo, $roomNo, $startTime = '', $endTime= '', $batchMethod = 0, $path = '', $filename = '') {
        $auth = $this->accAuth();
        if ($auth != "") {
            $this->showlog(date("Y-m-d H:i:s") . " " . $auth['status'] . " " . $auth['message']);
            return $auth;
        }
        //大写的sig参数
        $sig = strtoupper(md5($this->AccountAccess . $this->AccountToken . $this->Batch));
        //生成请求的URL 
        $url = "https://$this->ServerIP:$this->ServerPort/?c=Qrcode&a=get&communityNo=$communityNo&buildNo=$buildNo&floorNo=$floorNo&roomNo=$roomNo&startTime=$startTime&endTime=$endTime&batchmethod=$batchMethod&sig=$sig";
        $this->showlog(date('Y-m-d H:i:s') . " request url = " . $url);
        //生成授权： 主账户Id + 英文冒号 + 时间戳
        $authen = base64_encode($this->AccountAccess . ":" . $this->Batch);
        //生成包头
        $header = array("Accept:application/json", "Content-type:application/json;charset=utf-8", "Authorization: $authen");
        //发送请求
        $result = $this->curl_post($url, $header);
        $result = json_decode($result);
        if ($result->status == 200) {
            $path = $path ? $path : dirname(dirname(__FILE__)) . "/";
            $filename = $filename ? $filename : date("YmdHis") . ".png";
            $handle = fopen($path . $filename, "w");  //打开文件准备写入文件
            fwrite($handle, base64_decode($result->info));  //写入文件
            fclose($handle); //关闭
            $this->showlog(date('Y-m-d H:i:s') . 'request result ' . $result->status . "  success");
            return json_encode(array('status' => 200, 'info' => $filename,'batch' => $result->batch));
        } else {
            $this->showlog(date('Y-m-d H:i:s') . "request result " . $result->status . " " . $result->info);
            return json_encode(array('status'=>$result->status,'info'=>$result->info));
        }
    }

    /**
     * 主帐号鉴权
     */
    function accAuth() {
        if ($this->ServerIP == "") {
            $data['status'] = '202';
            $data['message'] = 'IP为空';
            return $data;
        }
        if ($this->ServerPort <= 0) {
            $data['status'] = '202';
            $data['message'] = '端口错误（小于等于0）';
            return $data;
        }
        if ($this->AccountAccess == "") {
            $data['status'] = '202';
            $data['message'] = '主帐号为空';
            return $data;
        }
        if ($this->AccountToken == "") {
            $data['status'] = '202';
            $data['message'] = '主帐号令牌为空';
            return $data;
        }
    }

}
