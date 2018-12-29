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
        return $this->where($where)
            ->with("uploadFile")
            ->order("sort desc")->paginate(20);
    }

    /**
     * 关联图片表
     * @return \think\model\relation\HasMany
     */
    public function uploadFile()
    {
        return $this->belongsTo('uploadFile','image','file_id');
    }
    public function getDetail($id=0)
    {
       // $where['status']=1;
        $where['banner_id']=$id;
        return $this->where($where)
            ->with("uploadFile")
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
            if(isset($data['banner_id']) && !empty($data['banner_id'])){
                $this->where(['banner_id'=>$data['banner_id']])->update($data);
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
