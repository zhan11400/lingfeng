<?php
namespace app\merchant\controller\plugins;

use app\common\model\ShopDynamic;
use app\merchant\controller\Controller;

class Dynamic extends Controller
{
    public function index()
    {

        $model=new ShopDynamic();
        $list=$model->getDynamicList($this->shop_id);
        return $this->fetch('index',compact('list'));
    }
    /**
     * 删除动态
     * @param $id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = ShopDynamic::get($id);
        if (!$model->remove()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    /**
     * 发布动态
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    /**
     * 添加商品
     * @return array|mixed
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('add');
        }
        $model = new ShopDynamic;
        $data=$this->postData('data');
        if(isset($data['images'])){
            $data['images']=serialize($data['images']);
        }else{
            $data['images']=serialize(array());
        }
        if ($model->add($data)) {
            return $this->renderSuccess('添加成功', url('plugins.dynamic/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }
}