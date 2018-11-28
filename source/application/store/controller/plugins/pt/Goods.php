<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2018/11/22
 * Time: 1:43
 */

namespace app\store\controller\plugins\pt;


use app\store\controller\Controller;
use app\store\model\PtCategory;
use app\store\model\Delivery;
use app\store\model\PtGoods as PtGoodsModel;
use app\store\model\PtGoods;

/**
 * 拼团商品管理页面
 * Class Goods
 * @package app\store\controller\plugins\pt
 */
class Goods extends Controller
{
    /**
     * 拼团商品列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index(){
        $model = new PtGoodsModel;
        $list = $model->getList();
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
            $catgory = PtCategory::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();
            return $this->fetch('add', compact('catgory', 'delivery'));
        }
        $model = new PtGoods();
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('plugins.pt.goods/index'));
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
        $model = PtGoodsModel::get($goods_id);
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
        // 商品详情
        $model = PtGoodsModel::detail($goods_id);
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = PtCategory::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();
            // 多规格信息
            $specData = 'null';
            if ($model['spec_type'] === 20)
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['spec']));
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData'));
        }
        // 更新记录
        if ($model->edit($this->postData('goods'))) {
            return $this->renderSuccess('更新成功', url('plugins.pt.goods/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }
}