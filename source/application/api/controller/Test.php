<?php

namespace app\api\controller;

use app\api\model\User;
use app\api\model\UserFavoriteShop;
use app\common\library\wechat\WxUser;
use app\common\model\Order;
use app\common\model\Shop;
use app\common\model\ShopDynamic;
use think\Cache;
Vendor('qcloudsms.src.index');
use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsVoiceVerifyCodeSender;
use Qcloud\Sms\SmsVoicePromptSender;
use Qcloud\Sms\SmsStatusPuller;
use Qcloud\Sms\SmsMobileStatusPuller;

use Qcloud\Sms\VoiceFileUploader;
use Qcloud\Sms\FileVoiceSender;
use Qcloud\Sms\TtsVoiceSender;
set_time_limit(0);
/**
 * 首页控制器
 * Class Index
 * @package app\api\controller
 */
class Test extends Controller
{
    public function test(){
// 短信应用SDK AppID
        $appid = 1400176762; // 1400开头

// 短信应用SDK AppKey
        $appkey = "1384ba05d7d6a69b3f64fe0d7d90d3b2";

// 需要发送短信的手机号码
        $phoneNumbers = 13380050751;

// 短信模板ID，需要在短信应用中申请
        $templateId = 7839;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

// 签名
        $smsSign = "腾讯云"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`

// 单发短信
        try {
            $ssender = new SmsSingleSender($appid, $appkey);

            $result = $ssender->send(0, "86", $phoneNumbers,"【腾讯云】您的验证码是: 新的订单", "", "");
            var_dump($result);exit;
            $rsp = json_decode($result);
            echo $result;
        } catch(\Exception $e) {
            echo var_dump($e);
        }
        echo "\n";
        var_dump($rsp);
    }

    public function index($openid='oVqpZ5NCx6XELqx2CEdPQbrcrmEw',$form_id)
    {
        $template_id = 'lXPzq1f7gh_xufKDX0XOM91KmoFBU_SB4A8tfShSN6k';
        $model=new User();
        $data=$model->wxopen($openid,$template_id,$form_id);
        return $this->renderSuccess(compact('data'));
    }
}
