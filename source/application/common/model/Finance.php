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
    /**
     * 关联店铺地址表
     * @return \think\model\relation\HasMany
     */
    public function shop()
    {
        return $this->hasOne('shop',"shop_id","shop_id");
    }
    public function getList($shop_id)
    {
        return $this->where(['shop_id'=>$shop_id])
            ->with(['shop'])
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

    public function getWithdrawalsList($shop_id=0)
    {
        //echo $shop_id;
        $where=[];
        if($shop_id>0){
            $where['shop_id']=$shop_id;
        }
       return  db("shop_withdrawals")
           ->where($where)
            ->with(['shop'])
            ->order('create_time desc')
            ->paginate(10, false, [
                'query' => Request::instance()->request()
            ])
            ->each(function($item,$key){
                if($item['status']=='0'){
                    $item['status_str']='待审核';
                }
                if($item['status']=='1'){
                    $item['status_str']='已打款';
                }
                if($item['status']=='-1'){
                    $item['status_str']='已拒绝';
                }
                return $item;
            });

    }
}
