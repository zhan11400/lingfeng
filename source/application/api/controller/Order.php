<?php

namespace app\api\controller;

use app\api\model\Delivery;
use app\api\model\Order as OrderModel;
use app\api\model\Wxapp as WxappModel;
use app\api\model\Cart as CartModel;
use app\common\library\wechat\WxPay;

/**
 * 订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 订单确认-立即购买
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function buyNow($goods_id, $goods_num, $goods_sku_id)
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getBuyNow($this->user, $goods_id, $goods_num, $goods_sku_id);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        //var_dump($order);exit;
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
                    , $order['order_pay_price']),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

    /**
     * 订单确认-购物车结算
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function cart()
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getCart($this->user);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }




        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            // 清空购物车
          //  $Card = new CartModel($this->user['user_id']);
           // $Card->clearAll();

            //拆单
            $order_id= $model['order_id'];
            $arr1=array();
            foreach ($order['goods_list']  as  $v) {
                $v1=$v['shop_id'];
                unset($v['shop_id']);
                $arr1[$v1][]=$v;
            }
            foreach($arr1 as $k=>$v){
                $allExpressPrice = array_column($v, 'express_price');
                $expressPrice = $allExpressPrice ? Delivery::freightRule($allExpressPrice) : 0.00;
                // 商品总金额
                $orderTotalPrice = array_sum(array_column($v, 'total_price'));
                $data= [
                    // 商品列表
                    'order_total_price' => round($orderTotalPrice, 2),              // 商品总金额 (不含运费)
                    'order_pay_price' => bcadd($orderTotalPrice, $expressPrice, 2),    // 实际支付金额
                    'address' => $this->user['address_default'],  // 默认地址
                    'express_price' => $expressPrice,       // 配送费用
                    'shop_id'=>$k,
                ];
                $model->Increase_sub_orders($this->user['user_id'],$order_id,$data);
            }
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
                    , $order['order_pay_price']),
                'order_id' =>$order_id
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

    /**
     * 构建微信支付
     * @param $order_no
     * @param $open_id
     * @param $pay_price
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function wxPay($order_no, $open_id, $pay_price)
    {
        $wxConfig = WxappModel::getWxappCache();
        $WxPay = new WxPay($wxConfig);
        return $WxPay->unifiedorder($order_no, $open_id, $pay_price);
    }

}
