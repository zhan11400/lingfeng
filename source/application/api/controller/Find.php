<?php

namespace app\api\controller;

use app\api\model\UserFavoriteShop;
use app\common\model\ShopDynamic;
use think\Cache;


/**
 * 发现控制器
 * Class Index
 * @package app\api\controller
 */
class Find extends Controller
{

    public function index()
    {
        $model=new UserFavoriteShop();
        $user = $this->getUser();   // 用户信息

        $shop_ids=$model->getCollectShopId($user->user_id);
        $model2=new ShopDynamic();
        $list=$model2->getDynamicList($shop_ids);
      return  $this->renderSuccess(compact('list'));

    }
}
