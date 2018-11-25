<?php

namespace app\common\model;

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
     * 获取商品列表
     * @param int $status
     * @param int $category_id
     * @param string $search
     * @param string $sortType
     * @param bool $sortPrice
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($status = null, $category_id = 0, $search = '', $sortType = 'all', $pageSize = 10)
    {
        // 筛选条件
        $filter = [];
        $category_id > 0 && $filter['shop_cate_id'] = $category_id;
        $status > 0 && $filter['shop_status'] = $status;
        !empty($search) && $filter['shop_name'] = ['like', '%' . trim($search) . '%'];
        $filter['is_delete']=0;
        // 排序规则
        $sort = [];
        if ($sortType === 'all') {
            $sort = ['shop_sort', 'shop_id' => 'desc'];
        } elseif ($sortType === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($sortType === 'new') {
            $sort = ['shop_id' => 'desc'];
         //  $sort = $sortPrice ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        }
        $db_add=[
            'file'=>db("upload_file"),
            'goods'=>db("goods"),
        ];
      $list= $this->alias("s")->join("shop_category sc","sc.category_id=s.shop_cate_id","LEFT")
          ->field("s.*,sc.name")
          ->cache(CACHE_TIME)
          ->where($filter)->order($sort)
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
}
