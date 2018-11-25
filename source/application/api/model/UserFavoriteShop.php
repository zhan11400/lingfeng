<?php

namespace app\api\model;

use think\Cache;
use think\Db;
use think\Log;
use think\Model;
use think\Request;

/**
 * 店铺分类模型
 * Class Category
 * @package app\store\model
 */
class UserFavoriteShop extends Model
{
    public static $wxapp_id;
//    protected $name = 'shop';
    protected $fk = 'shop_id';
    public function is_collected($wxapp_id,$user_id,$shop_id)
    {
        return $this->where(['wxapp_id'=>$wxapp_id,'user_id'=>$user_id,'shop_id'=>$shop_id])->field("id,status")->find();
    }
    public function favourite($data,$id=0)
    {
        unset($data['token']);

        // 开启事务
        Db::startTrans();
        try {
            if($id){
                 $this->save($data,['id' => $id]);
            }
            $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

  /*  public function shoplists()
    {
       return $this->hasMany("Shop",'shop_id','shop_id');
    }*/

    public function shop()
    {
        return $this->belongsTo('Shop')->field("shop_id,shop_name");
    }
    //收藏列表
    public function getList($user_id,$status=1)
    {
        $list = $this
            ->with(['shop'])
            ->field(['id','create_time','shop_id'])
            ->where(['user_id'=>$user_id,'status'=>$status])
           ->paginate(10);
        return $list;
    }
}
