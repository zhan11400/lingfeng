<?php

namespace app\merchant\model;

use think\Log;
use think\Model;
use think\Session;

/**
 * 商家用户模型
 * Class StoreUser
 * @package app\store\model
 */
class ShopManagers extends Model
{
    /**
     * 商家用户登录
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($data)
    {
        $user = self::useGlobalScope(false)
            ->where([
            'mobile' => $data['user_name'],
        ])->find();
        if (!$user) {
            $this->error = '登录失败, 用户名不存在';
            return false;
        }
        if($user['password']!=md5($data['password'].$user['salt'])){
            $this->error = '登录失败, 密码错误';
            return false;
        }
        // 保存登录状态
        Session::set('merchant_store', [
            'user' => [
                'store_user_id' => $user['admin_id'],
                'user_name' => $user['real_name'],
            ],
            'shop_id' => $user['shop_id'],
            'wxapp_id' => $user['wxapp_id'],
            'is_login' => true,
        ]);
        session("yoshop_store",null);
        return true;
    }

    /**
     * 商户信息
     * @param $store_user_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($store_user_id)
    {
        return self::get($store_user_id);
    }

    /**
     * 更新当前管理员信息
     * @param $data
     * @return bool
     */
    public function renew($data)
    {

        $user = self::useGlobalScope(false)
            ->where([
                'admin_id' => session('merchant_store.user')['store_user_id'],
            ])->find();
        if(md5($data['old_password'].$user['salt'])!=$user['password']){
            $this->error = '原密码不正确';
            return false;
        }
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        $salt=rand(1000,9999);
        $password=md5($data['password'].$salt);
        // 更新管理员信息
        if ($this->save([
                'real_name' => $data['real_name'],
                'salt'=>$salt,
                'password' => $password,
            ]) === false) {
            return false;
        }
        session("merchant_store",null);
        return true;
    }

}
