<?php

namespace app\common\model;

use think\Hook;
use think\Log;

/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class PtOrder extends BaseModel
{
    protected $name = 'pt_order';
    protected $pk = 'pt_order_id';

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('pt_order', $static);
    }

    /**
     * 订单商品列表
     * @return \think\model\relation\HasMany
     */
    public function goods()
    {
        return $this->hasMany('PtOrderGoods');
    }

    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('PtOrderAddress');
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
     * 拼团状态
     * @param $value
     * @return array
     */
    public function getPtStatusAttr($value)
    {
        $status = [10 => '未成团', 20 => '待成团', 30 => '已成团', 40 => '团失效'];
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
        $order = self::get($order_id, ['goods.image', 'address']);

        $pid = empty($order['parent_id']) ? $order['order_no'] : $order['parent_id'];


        if (!$order['group'] = self::where('order_no|parent_id', $pid)->where('pt_status','>',20)->select()) {
            throw new BaseException(['msg' => '无法找到团信息']);
        }
//todo how to use with
        foreach ($order['group'] as $i => $v) {
            $user = (new \app\store\model\User())->where('user_id', $v['user_id'])->field('avatarUrl,nickName,user_id')->find()->getData();
            $order['group'][$i]['avatarUrl'] = $user['avatarUrl'];
            $order['group'][$i]['nickName'] = $user['nickName'];
            $order['group'][$i]['user_id'] = $user['user_id'];

        }
        return $order;
    }

}
