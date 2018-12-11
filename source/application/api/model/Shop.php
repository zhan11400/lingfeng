<?php

namespace app\api\model;

use think\Cache;
use think\Db;
use think\Model;
use think\Request;

/**
 * 店铺分类模型
 * Class Category
 * @package app\store\model
 */
class Shop extends Model
{
    public static $wxapp_id;
    protected $name = 'shop';
    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }
    /**
     * 获取商品列表
     * @param int $status
     * @param int $category_id
     * @param string $search
     * @param string $sortType
     * @param bool $sortPrice
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */


    public function detail($shop_id)
    {
        $item= $this->where(['shop_status'=>10,'is_delete'=>0,'shop_id'=>$shop_id])->cache(CACHE_TIME)->find();
        if(!$item) return $item;

        if($item['shop_status']==10) {
            $item['shop_status_text'] = config("shop_status_up");
        }else{
            $item['shop_status_text'] = config("shop_status_down");
        }
        $image_ids=unserialize($item['shop_image']);
        $where['file_id']=array('in',$image_ids);
        $files= db("upload_file")->where($where)->cache(CACHE_TIME)->column("file_id,file_name");
        foreach($files as $k=> $file_name){
            $images[$k]['file_path'] =IMG_PATH.$file_name;
            $images[$k]['image_id'] =$k;
        }

        $image_ids=unserialize($item['pictures']);
        $where['file_id']=array('in',$image_ids);
        $files= db("upload_file")->where($where)->cache(CACHE_TIME)->column("file_id,file_name");
        foreach($files as $k=> $file_name){
            $image[$k]['file_path'] =IMG_PATH.$file_name;
            $image[$k]['image_id'] =$k;
        }
        $item['shop_image']=array_merge($images);
        $item['pictures']=array_merge($image);
        $item['shop_logo']= IMG_PATH.db("upload_file")->cache(CACHE_TIME)->where(['file_id'=>$item['shop_logo']])->value("file_name");
        $item['shop_goods_num']= db("goods")->cache(CACHE_TIME)->where(['shop_id'=>$item['shop_id']])->count("goods_id");
        return $item;
    }

    public function goods($shop_id,$cate_id)
    {
        $where['shop_id']=$shop_id;
        if($cate_id>0) {
            $where['plat_category_id'] = $cate_id;
        }
     return     db("goods")->where($where)->order("sort desc")->paginate(10);
    }
}
