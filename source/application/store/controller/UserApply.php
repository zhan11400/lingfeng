<?php

namespace app\store\controller;



/**
 * 用户管理
 * Class User
 * @package app\store\controller
 */
class UserApply extends Controller
{
    /**
     * 微商城入驻
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new \app\store\model\UserApply();
        $list = $model->getList('微商城');
        return $this->fetch('index', compact('list'));
    }
    /**
     * 店铺入驻
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function shop_apply()
    {
        $model = new \app\store\model\UserApply();
        $list = $model->getList('店铺申请');
        return $this->fetch('index', compact('list'));
    }
    /**
     * 广告入驻
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function ad_apply()
    {
        $model = new \app\store\model\UserApply();
        $list = $model->getList('广告');
        return $this->fetch('index', compact('list'));
    }
}
