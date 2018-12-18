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
        return $this->belongsTo('Shop')->where(['shop_status'=>10,'is_delete'=>0]);
    }
    //收藏列表
    public function getList($user_id,$status=1)
    {
        $ids = $this
            ->where(['user_id'=>$user_id,'status'=>$status])
			->order("id desc")
           ->column("shop_id");
		return   $this->getCollectList($status=10,$ids, $pageSize = 10);
    
    }
	 public function getCollectList($status=10,$ids, $pageSize = 10)
    {
		if(empty($ids)){
			 return [];
		}
        // 筛选条件
        $filter = [];
        $status > 0 && $filter['shop_status'] = $status;
        $filter['is_delete']=0;
		$filter['shop_id']=array('in',$ids);
        // 排序规则

        $db_add=[
            'file'=>db("upload_file"),
            'goods'=>db("goods"),
        ];
      $list= db("shop")->alias("s")->join("shop_category sc","sc.category_id=s.shop_cate_id","LEFT")
          ->field("s.*,sc.name")
          ->where($filter)
		  //->order($)
          ->paginate($pageSize, false, [
              'query' => Request::instance()->request()
          ])->each(function($item, $key) use($db_add){
              if($item['shop_status']==10) {
                  $item['shop_status_text'] = config("shop_status_up");
              }else{
                  $item['shop_status_text'] = config("shop_status_down");
              }
              $image_ids=unserialize($item['shop_image']);
              $where['file_id']=array('in',$image_ids);
              $files= $db_add['file']->where($where)->cache(CACHE_TIME)->column("file_id,file_name");
              foreach($files as $k=> $file_name){
                  $images[$k]['file_path'] =IMG_PATH.$file_name;
                  $images[$k]['image_id'] =$k;
              }
              $item['shop_image']=array_merge($images);
              $item['shop_logo']= IMG_PATH.$db_add['file']->cache(CACHE_TIME)->where(['file_id'=>$item['shop_logo']])->value("file_name");
              $item['shop_goods_num']= $db_add['goods']->cache(CACHE_TIME)->where(['shop_id'=>$item['shop_id']])->count("goods_id");
              return $item;
          });
        return $list;
    }
    public function getCollectShopId($user_id)
    {
        //关注的店铺，并且店铺没被下架，没被删除
        return  $this->alias("c")
            ->join('shop s','s.shop_id = c.shop_id')
            ->where(['user_id'=>$user_id,'status'=>1,'s.shop_status'=>10,'is_delete'=>0])
            ->order("id desc")
            ->column("c.shop_id");
    }
}
