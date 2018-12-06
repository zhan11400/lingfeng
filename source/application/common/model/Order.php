<?php

namespace app\common\model;

use think\Hook;

/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends BaseModel
{
    protected $name = 'order';

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('order', $static);
    }

    /**
     * 订单商品列表
     * @return \think\model\relation\HasMany
     */
    public function goods()
    {
        return $this->hasMany('OrderGoods');
    }

    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderAddress');
    }
    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function address2()
    {
        return $this->hasOne('OrderAddress','order_address_id','parent_id');
    }
    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayStatusAttr($value)
    {
        $status = [10 => '待付款', 20 => '已付款'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 发货状态
     * @param $value
     * @return array
     */
    public function getDeliveryStatusAttr($value)
    {
        $status = [10 => '待发货', 20 => '已发货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getReceiptStatusAttr($value)
    {
        $status = [10 => '待收货', 20 => '已收货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getOrderStatusAttr($value)
    {
        $status = [10 => '进行中', 20 => '取消', 30 => '已完成'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 生成订单号
     */
    protected function orderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 订单详情
     * @param $order_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($order_id)
    {
        return self::get($order_id, ['goods.image', 'address']);
    }
    //获得店铺上个月销售额
    public function getLastMonthOrderSaleMoney($shop_id)
    {
        // 筛选条件
        $filter = [];
        $filter['is_hidden'] =0;
        $filter['pay_status'] = 20;
        $filter['delivery_status'] = 20;
        $filter['receipt_status'] = 10;
        $begin_time = strtotime(date('Y-m-01',strtotime('-1 month')));
        $end_time =strtotime(date("Y-m-d 23:59:59", strtotime(-date('d').'day')));
        return $this ->where('shop_id', '=', $shop_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
             ->whereBetween("receipt_time",[$begin_time,$end_time])
            ->order(['create_time' => 'desc'])
            ->sum("total_price");
    }
    //获得店铺上个月销售额对应的服务费
    public function getLastMonthOrderServiceMoney($money)
    {
       if($money<200){
           return 0;
       }elseif($money>=200 && $money<600){
           return 10;
       }elseif($money>=600 && $money<1000){
           return 20;
       }else{
           return 30;
       }
    }
    //检测是否已经结清上个月
    public function checkLastMonthOrderLog($shop_id)
    {
        $where['shop_id']=$shop_id;
        $where['month']=date("Y-m");
        $where['type']=0;
        return db("shop_money_log")->where($where)->find();

    }
}
