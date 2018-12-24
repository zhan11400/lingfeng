<?php

namespace app\api\model;

use app\common\model\BaseModel;
use think\Cache;
use think\Model;

/**
 * 商品分类模型
 * Class Category
 * @package app\store\model
 */
class ShopCategory extends BaseModel
{
   // protected $name = 'shop_category';
    public function image()
    {
        return $this->belongsTo('UploadFile','image_id','file_id');
    }
    /**
     * 获取所有分类
     */
    public  function getCategory()
    {
        $list=$this->where(['parent_id'=>0])
            ->with("image")
            ->select();
        return $list;
    }


}
