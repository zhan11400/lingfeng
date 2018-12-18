<?php

namespace app\store\controller\plugins;

use app\common\model\Banner;
use app\store\controller\Controller;

/**
 * 微商城
 * Class User
 * @package app\store\controller
 */
class Micromall extends Controller
{
    /**
     * 微商城广告图
     * @return mixed
     */
    public function ad()
    {
        $model=new Banner();
        $where['type']=1;
        $list=$model->getList($where);
        return $this->fetch('ad',compact("list"));
    }
    public function add()
    {    $model=new Banner();
        if(request()->isPost()){
            $data=input("ad/a");
            $data['type']=1;//微商城轮播
            if($model->add($data)){
                return $this->renderSuccess('操作成功',url('plugins.micromall/ad'));
            }
            $error = $model->getError() ?: '操作失败';
            return $this->renderError($error);
        }

        $id=input("id");
        $info=$model->getDetail($id);
        // var_dump($info);
        return $this->fetch('add',compact("info"));
    }
    public function fourPalaceEdit()
    {    $model=new Banner();
        if(request()->isPost()){
            $data=input("ad/a");
            $data['type']=2;//四宫格
            if($model->add($data)){
                return $this->renderSuccess('操作成功',url('plugins.micromall/banner'));
            }
            $error = $model->getError() ?: '操作失败';
            return $this->renderError($error);
        }

        $id=input("id");
        $info=$model->getDetail($id);
        // var_dump($info);
        return $this->fetch('fouredit',compact("info"));
    }
    public function ad_del()
    {
        $model=new Banner();
        $id=input("id");
        $where['banner_id']=$id;
        $res=$model->where($where)->delete();
        if($res){
            return $this->renderSuccess('操作成功');
        }
        $error = $model->getError() ?: '操作失败';
        return $this->renderError($error);
    }
    public function banner()
    {
        $model=new Banner();
        $where['type']=2;
        $list=$model->getList($where);
        return $this->fetch('banner',compact("list"));
    }
    public function edit()
    {    $model=new Banner();
        if(request()->isPost()){
            $data=input("ad/a");
            $data['type']=1;//微商城轮播
            if($model->add($data)){
                return $this->renderSuccess('操作成功',url('plugins.micromall/banner'));
            }
            $error = $model->getError() ?: '操作失败';
            return $this->renderError($error);
        }

        $id=input("id");
        $info=$model->getDetail($id);
        // var_dump($info);
        return $this->fetch('add',compact("info"));
    }




}