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
        return $this->fetch('index', compact('list'));
    }

    public function demolist()
    {
        return $this->fetch('demo-list');
    }


}
