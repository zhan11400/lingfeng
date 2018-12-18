<?php

namespace app\api\controller;

use app\api\model\UserFavoriteShop;
use app\common\model\Order;
use app\common\model\Shop;
use app\common\model\ShopDynamic;
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

    public function index()
    {
        $model=new UserFavoriteShop();
        $user = $this->getUser();   // 用户信息

        $shop_ids=$model->getCollectShopId($user->user_id);
        $model2=new ShopDynamic();
        $list=$model2->getDynamicList($shop_ids);
      //  var_dump($list);
      return  $this->renderSuccess(compact('list'));
       // return $this->beforeActionList;
    }
}
