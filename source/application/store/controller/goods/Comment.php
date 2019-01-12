<?php

namespace app\store\controller\goods;

use app\common\model\GoodsComment;
use app\store\controller\Controller;


/**
 * 商品评价
 * Class Category
 * @package app\store\controller\goods
 */
class Comment extends Controller
{
    /**
     * 商品评价列表
     * @return mixed
     */
    public function index()
    {
        $model = new GoodsComment();
        $list = $model->getCommentList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 商品评价审核
     * @return mixed
     */
    public function check()
    {
        $id=input('post.id/d');
        $status=input("post.status/d");
        $model = new GoodsComment();
        $info = $model->getDetail($id);
        if($info['status']!='0'){
             return $this->renderError('不能重复审核');
         }
        if($info->updateStatus($status)){
            return $this->renderSuccess('审核成功');
        }
        return $this->renderError('审核失败');
    }
    /**
     * 删除商品
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = new GoodsComment();
        $info = $model->getDetail($id);
        if (!$info->remove()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
