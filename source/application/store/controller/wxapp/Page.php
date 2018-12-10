<?php

namespace app\store\controller\wxapp;

use app\common\model\Banner;
use app\store\controller\Controller;
use app\store\model\WxappPage as WxappPageModel;

/**
 * 小程序页面管理
 * Class Page
 * @package app\store\controller\wxapp
 */
class Page extends Controller
{
    /**
     * 首页设计
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function home()
    {
        $model = WxappPageModel::detail();
        if (!$this->request->isAjax()) {
            $jsonData = $model['page_data']['json'];
            return $this->fetch('home', compact('jsonData'));
        }
        $data = $this->postData('data');
        if (!$model->edit($data)) {
            return $this->renderError('更新失败');
        }
        return $this->renderSuccess('更新成功');
    }

    /**
     * 页面链接
     * @return mixed
     */
    public function links()
    {
        return $this->fetch('links');
    }
    /**
     * 页面广告图
     * @return mixed
     */
    public function ad()
    {
        $model=new Banner();
        $where['type']=0;
        $list=$model->getList($where);
        return $this->fetch('ad',compact("list"));
    }
    public function add()
    {    $model=new Banner();
        if(request()->isPost()){
            $data=input("ad/a");
            $data['type']=0;//首页广告
            if($model->add($data)){
                return $this->renderSuccess('操作成功');
            }
            $error = $model->getError() ?: '操作失败';
            return $this->renderError($error);
        }

        $id=input("id");
        $info=$model->getDetail($id);
        echo '<pre>';
        var_dump($info);exit;
        return $this->fetch('add',compact("info"));
    }
}
