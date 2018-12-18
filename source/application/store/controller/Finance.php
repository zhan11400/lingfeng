<?php
namespace app\store\controller;
use app\common\model\Shop;
use app\common\model\ShopWithdrawals;
use think\Db;

/**
 * 后台首页
 * Class Index
 * @package app\store\controller
 */
class Finance extends Controller
{



    public function index()
    {
        $model=new ShopWithdrawals();
         $list= $model->getWithdrawalsList();
        return $this->fetch('apply_log', compact('list','money'));
    }
    public function pay()
    {
        $model=new ShopWithdrawals();
        $id=input("id");
        $status=input("status");
        if(!in_array($status,['1','-1'])){
            return $this->renderError('状态有误');
        }
        $info= $model->getDetail($id);
        if($info->status!='0'){
            return $this->renderError('不能重复审核');
        }
       if($status=='1'){
           return $this->renderError('支付宝资料还没提供，无法打款');
            $result=$model->alipay($info);
           if($result['code']==0){
               return $this->renderError($result['message']);
           }
           $res=$model->updateStatus($id,$status);
           if($res){
               return $this->renderSuccess('操作成功');
           }
       }else{
            $res=$model->updateStatus($id,$status);
           if($res) {
               shop_money_log($info->shop_id, $info->money, '余额提现失败，返还金额', 3);
               return $this->renderSuccess('操作成功');
           }
       }
        return $this->renderError('操作失败');
    }
}
