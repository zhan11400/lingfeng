<?php

namespace app\api\controller;

use app\api\model\PtCategory as CategoryModel;

/**
 * 商品分类控制器
 * Class Goods
 * @package app\api\controller
 */
class PtCategory extends Controller
{
    /**
     * 全部分类
     * @return array
     */
    public function lists()
    {
        $list = array_values(CategoryModel::getCacheTree());
        return $this->renderSuccess(compact('list'));
    }
}
