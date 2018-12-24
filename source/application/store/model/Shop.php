<?php

namespace app\store\model;

use think\Cache;
use think\Db;
use think\Model;
use think\Request;

/**
 * 店铺分类模型
 * Class Category
 * @package app\store\model
 */
class Shop extends \app\common\model\Shop
{
    /**
     * 添加店铺
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        if (!isset($data['images']) || empty($data['images'])) {
            $this->error = '请上传店铺图片';
            return false;
        }
        if (!isset($data['shop_logo']) || empty($data['shop_logo'])) {
            $this->error = '请上传店铺logo图片';
            return false;
        }
		 if (!isset($data['pictures']) || empty($data['pictures'])) {
            $this->error = '请上传环境图片';
            return false;
        }
        if (!isset($data['shop_message']) || empty($data['shop_message'])) {
            $this->error = '请上传商家信息图片';
            return false;
        }
        $data['content'] = isset($data['content']) ? $data['content'] : '';
		$data['pictures']=serialize($data['pictures']);
        $data['shop_message']=serialize($data['shop_message']);
		$data['wxapp_id']= self::$wxapp_id;
        $data['shop_image']=serialize($data['images']);
        unset($data['images']);
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
    /**
     * 添加店铺
     * @param array $data
     * @return bool
     */
    public function edit(array $data)
    {
        if (!isset($data['images']) || empty($data['images'])) {
            $this->error = '请上传店铺图片';
            return false;
        }
        if (!isset($data['shop_logo']) || empty($data['shop_logo'])) {
            $this->error = '请上传店铺logo图片';
            return false;
        }
        if (!isset($data['pictures']) || empty($data['pictures'])) {
            $this->error = '请上传环境图片';
            return false;
        }
        if (!isset($data['shop_message']) || empty($data['shop_message'])) {
            $this->error = '请上传商家信息图片';
            return false;
        }
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['shop_image']=serialize($data['images']);
        $data['pictures']=serialize($data['pictures']);
        $data['shop_message']=serialize($data['shop_message']);
        $data['update_time']=time();
        unset($data['images']);

        // 开启事务
        Db::startTrans();
        try {
            if($data['shop_status']==20){
                db("goods")->where(['shop_id'=>$data['shop_id']])->update(['goods_status'=>30]);
            }
            $this->where(['shop_id'=>$data['shop_id']])->update($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
    /**
     * 删除店铺
     * @return bool
     */
    public function remove($shop_id)
    {
        // 开启事务处理
        Db::startTrans();
        try {

            // 删除当前商品
            $this->where(['shop_id'=>$shop_id])->update(['is_delete'=>1]);
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
