<?php

namespace app\api\model;

use app\common\model\PtCategory as CategoryModel;

/**
 * 商品分类模型
 * Class Category
 * @package app\common\model
 */
class PtCategory extends CategoryModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
//        'create_time',
        'update_time'
    ];

    public static function getList() {

    }

}
