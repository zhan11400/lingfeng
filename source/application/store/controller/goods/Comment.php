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




}
