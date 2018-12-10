<?php

namespace app\common\model;

use think\Cache;
use think\Db;

/**
 * 商品分类模型
 * Class Category
 * @package app\common\model
 */
class Banner extends BaseModel
{
    public static $wxapp_id;
    protected $name = 'banner';

    public function getList($where=[])
    {
        $where['status']=1;
        return $this->where($where)->order("sort desc")->paginate(20);
    }

    /**
     * 关联图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->belongsTo('uploadFile','image','file_id');
    }
    public function getDetail($id=0)
    {
        $where['status']=1;
        $where['banner_id']=$id;
        return $this->where($where)
            ->with("image")
            ->find();
    }
    /**
     * 添加店铺
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        if (!isset($data['image']) || empty($data['image'])) {
            $this->error = '请上传广告图片';
            return false;
        }
        $data['wxapp_id']=config("wxapp_id");
        // 开启事务
        Db::startTrans();
        try {
            // 添加店铺
            $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
}
