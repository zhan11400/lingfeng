<?php
namespace app\merchant\controller;

/**
 * 后台首页
 * Class Index
 * @package app\store\controller
 */
class Finance extends Controller
{

    public function index()
    {
        $model=new \app\common\model\Finance();
        $list=$model->getList($this->shop_id);
       $money= $model->getShopMoney($this->shop_id);
        return $this->fetch('index', compact('list','money'));
    }

    public function apply_log()
    {
        return $this->fetch('apply_log');
    }


}
