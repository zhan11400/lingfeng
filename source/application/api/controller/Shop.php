<?php

namespace app\api\controller;
use app\api\model\ShopCategory;
use app\api\model\UserFavoriteSho;
use app\api\model\UserFavoriteShop;
use app\common\model\Goods;
use app\common\model\Shop as ShopModel;
use app\api\model\Shop as apiShop;

/**
 * 店铺控制器
 * Class Goods
 * @package app\api\controller
 */
class Shop extends Controller
{
  //  private $shop;

  /*  public function __construct()
    {
       parent::__construct();
        $this->shop=new ShopModel;
    }*/
    /**
     * 店铺列表
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($category_id=0, $search='', $sortType='all', $pageSize=5,$is_new=0)
    {
        $model = new ShopModel;
        $list = $model->getList(10, $category_id, $search, $sortType, $pageSize,$is_new);
        return $this->renderSuccess(compact('list'));
    }
    public function cate()
    {
        $model = new ShopCategory();
        $list = $model->getCategory();
        return $this->renderSuccess(compact('list'));
    }
    /**
     * 收藏店铺与取消收藏
     * */
    public function collect()
    {
        if(!request()->isPost()){
            return $this->renderSuccess('请求方式有误');
        }
       $user=$this->getUser();
        $model = new apiShop;
        $data=input();
        if(!$model->detail($data['shop_id'])){
            return $this->renderError('店铺不存在');
        }
        $UserFavoriteShop=new UserFavoriteShop();
        $id_status=$UserFavoriteShop->is_collected($data['wxapp_id'],$user->user_id,$data['shop_id']);
        $data['user_id']=$user->user_id;
        if($id_status){
            $id= $id_status->id;
            if( $id_status->status==1){
                $data['status']=0;
                if( $UserFavoriteShop->favourite($data,$id)){
					db("shop")->where(["shop_id"=>$data['shop_id']])->setDec("collect_num");
                    return $this->renderSuccess('取消收藏成功');
                }
            }else{
                $data['status']=1;
                if( $UserFavoriteShop->favourite($data,$id)){
					db("shop")->where(["shop_id"=>$data['shop_id']])->setInc("collect_num");
                    return $this->renderSuccess('收藏成功');
                }
            }
        }else{
            $data['status']=1;
            if( $UserFavoriteShop->favourite($data)){
				db("shop")->where(["shop_id"=>$data['shop_id']])->setInc("collect_num");
                return $this->renderSuccess('收藏成功');
            }
        }
        return $this->renderError('收藏失败');
    }
    /**
     * 店铺详情
     */
    public function shop_detail($shop_id){
		$user=$this->getUser();   // 用户信息
	    $user_id=$user->user_id;
        $model = new apiShop;
        $shop=$model->detail($shop_id);
        if(!$shop){
            return $this->renderError('店铺不存在');
        }
		$is_collect=db('UserFavoriteShop')->where(['user_id'=>$user_id,'shop_id'=>$shop_id])->find();
		if(!$is_collect){
			$shop->is_collect=0;
		}else{
			$shop->is_collect=$is_collect['status'];
		}
        return $this->renderSuccess(compact('shop'));
    }
    /**
     * 店铺的商品列表
     */
    public function shop_goods($shop_id,$category_id=0,$search=''){
        $model = new apiShop;
        $shop=$model->detail($shop_id);
        if(!$shop){
            return $this->renderError('店铺不存在或者已下架');
        }
        $goods_model=new Goods();
        $shop=$goods_model->getShopGoodsList($status = 10, $shop_id,$category_id, $search );

        return $this->renderSuccess(compact('shop'));
    }

}
