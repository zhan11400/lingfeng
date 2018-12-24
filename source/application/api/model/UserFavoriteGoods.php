<?php

namespace app\api\model;

use app\common\model\BaseModel;
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
class UserFavoriteGoods extends BaseModel
{
    public function is_collected($wxapp_id,$user_id,$goods_id)
    {
        return $this->where(['wxapp_id'=>$wxapp_id,'user_id'=>$user_id,'goods_id'=>$goods_id])->field("id,status")->find();
    }
    public function favourite($data,$id=0)
    {
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


    //收藏列表
    public function getList($user_id,$status=1)
    {

        $ids = $this
            ->where(['user_id'=>$user_id,'status'=>$status])
			->order("id desc")
           ->column("goods_id");
        return  (new Goods())->getCollectList($ids);

    
    }

}
