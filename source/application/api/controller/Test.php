<?php

namespace app\api\controller;

use app\common\model\Order;
use app\common\model\Shop;
use think\Cache;

set_time_limit(0);
/**
 * 首页控制器
 * Class Index
 * @package app\api\controller
 */
class Test extends Controller
{
    public function test(){
        $model=new Order();
        $shopmodel=new Shop();
        $shoplist=$shopmodel->getList(null,0,'','all',100000);
       foreach($shoplist as $v){
        if(!$model->checkLastMonthOrderLog($v->shop_id)) {
            //获得上个月店铺的销售额
            $money = $model->getLastMonthOrderSaleMoney($v->shop_id);
            //获得店铺的服务费
            $serve_fee = $model->getLastMonthOrderServiceMoney($money);
            shop_money_log($v->shop_id, $money, '清算' . date("Y") . '-' . str_pad(date("m") - 1, 2, '0', STR_PAD_LEFT) . '销售额', 0);
            shop_money_log($v->shop_id, -$serve_fee, '缴纳' . date("Y") . '-' . str_pad(date("m") - 1, 2, '0', STR_PAD_LEFT) . '服务费', 2);
        }
       }
    }
}
