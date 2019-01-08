<?php

namespace app\common\library\wechat;

/**
 * 微信小程序用户管理类
 * Class WxUser
 * @package app\common\library\wechat
 */
class WxUser
{
    private $appId;
    private $appSecret;

    private $error;

    /**
     * 构造方法
     * WxUser constructor.
     * @param $appId
     * @param $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * 获取session_key
     * @param $code
     * @return array|mixed
     */
    public function sessionKey($code)
    {
        /**
         * code 换取 session_key
         * ​这是一个 HTTPS 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。
         * 其中 session_key 是对用户数据进行加密签名的密钥。为了自身应用安全，session_key 不应该在网络上传输。
         */
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $result = json_decode(curl($url, [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => 'authorization_code',
            'js_code' => $code
        ]), true);
        if (isset($result['errcode'])) {
            $this->error = $result['errmsg'];
            return false;
        }
        return $result;
    }

    public function getError()
    {
        return $this->error;
    }
    /**
     * 获取access_token
     * @param $code
     * @return array|mixed
     */
    public function getAccessToken()
    {
        /**
         * 调用各后台接口时都需使用 access_token，开发者需要进行妥善保存。
         */
        //if(!cache('access_token')) {
            $url = 'https://api.weixin.qq.com/cgi-bin/token';
            $result = json_decode(curl($url, [
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'grant_type' => 'client_credential',
            ]), true);
            if (isset($result['errcode'])) {
                $this->error = $result['errmsg'];
                return false;
            }
            cache('access_token', $result, 3600);
       // }
        return cache('access_token');
    }
    /**
     * 发送小程序模板
     * @param $touser  接收者（用户）的 openid
     * @param $template_id   所需下发的模板消息的id
     * $param $access_token
     * @return array|mixed
     */
    public function sendTemplate($touser,$template_id,$access_token,$form_id)
    {
       // uniform_send
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$access_token;
        $result = json_decode(postCurl($url, json_encode([
            'touser' => $touser,
            'template_id' => $template_id,
            'form_id' =>$form_id,
            'data'=>[
                'keyword1'=>['value'=>'111'],
                'keyword2'=>['value'=>'222'],
                'keyword3'=>['value'=>'333'],
                'keyword4'=>['value'=>'444'],
            ]
        ])), true);
        if (isset($result['errcode'])) {
            $this->error = $result['errmsg'];
            return false;
        }
        return $result;
    }
}