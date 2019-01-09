<?php

namespace app\merchant\controller\goods;

use app\common\model\GoodsComment;
use app\merchant\controller\Controller;


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
        $list = $model->getCommentList($this->shop_id);
        return $this->fetch('index', compact('list'));
    }




}
