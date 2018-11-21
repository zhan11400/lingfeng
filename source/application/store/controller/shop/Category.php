<?php

namespace app\store\controller\shop;

use app\store\controller\Controller;
use app\store\model\ShopCategory as CategoryModel;

/**
 * 店铺分类
 * Class Category
 * @package app\store\controller\goods
 */
class Category extends Controller
{
    /**
     * 店铺分类列表
     * @return mixed
     */
    public function index()
    {
        $model = new CategoryModel;
        $list = $model->getCacheTree();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 删除店铺分类
     * @param $category_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($category_id)
    {
        $model = CategoryModel::get($category_id);
        if (!$model->remove($category_id)) {
            $error = $model->getError() ?: '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 添加店铺分类
     * @return array|mixed
     */
    public function add()
    {
        $model = new CategoryModel;
        if (!$this->request->isAjax()) {
            // 获取所有地区
            $list = $model->getCacheTree();
            return $this->fetch('add', compact('list'));
        }
        // 新增记录
        if ($model->add($this->postData('category'))) {
            return $this->renderSuccess('添加成功', url('shop.category/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }

    /**
     * 编辑
     * @param $category_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($category_id)
    {
        // 模板详情
        $model = CategoryModel::get($category_id);
        if (!$this->request->isAjax()) {
            // 获取所有地区
            $list = $model->getCacheTree();
            $file_name= db("upload_file")->where(['file_id'=>$model->image_id])->value("file_name");
            $model->image=IMG_PATH.$file_name;
            return $this->fetch('edit', compact('model', 'list'));
        }
        // 更新记录
        if ($model->edit($this->postData('category'))) {
            return $this->renderSuccess('更新成功', url('shop.category/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }

}
