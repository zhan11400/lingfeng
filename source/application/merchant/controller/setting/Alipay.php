<?php

namespace app\merchant\controller\setting;

use app\api\controller\Index;
use app\common\model\Shop;
use app\merchant\controller\Controller;


/**
 * 支付宝设置
 * Class Delivery
 * @package app\store\controller\setting
 */
class Alipay extends Controller
{
    /**
     * 支付宝
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model=new Shop();
        $info=$model->shopDetail($this->shop_id);
        return $this->fetch('index', compact('info'));
    }
    /**
     * 配置支付宝
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function set()
    {
        $data['ali_name']=input("ali_name");
        $data['ali_account']=input("ali_account");
        if(!$data['ali_name']){
            return  $this->renderError('姓名不能为空');
        }
        if(check_mobile($data['ali_account']) ||  check_email($data['ali_account'])){
           $res=db("shop")->where(['shop_id'=>$this->shop_id])->update($data);
            if($res!==false) return  $this->renderSuccess('操作成功');
        }else{
            return  $this->renderError('不是有效的支付宝账号');
        }

        return  $this->renderError('操作失败');
    }
}
