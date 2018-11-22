<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/22
 * Time: 14:44
 */
namespace app\store\controller;
use app\store\model\Managers as ManagersModel;
use think\Db;
use think\Request;

class Managers extends Controller
{
    private $model;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->model=new ManagersModel();
    }

    public function index($shop_id)
    {

        $list=$this->model->getList($shop_id);
        return $this->fetch('index',compact('list','shop_id'));
    }
    /**
     * 添加店铺
     * @return array|mixed
     */
    public function add($shop_id)
    {
        if (!$this->request->isPost()) {
            return $this->fetch('add');
        }
        $data=$this->postData('data');
        $data['shop_id']=$shop_id;
        if(!check_mobile($data['mobile'])){
            return $this->renderError('手机号码有误');
        }
        if(!$this->model->existenceMobile($data['mobile'])){
            return $this->renderError('该手机号码已经被注册过了');
        }
        if ($this->model->add($data)) {
            return $this->renderSuccess('操作成功',url('/store/managers/index/shop_id/'.$shop_id));
            //return $model->renderJson('添加成功', url('shop/index'));
        }
        return $this->renderError('操作失败');
    }
    /**
     * 编辑配送模板
     * @param $category_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($shop_id)
    {
        $model = $this->model->get($shop_id);
        if (!$this->request->isPost()) {
            return $this->fetch('edit', compact('model'));
        }
        if ($this->model->edit($this->postData('data'))) {
            return $this->renderSuccess('操作成功',url('/store/managers/index/shop_id/'.$shop_id));
            //return $model->renderJson('添加成功', url('shop/index'));
        }
        return $this->renderError('操作失败');
    }
}