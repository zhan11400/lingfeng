<?php

namespace app\merchant\controller;

use app\store\model\Category;
use app\store\model\Delivery;
use app\store\model\Goods as GoodsModel;

/**
 * 商品管理控制器
 * Class Goods
 * @package app\store\controller
 */
class Goods extends Controller
{

    /**
     * 商品列表(出售中)
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new GoodsModel;
        $list = $model->getList($status = null, $category_id = 0, $search = '', $sortType = 'all', $sortPrice = false);
        return $this->fetch('index', compact('list'));
    }

    /**
     * 添加商品
     * @return array|mixed
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 配送模板
            $platformcatgory= Category::getPlatformALL();
            $delivery = Delivery::getAll();
            return $this->fetch('add', compact('catgory', 'delivery','platformcatgory'));
        }
        $model = new GoodsModel;
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('goods/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }

    /**
     * 删除商品
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($goods_id)
    {
        $model = GoodsModel::get($goods_id);
        if (!$model->remove()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 商品编辑
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($goods_id)
    {
        $where['shop_id']=session('merchant_store')['shop_id'];
        $goods=new GoodsModel();
        $where['goods_id']=$goods_id;
        if(!$goods->where($where)->find()){
            $this->error('非法访问');
        }
        // 商品详情
        $model = GoodsModel::detail($goods_id);

        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();

            $platformcatgory= Category::getPlatformALL();
            // 配送模板
            $delivery = Delivery::getAll();
            // 多规格信息
            $specData = 'null';
            if ($model['spec_type'] == 20)
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['spec']));
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData','platformcatgory'));
        }
        // 更新记录
        if ($model->edit($this->postData('goods'))) {
            return $this->renderSuccess('更新成功', url('goods/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }

}
