<?php
namespace app\store\controller;
use app\common\model\Shop;
use think\Db;

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
         $list= $model->getWithdrawalsList();
        return $this->fetch('apply_log', compact('list','money'));
    }

}
