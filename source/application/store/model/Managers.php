<?php

namespace app\store\model;

use think\Cache;
use think\Db;
use think\Model;
use think\Request;

/**
 * 管理员分类模型
 * Class Category
 * @package app\store\model
 */
class Managers extends Model
{
    public static $wxapp_id;
    protected $name = 'shop_managers';
    /**
     * 添加管理员
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        if($data['password']){
            $data['salt']=rand(1000,9999);
            $data['password']=md5($data['password'].$data['salt']);
        }
        unset($data['repassword']);
        // 开启事务
        Db::startTrans();
        try {
            // 添加管理员
            $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
    /**
     * 添加管理员
     * @param array $data
     * @return bool
     */
    public function existenceMobile($mobile)
    {
     $res=$this->where(['mobile'=>$mobile])->value("admin_id");
        if($res) return false;
        return true;
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
    public function getList($shop_id)
    {
        // 筛选条件
        $filter = [];
        $filter['shop_id']=$shop_id;

      return $this
          ->where($filter)
          ->order("admin_id desc")
          ->paginate(10, false, [
              'query' => Request::instance()->request()
          ])->each(function($item, $key){
              if($item['status']==1) {
                  $item['status_text'] = config("Able");
              }else{
                  $item['status_text'] =  config("Enable");
              }
              return $item;
          });
    }
    /**
     * 添加管理员
     * @param array $data
     * @return bool
     */
    public function edit(array $data)
    {
        $data['update_time']=time();
        if($data['password']){
            $data['salt']=rand(1000,9999);
            $data['password']=md5($data['password'].$data['salt']);
        }
        unset($data['repassword']);
        // 开启事务
        Db::startTrans();
        try {
            // 添加管理员\
            $this->where(['admin_id'=>$data['admin_id']])->update($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
    /**
     * 删除管理员
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
