<?php

namespace app\api\controller;

use app\api\model\Goods as GoodsModel;
use app\api\model\Cart as CartModel;
use app\api\model\Shop;
use app\api\model\UserFavoriteGoods;
use app\common\model\GoodsComment;

/**
 * 商品控制器
 * Class Goods
 * @package app\api\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($category_id, $search, $sortType, $sortPrice)
    {
        $model = new GoodsModel;
        $list = $model->getApiList(10, $category_id, $search, $sortType, $sortPrice);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取商品详情
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function detail($goods_id)
    {
        $user=$this->getUser();
        // 商品详情
        $detail = GoodsModel::detail($goods_id);
        if (!$detail || $detail['goods_status']['value'] != 10) {
            return $this->renderError('很抱歉，商品信息不存在或已下架');
        }
        // 规格信息
        $specData = $detail['spec_type'] == 20 ? $detail->getManySpecData($detail['spec_rel'], $detail['spec']) : null;
//        $user = $this->getUser();
//        // 购物车商品总数量
//        $cart_total_num = (new CartModel($user['user_id']))->getTotalNum();
        $shop=(object)array();
        if($detail['shop_id']){
            $shop=(new Shop())->detail($detail['shop_id']);
        }
        $is_collect=db('UserFavoriteGoods')->where(['user_id'=>$user->user_id,'goods_id'=>$goods_id])->find();
        if(!$is_collect){
            $detail['is_collect']=0;
        }else{
            $detail['is_collect']=$is_collect['status'];
        }
        return $this->renderSuccess(compact('detail', 'cart_total_num', 'specData','shop'));
    }
    /**
     * 获取商品的评论列表
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function comment($goods_id)
    {
        $user=$this->getUser();
        // 商品详情
        $detail = GoodsModel::detail($goods_id);
        if (!$detail || $detail['goods_status']['value'] != 10) {
            return $this->renderError('很抱歉，商品信息不存在或已下架');
        }
        $sortType=input('sortType');
        $model=new GoodsComment();
        $list=$model->getGoodsCommentList($goods_id,$sortType);
        $pic_count=$model->getGoodsCommentCount($goods_id,'pic');
        $nopic_count=$model->getGoodsCommentCount($goods_id,'nopic');
        return $this->renderSuccess(compact('list','pic_count','nopic_count'));
    }
    /**
     * 收藏商品与取消收藏
     * */
    public function collect($goods_id)
    {
        if(!request()->isPost()){
            return $this->renderSuccess('请求方式有误');
        }
        $user=$this->getUser();
        // 商品详情
        $detail = GoodsModel::detail($goods_id);
        if (!$detail || $detail['goods_status']['value'] != 10) {
            return $this->renderError('很抱歉，商品信息不存在或已下架');
        }
        $UserFavoriteGoods=new UserFavoriteGoods();
        $id_status=$UserFavoriteGoods->is_collected($this->wxapp_id,$user->user_id,$goods_id);
        $data['goods_id']=$goods_id;
        $data['user_id']=$user->user_id;
        $data['wxapp_id']=$this->wxapp_id ;
        if($id_status){
            $id= $id_status->id;
            if( $id_status->status==1){
                $data['status']=0;
                if( $UserFavoriteGoods->favourite($data,$id)){
                    return $this->renderSuccess('取消收藏成功');
                }
            }else{
                $data['status']=1;
                if( $UserFavoriteGoods->favourite($data,$id)){
                    return $this->renderSuccess('收藏成功');
                }
            }
        }else{

            $data['status']=1;
            if( $UserFavoriteGoods->favourite($data)){
                return $this->renderSuccess('收藏成功');
            }
        }
        return $this->renderError('收藏失败');
    }
}
