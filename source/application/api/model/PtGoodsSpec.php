<?php

namespace app\api\model;

use app\common\model\PtGoodsSpec as GoodsSpecModel;

/**
 * 商品规格模型
 * Class GoodsSpec
 * @package app\api\model
 */
class PtGoodsSpec extends GoodsSpecModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

}
