<?php
namespace app\merchant\controller;
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
        $model=new \app\common\model\Finance();
        $list=$model->getList($this->shop_id);
       $money= $model->getShopMoney($this->shop_id);
        return $this->fetch('index', compact('list','money'));
    }

    public function apply_log()
    {
        $model=new ShopWithdrawals();
         $list= $model->getWithdrawalsList($this->shop_id);
        $model=new \app\common\model\Finance();
        $money= $model->getShopMoney($this->shop_id);
        return $this->fetch('apply_log', compact('list','money'));
    }
    public function balance()
    {
        $model=new \app\common\model\Finance();
        $money= $model->getShopMoney($this->shop_id);
        return $this->fetch('balance', compact('money'));
    }
    public function apply()
    {
        if(request()->isAjax()){
            $money=input("money");
            if (preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money)) {
                if($money<100){
                    return $this->renderError('最低提现金额100');
                }
                $model=new \app\common\model\Finance();
                $balance= $model->getShopMoney($this->shop_id);
                if($balance<$money){
                    return $this->renderError('余额不足');
                }
                $model_shop=new Shop();
                $info=$model_shop->shopDetail($this->shop_id);
                if(!$info['ali_name'] || !$info['ali_account']){
                    return $this->renderError('请先配置提现的支付宝账号');
                }
                $data['user_name']=$info['ali_name'];
                $data['pay_no']=date("YmdHis").rand(1000,9999);
                $data['account']=$info['ali_account'];
                $data['money']=$money;
                $data['status']='0';
                $data['create_time']=time();
                $data['shop_id']=$this->shop_id;
                $model=new ShopWithdrawals();
                $res= $model->addWithdrawalsLog($data);
                shop_money_log($this->shop_id,$money,'余额提现',1);
                return $this->renderSuccess('提现成功，请等待后台审核');
            }else{
                return $this->renderError('金额输入有误');
            }
        }


        return $this->fetch('balance', compact('money'));
    }
}
