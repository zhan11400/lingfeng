<?php

namespace app\api\controller;

use app\api\model\User;
use app\api\model\UserApply;
use app\api\model\WxappPage;
use app\api\model\Goods as GoodsModel;
use app\api\validate\BaseValidate;
use app\common\model\Banner;
use think\Cache;
use think\Validate;

/**
 * 首页控制器
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{
    /**
     * 首页diy数据
     * @return array
     * @throws \think\exception\DbException
     */
    public function page()
    {
        // 页面元素
        $wxappPage = WxappPage::detail();
        $items = $wxappPage['page_data']['array']['items'];
        // 新品推荐
        $model = new GoodsModel;
        $newest = $model->getNewList();
        // 猜您喜欢
        $best = $model->getBestList();
        return $this->renderSuccess(compact('items', 'newest', 'best'));
    }

    public function getTestToken()
    {
        $data['openid']='oVqpZ5F-Kddr-ouoFqjt5XzY-GXk';
        echo (new User())->SetTestToken($data);
    }

    //申请入驻
    public function user_apply()
    {
        if(!request()->isPost()){
            return $this->renderError('请求方式有误');
        }
        $user=$this->getUser();
       $data=input("post.");
        $validate=new BaseValidate();
        if(!$validate->ckeckUserApply($data)){
            return $this->renderError($validate->getError());
        }

       $model=new UserApply();
       if($model->is_exist($data['wxapp_id'],$user->user_id)){
           return $this->renderError('您已经申请过了！');
       }
        $data['user_id']=$user->user_id;
        if(!$model->add($data)){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess('申请成功，请耐心等候工作人员与你联系');
    }
    //首页广告

    public function ad($type=0)
    {
		if($type == 4){
			return config('app_debug');
		}
        $model=new Banner();
        $where['type']=$type;//0首页广告，1微商城轮播，2微商城四宫格
        $where['status']=1;
        $list=$model->getList($where);
        return $this->renderSuccess(compact('list'));
    }
}
