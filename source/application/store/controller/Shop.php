<?php
namespace app\store\controller;
use app\store\model\Shop as ShopModel;
use app\store\model\ShopCategory;
class Shop extends Controller
{
    /*
     * 店铺列表
     */
    public function index()
    {
        $model = new ShopModel;
        $list=$model->getList($status = null, $category_id = 0, $search = '', $sortType = 'all', $sortPrice = false);
        $catgory = ShopCategory::getCacheTree();
      //  dump($list);
       return $this->fetch('index',compact('catgory', 'list'));
    }
    /**
     * 添加店铺
     * @return array|mixed
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            // 店铺分类
            $catgory = ShopCategory::getCacheTree();
            return $this->fetch('add', compact('catgory', 'delivery'));
        }
        $model = new ShopModel;
        if ($model->add($this->postData('shop'))) {
            return $this->renderSuccess('添加成功', url('shop/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }

    /**
     * 编辑配送模板
     * @param $category_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($shop_id)
    {
        // 商品详情
        $model = ShopModel::get($shop_id);
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = ShopCategory::getCacheTree();
            $image_ids=unserialize($model->shop_image);
            $where['file_id']=array('in',$image_ids);
            $files= db("upload_file")->where($where)->column("file_id,file_name");
            var_dump($files);
           foreach($files as $k=> $file_name){
               $images[$k]['file_path'] =IMG_PATH.$file_name;
               $images[$k]['image_id'] =$k;
           }
            $model->image =$images;
            return $this->fetch('edit', compact('model', 'catgory'));
        }
        // 更新记录
        if ($model->edit($this->postData('goods'))) {
            return $this->renderSuccess('更新成功', url('shop/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }
}
