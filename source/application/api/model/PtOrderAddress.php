<?php

namespace app\api\model;

use app\common\model\PtOrderAddress as OrderAddressModel;

/**
 * 订单收货地址模型
 * Class OrderAddress
 * @package app\api\model
 */
class PtOrderAddress extends OrderAddressModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
    ];

}
