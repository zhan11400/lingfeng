<?php

namespace app\common\model;

use think\Request;

/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class Finance extends BaseModel
{
    protected $name = 'shop_money_log';

    public function getList($shop_id)
    {
        return $this->where(['shop_id'=>$shop_id])
           // ->with(['shop'])
            ->order('create_time desc')
            ->paginate(10, false, [
                'query' => Request::instance()->request()
            ])
            ->each(function($item,$key){
                if($item['type']==0){
                    $item['type']='用户收货';
                }
                if($item['type']==1){
                    $item['type']='店铺提现';
                }
                if($item['type']==2){
                    $item['type']='服务费';
                }
                return $item;
            });
    }
    public function getShopMoney($shop_id)
    {
        return  db("shop")->where(['shop_id'=>$shop_id])->value("money");
    }




}
