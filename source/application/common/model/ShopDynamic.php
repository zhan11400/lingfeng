<?php

namespace app\common\model;

use think\Cache;
use think\Db;
use think\Request;

/**
 * 商家动态
 * Class Region
 * @package app\common\model
 */
class ShopDynamic extends BaseModel
{
  //  protected $name = 'region';
 //   protected $createTime = false;
    //protected $updateTime = false;
    public function shop()
    {
        return $this->belongsTo('shop',"shop_id","shop_id");
    }
    public function getDynamicList($shop_id=0)
    {
        //echo $shop_id;
        $where=[];
        if(is_array($shop_id)){
            $where['shop_id']=array('in',$shop_id);
        }elseif($shop_id>0){
            $where['shop_id']=$shop_id;
        }
        return  $this
            ->where($where)
            ->with(['shop'])
            ->order('create_time desc')
            ->paginate(10, false, [
                'query' => Request::instance()->request()
            ])
            ->each(function($item,$key){
                 $image_ids=unserialize($item['images']);
                $where['file_id']=array('in',$image_ids);
                $files= db("upload_file")->where($where)->cache(CACHE_TIME)->column("file_id,file_name");
                $images=[];
                foreach($files as $k=> $file_name){
                    $images[$k]['file_path'] =IMG_PATH.$file_name;
                    $images[$k]['image_id'] =$k;
                }
                $item['images']=array_merge($images);
                return $item;
            });
    }


    /**
     * 添加商品
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['wxapp_id'] = self::$wxapp_id;
         $data['shop_id']=session('merchant_store')['shop_id'];

        // 开启事务
        Db::startTrans();
        try {
            // 发布动态
            $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
    /**
     * 删除动态
     * @return bool
     */
    public function remove()
    {
        // 开启事务处理
        Db::startTrans();
        try {
            // 删除当前商品
            $this->delete();
            // 事务提交
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
            return false;
        }
    }
}
