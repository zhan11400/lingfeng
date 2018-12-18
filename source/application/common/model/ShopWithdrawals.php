<?php

namespace app\common\model;

use think\Cache;
use think\Request;

/**
 * 地区模型
 * Class Region
 * @package app\common\model
 */
class ShopWithdrawals extends BaseModel
{
  //  protected $name = 'region';
 //   protected $createTime = false;
    //protected $updateTime = false;
    public function shop()
    {
        return $this->belongsTo('shop',"shop_id","shop_id");
    }
    public function getWithdrawalsList($shop_id=0)
    {
        //echo $shop_id;
        $where=[];
        if($shop_id>0){
            $where['shop_id']=$shop_id;
        }
        return  $this
            ->where($where)
            ->with(['shop'])
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
    public function getDetail($id,$shop_id=0)
    {
        $where=[];
        if($shop_id>0){
            $where['shop_id']=$shop_id;
        }
        $where['id']=$id;
        return  $this
            ->where($where)
            ->with(['shop'])
            ->find();
    }

    public function addWithdrawalsLog($data)
    {
        $data['wxapp_id']=self::$wxapp_id;
        return $this->insert($data);
    }

    public function updateStatus($id,$status)
    {
        $where=[];
        $where['id']=$id;
        return  $this
            ->where($where)
            ->update(['status'=>$status,'create_time'=>time()]);
    }
    public function alipay($data){
        Vendor('Alipay.AopSdk');
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId =''; //C("ALIPAY.APPID");  C("ALIPAY.rsaPrivateKey");//C("ALIPAY.alipayrsaPublicKey");//
        $aop->rsaPrivateKey ='1';
        $aop->alipayrsaPublicKey= '2';

        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';

        $request = new \AlipayFundTransToaccountTransferRequest ();
        $data=array(
            "out_biz_no"=>$data->pay_no,//商户转账唯一订单号。发起转账来源方定义的转账单据ID，用于将转账回执通知给来源方。
            "payee_type"=>'ALIPAY_LOGONID',//支付宝登录号，支持邮箱和手机号格式。
            "payee_account"=>$data->account,//收款方账户。与payee_type配合使用。付款方和收款方不能是同一个账户。
            "amount"=>$data->money,//只支持2位小数，小数点前最大支持13位，金额必须大于等于0.1元。 单位元，微信是分，
            "payer_show_name"=>'平远圈',//	付款方姓名，可填可不填
            "payee_real_name"=>$data->user_name,//收款方真实姓名，可填可不填，建议填写
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
