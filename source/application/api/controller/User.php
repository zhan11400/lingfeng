<?php

namespace app\api\controller;

use app\api\controller\user\Index;
use app\api\model\User as UserModel;
use app\api\model\UserFavoriteShop;

/**
 * 用户管理
 * Class User
 * @package app\api
 */
class User extends Controller
{
    /**
     * 用户自动登录
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function login()
    {
        $model = new UserModel;
        $user_id = $model->login($this->request->post());
        $token = $model->getToken();
        return $this->renderSuccess(compact('user_id', 'token'));
    }

    //我收藏的店铺
    public function myCollectShop()
    {
        if(!request()->isPost()){
            return $this->renderSuccess('请求方式有误');
        }
        $p=input("page");
        $pageSize=input("pageSize",5);
        $user=$this->getUser();
        $UserFavoriteShop=new UserFavoriteShop();
        $list=$UserFavoriteShop->getList($user->user_id);
        return $this->renderSuccess(compact('list'));
    }
}
