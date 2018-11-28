<?php

namespace app\task\model;

use app\common\model\PtOrder as OrderModel;
use think\Db;

/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class PtOrder extends OrderModel
{
    /**
     * 待支付订单详情
     * @param $order_no
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function payDetail($order_no)
    {
        return self::get(['order_no' => $order_no, 'pay_status' => 10], ['goods']);
    }

    /**
     * 更新付款状态
     * @param $transaction_id
     * @return false|int
     * @throws \Exception
     */
    public function updatePayStatus($transaction_id)
    {
        Db::startTrans();
        // 更新商品库存、销量
        $GoodsModel = new PtGoods;
        $GoodsModel->updateStockSales($this['goods']);
        // 更新订单状态
        $this->save([
            'pay_status' => 20,
            'pt_status' => 20,
            'pay_time' => time(),
            'transaction_id' => $transaction_id,
        ]);
        Db::commit();
        return true;
    }

    /**
     * 更新拼团状态
     * @param $parent_id
     * @param $pt_limit_num
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updatePtStatus($parent_id, $pt_limit_num)
    {
        //TODO 开团参团后续处理
        if (!empty($parent_id)) {
            $pt_count = $this->where(['order_no|parent_id' => $parent_id, 'pt_status' => 20])->count();
            if ($pt_count == $pt_limit_num) {
                Db::startTrans();
                //更新成团信息
                (new PtOrder())->save([
                    'pt_status' => 30
                ], ['order_no|parent_id' => $parent_id, 'pt_status' => 20]);
                //关闭其他未支付订单
                (new PtOrder())->save([
                    'order_status' => 20
                ], ['order_no|parent_id' => $parent_id,'pay_status'=>['<>', 20]]);

                Db::commit();
                return true;
            }
        }

        /*$detail=$GoodsModel::detail($this['goods'][0]['pt_goods_id']);
        Log::error($detail);*/
        //开团订单只更新状态

        /* //子订单回调判断成团条件
         Db::startTrans();
         // 更新订单状态
         $this->save([
             'pt_status' => 20,
         ]);
         Db::commit();
         return true;*/
    }

}
