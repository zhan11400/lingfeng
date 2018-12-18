<?php
namespace app\store\controller;
use app\store\model\Shop as ShopModel;
use app\store\model\ShopCategory;
use think\Db;

class Shop extends Controller
{
    /*
     * 店铺列表
     */
    public function index()
    {
        $model = new ShopModel;
        $list=$model->getList($status = null, $category_id = 0, $search = '', $sortType = 'all');
		//var_dump($list);
        $catgory = ShopCategory::getCacheTree();
      //  dump($list);
       return $this->fetch('index',compact('catgory', 'list'));
    }
    /*
   * 新店列表
   */
    public function new_shop()
    {
        $model = new ShopModel;
        $list=$model->getList($status = null, $category_id = 0, $search = '', $sortType = 'all',10,1);
        //var_dump($list);
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
        if (!$this->request->isPost()) {
            // 店铺分类
            $catgory = ShopCategory::getCacheTree();
            return $this->fetch('add', compact('catgory', 'delivery'));
        }
        $model = new ShopModel;
        if ($model->add($this->postData('shop'))) {
			// $this->redirect(base_url().url('shop/index'));
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
            foreach($image_ids as $k=> $v){
                $images[$k]['file_path'] =IMG_PATH.$files[$v];
                $images[$k]['image_id'] =$v;
            }
            $model->image =$images;
            $image_ids=unserialize($model->pictures);
            $where['file_id']=array('in',$image_ids);
            $files= db("upload_file")->where($where)->column("file_id,file_name");
            $images=[];
            foreach($image_ids as $k=> $v){
                $images[$k]['file_path'] =IMG_PATH.$files[$v];
                $images[$k]['image_id'] =$v;
            }
            $model->pictures =$images;
            $model->shop_logo_img=IMG_PATH.db("upload_file")->where(['file_id'=>$model->shop_logo])->value("file_name");
            return $this->fetch('edit', compact('model', 'catgory'));
        }
        // 更新记录
        $model = new ShopModel;

        if ($model->edit($this->postData('shop'))) {
          // $this->redirect(base_url().url('shop/index'));
           return $this->renderSuccess('操作成功', url('shop/index'));
        }
        $error = $model->getError() ?: '操作失败';
        return $this->renderError($error);
    }

    /**
     * 删除商品
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($shop_id)
    {
        $model = ShopModel::get($shop_id);
        if (!$model->remove($shop_id)) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
}
