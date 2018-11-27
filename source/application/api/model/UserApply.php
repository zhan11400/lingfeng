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
class UserApply extends Model
{
    public static $wxapp_id;
    protected $name = 'user_apply';
    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    public function is_exist($wxapp_id,$user_id)
    {
      return   $this->where(['wxapp_id'=>$wxapp_id,'user_id'=>$user_id])->value("id");
    }
    public function add($data)
    {
      //  unset($data['token']);
        // 开启事务
        Db::startTrans();
        try {
            $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            Db::rollback();
        }
        return false;
    }
}
