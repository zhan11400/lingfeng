<?php

namespace app\api\model;

use app\common\model\PtGoodsImage as GoodsImageModel;

/**
 * 商品图片模型
 * Class GoodsImage
 * @package app\api\model
 */
class PtGoodsImage extends GoodsImageModel
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
