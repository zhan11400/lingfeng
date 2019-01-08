<?php

namespace app\common\model;

use think\Cache;
use think\Db;

/**
 * 商品分类模型
 * Class Category
 * @package app\common\model
 */
class Article extends BaseModel
{
    protected $name = 'article';

    public function getList($where=[])
    {
        return $this->where($where)
            ->paginate(20);
    }

    public function getDetail($id=0)
    {
       // $where['status']=1;
        $where['article_id']=$id;
        return $this->where($where)
            ->find();
    }
    /**
     * 修改文章
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $data['wxapp_id']=self::$wxapp_id;
        // 开启事务
        Db::startTrans();
        try {
            if(isset($data['article_id']) && !empty($data['article_id'])){
                $this->where(['article_id'=>$data['article_id']])->update($data);
            }else {
                // 添加店铺
                $this->allowField(true)->save($data);
            }
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
}
