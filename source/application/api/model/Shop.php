<?php

namespace app\api\model;

use think\Cache;
use think\Db;
use think\Model;
use think\Request;

/**
 * 店铺分类模型
 * Class Category
 * @package app\store\model
 */
class Shop extends Model
{
    public static $wxapp_id=10001;
    protected $name = 'shop';
    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }
    /**
     * 获取商品列表
     * @param int $status
     * @param int $category_id
     * @param string $search
     * @param string $sortType
     * @param bool $sortPrice
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */


    public function detail($shop_id)
    {
        return $this->where(['shop_status'=>10,'is_delete'=>0,'shop_id'=>$shop_id])->field("shop_id")->find();
    }
}
