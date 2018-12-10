<?php

namespace app\common\model;

use think\Request;

/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class Finance extends BaseModel
{
    protected $name = 'shop_money_log';
    /**
     * 关联店铺地址表
     * @return \think\model\relation\HasMany
     */
    public function shop()
    {
        return $this->hasOne('shop',"shop_id","shop_id");
    }
    public function getList($shop_id)
    {
        return $this->where(['shop_id'=>$shop_id])
           // ->with(['shop'])
            ->order('create_time desc')
            ->paginate(10, false, [
                'query' => Request::instance()->request()
            ])
            ->each(function($item,$key){
                if($item['type']==0){
                    $item['type']='用户收货';
                }
                if($item['type']==1){
                    $item['type']='店铺提现';
                }
                if($item['type']==2){
                    $item['type']='服务费';
                }
                return $item;
            });
    }
    public function getShopMoney($shop_id)
    {
        return  db("shop")->where(['shop_id'=>$shop_id])->value("money");
    }

    public function getWithdrawalsList($shop_id=0)
    {
        //echo $shop_id;
        $where=[];
        if($shop_id>0){
            $where['shop_id']=$shop_id;
        }
       return  db("shop_withdrawals")
           ->where($where)
          //  ->with(['shop'])
            ->order('create_time desc')
            ->paginate(10, false, [
                'query' => Request::instance()->request()
            ])
            ->each(function($item,$key){
                if($item['status']=='0'){
                    $item['status_str']='待审核';
                }
                if($item['status']=='1'){
                    $item['status_str']='已打款';
                }
                if($item['status']=='-1'){
                    $item['status_str']='已拒绝';
                }
                return $item;
            });
    }

    public function alipay($data){
        Vendor('Alipay.AopSdk');
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId =''; //C("ALIPAY.APPID");  C("ALIPAY.rsaPrivateKey");//C("ALIPAY.alipayrsaPublicKey");//
        $aop->rsaPrivateKey ='ka0XCnAIwUp+IiBei2O4drXaWjpcT2N9v0eh/Kup2BdTKJUK5ZsT9NuXK/6fdPTm9LwahkMKU2i2HuF87gv0I7fmAquIxU831xtpF5cbGFya4dzVSda4+8GCFlGCFw73Xn0gvIqRyi2nmaS5hR59pu6XbngBYXxjYuB+omW3dnic9nxHk3lemKc6tXm1zNTYCkNz369CycG8WonVvK0TIiWQY5yWC13I3WCJa9WdtZccX7QwTM6R4uQ43H/AgMBAAECggEAYVHz7QbTNmFzxvJkv/TigIzbX9fbiIhOu3V4UkwELxuKub2fPVweIIZ07esYp9TsF0UnjZAMW+8sDamZ0m5m9aCNMpL4uNL9S5ytBFbw3rxllCmzGU28TP7vMH2A4nbH0Z+yDm3LW20Yp4QClDvohCdtnRvoPk9fOcnFJqOimiiqdHgwiyk+TR3zguR4V2+rUM4khABTeS+RmKVyvTngQRc4UZm0trVLzPeMIjSq5r5fbNoPEP3v1ZJKWm+ZSz01yHma2DtzBJgXvWgw0xsTmYCz+DjKrF/99OcancCX928eGZGtPeGmy1BMiK5vPGbdcg+m6Spss/yoqKHDjc/uwQKBgQDXWUPTcow+PWcVvBaWL38kWfiaHSLyMUmzKvcCe1EhJDcQpBvJYKw5CgFBPpQKOaxFSBp9Dwkh+rvOXmP0tGL11GmcUyFONJkFDop7FiLcKs6Fx2toVU1cAjIJ6CqT5vq13DPeRAFWViGzSpZoDcJX5GnKeYWcVPDlF2KR2h0lcQKBgQDQFLxxb8JtOfmNX208W7zACpBLHnMXgKn172F0mHQZmyA2GkdUQf11ez7bVJHHb/dC6ZHYa2sRztGxE/wbZiSlC3uYFEmZt5SySKeNhN7xw2w4hXHqSkyyRXTVT+7/Vkz+zY85ZPX7Sh/pd5slhMHPLxMuFNmpP/zmPwlBJ1CWbwKBgQCpW+2InJyI0leA82Q8BuyR1SQ7Z9C08mhIvPB4Bi2ex9F6h+XGcP3g+epUundIt2SxM+yJD8sZ6wvKV9d6emcdeEj6hTI7RAhXvsDP3m/aANxcv6HL8tIdGyjpO6pImS3w2lX8ZjU7BhAI5g71lGSUJPHCJ+IZOYAeW07M7+FeMQKBgCWA10YKwpw1KslUPbf3QNnMDZ28azn9MqTk9Eezgplq4C9gJSMGkcwu3nFhmLS9dW9V8bd0BOQ8xoaH95RooIouu6P5ZBqUf/RyK+DR5ezlMAgv5Qw5QAGRizE3KvhScaYrHnlVvRABmCbYK9pjRxs0fNx9XY2nvskw61YW4+t5AoGBAJvvMjm13Hs6Uf3+ttK+RiRVfoxvYPN54XQDFoPmtss1KW8QuwN/2GZtdlFRruemkyP59ol69CtJOfpGCt1EZpR3rIF+A1aAdM9WLfYDMofL8TaVHqrF4BSu9KmrqIqAljstrrOGWQlsGgyXeV0TuVRpEqzUH5BpPd84QzMZGud6';
        $aop->alipayrsaPublicKey= 'MIIBIjANBgkqhki8AMIIBCgKCAQEAo6bdcx6OY10C+JTqi+6XXz9+6dcXIJktyAf5MkmauO0Li+V3/4WFKLQVJX22oZn0y1xkcWjfOdjcxxM02xBV2XOCejU0xokTW9VbEyIbGENX7zfSsOHc7nmmdZ4j/es531589c2jI2CERHtiKIxVx3Dgv4BGJXxDg8XZ+wvjUQVNO4xpjo7zT1MgNaWQS6isablPRLe/fFEuzna0KQfirssg7SNx7bFTKvtnFQL6P6Pr42ybd3TR3OWgTtgwDaPeN3D9eq/lsAnQGNUY/W4g+pKv7nlK7iL0on+3VG4ontWQA8pS0Efag1Cs7Sensvaoba3Ixe9ic1XMP7UewFUDYwIDAQAB';

        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';

        $request = new \AlipayFundTransToaccountTransferRequest ();
        $account=$data['account'];
        $amount=$data['paymoney'];
        $show_name='';
        $realname=$data['user_name'];
        $data=array(
            "out_biz_no"=>$data['id'].rand(1,99999),//商户转账唯一订单号。发起转账来源方定义的转账单据ID，用于将转账回执通知给来源方。
            "payee_type"=>'ALIPAY_LOGONID',//支付宝登录号，支持邮箱和手机号格式。
            "payee_account"=>$account,//收款方账户。与payee_type配合使用。付款方和收款方不能是同一个账户。
            "amount"=>$amount,//只支持2位小数，小数点前最大支持13位，金额必须大于等于0.1元。 单位元，微信是分，
            "payer_show_name"=>$show_name,//	付款方姓名，可填可不填
            "payee_real_name"=>$realname,//收款方真实姓名，可填可不填，建议填写
            "remark"=>'提现打款',//转账备注
        );
        ;
        $json=json_encode($data);
        $request->setBizContent($json);
        $result = $aop->execute ( $request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";

        $resultCode = $result->$responseNode->code;

        if(!empty($resultCode)&&$resultCode == 10000){
            $msg['code']=1;
        } else {
            $msg['code']=0;
            $msg['message']=$result->alipay_fund_trans_toaccount_transfer_response->sub_msg;
        }
        return $msg;
    }
}
