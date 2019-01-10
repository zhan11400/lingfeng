<?php

namespace app\common\model;
use think\Request;

/**
 * 商品评论
 * Class GoodsSpec
 * @package app\common\model
 */
class GoodsComment extends BaseModel
{
    protected $name = 'goods_comment';

    /**
     * 提交评论
     * @param $data
     * @param $order
     * @return bool
     * @throws \Exception
     */
    public function addComment($data,$order)
    {
        if(is_array($data)){
            $data=json_decode(htmlspecialchars_decode($data[0]),true);
        }

        $list=[];
        foreach($data as $k=>$v){
            $insert['user_id']=$order['user_id'];
            $insert['shop_id']=$order['shop_id'];
            $insert['content']=$v['content'];
            $insert['goods_id']=$k;
            $insert['des_star']=$v['des_star'];
            $insert['express_star']=$v['express_star'];
            $insert['service_star']=$v['service_star'];
            if(isset($v['images']) && count($v['images'])>0){
                $insert['type']=1;
                $insert['images']=serialize($v['images']);
            }else{
                $insert['type']=0;
                $insert['images']=serialize([]);
            }
            $insert['wxapp_id'] = self::$wxapp_id;
           array_push($list,$insert);
        }
        if(!$this->saveAll($list, false)){
            $this->error = '提交评论失败';
            return false;
        }
        return true;
        //return $this->allowField(true)->save($data);
    }

    /**
     * 商品对应的评论列表
     * @param $goods_id
     * @param string $sortType
     * @return $this
     */
    public function getGoodsCommentList($goods_id,$sortType='all')
    {
        if($sortType=='pic'){
            $where['type']=1;
        }elseif($sortType=='nopic'){
            $where['type']=0;
        }
        $where['goods_id']=$goods_id;
        $where['status']=1;
       $list=  $this->where($where)
           ->with("user")
           ->order("create_time desc")
           ->paginate(5)->each(function($item){
               $image_ids=unserialize($item['images']);
               $where['file_id']=array('in',$image_ids);
               $files= db("upload_file")->where($where)->cache(CACHE_TIME)->column("file_id,file_name");
               $shop_message=[];
               foreach($image_ids as $k=> $v){
                   $shop_message[$k]['file_path'] =IMG_PATH.$files[$v];
                   $shop_message[$k]['image_id'] =$v;
               }
               $item['images']=$shop_message;
               return $item;
           });
        return $list;
    }

    /**
     * 商品列表，后台用的
     * @param int $shop_id
     * @return $this
     */
    public function getCommentList($shop_id=0)
    {
        $where=[];
        if($shop_id>0){
            $where['shop_id']=$shop_id;
            $where['status']=1;
        }
        $list=  $this->where($where)
            ->with(["user","goods"])
            ->order("create_time desc")
            ->paginate(5, false, [
                'query' => Request::instance()->request()
            ])->each(function($item){
                $image_ids=unserialize($item['images']);
                $where['file_id']=array('in',$image_ids);
                $files= db("upload_file")->where($where)->cache(CACHE_TIME)->column("file_id,file_name");
                $shop_message=[];
                foreach($image_ids as $k=> $v){
                    $shop_message[$k]['file_path'] =IMG_PATH.$files[$v];
                    $shop_message[$k]['image_id'] =$v;
                }
                $status = [0 => '待审核', 1 => '已通过', -1 => '不通过'];
                $item['status_str']=$status[$item['status' ]];
                $item['images']=$shop_message;
                return $item;
            });
        return $list;
    }
    public function user()
    {
      return  $this->belongsTo("User",'user_id','user_id');
    }
    public function goods()
    {
        return  $this->belongsTo("Goods",'goods_id','goods_id');
    }

    public function getDetail($id)
    {
        return self::get($id);
    }
    public function updateStatus($status)
    {
        $res=$this->allowField(true)->save(['status'=>$status]);
        return $res;
    }
}
