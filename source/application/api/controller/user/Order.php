<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\Wxapp as WxappModel;
use app\common\library\wechat\WxPay;
use app\common\model\GoodsComment;
use app\api\model\UploadFile;
use think\Db;

/**
 * 用户订单管理
 * Class Order
 * @package app\api\controller\user
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 我的订单列表
     * @param $dataType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        return $this->renderSuccess(['order' => $order]);
    }

    /**
     * 取消订单
     * @param $order_id
     * @return array
     * @throws \Exception
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 确认收货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        Db::startTrans();
        if ($model->receipt()) {
          /*  if($model->shop_id>0) {
               // db("shop")->where(['shop_id' => $model->shop_id])->setInc(['money' => $model->total_price]);
               // shop_money_log( $model->shop_id,$model->total_price,'订单号'.$model->order_no.'确认收货',0);
            }*/
            Db::commit();
            return $this->renderSuccess();
        }
        Db::rollback();
        return $this->renderError($model->getError());
    }

    /**
     * 立即支付
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function pay($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 判断商品状态、库存
        if (!$order->checkGoodsStatusFromOrder($order['goods'])) {
            return $this->renderError($order->getError());
        }
        // 发起微信支付
        $wxConfig = WxappModel::getWxappCache();
        $WxPay = new WxPay($wxConfig);
        $wxParams = $WxPay->unifiedorder($order['order_no'], $this->user['open_id'], $order['pay_price']);
        return $this->renderSuccess($wxParams);
    }
    /**
     * 订单评论
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function comment()
    {
        $order_id=input("order_id/d");
        $filter= OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
      //  if($filter['pay_status']['value']==20 && $filter['pay_status']['value']==20 && $filter['receipt_status']['value']==20 && $filter['is_comment']==0){

            $model=new GoodsComment();
            Db::startTrans();
            //  var_dump(input());

            if ($model->addComment($this->postData('data'),$filter)) {
                //修改订单是否已评论状态
                $filter->setComment();
                Db::commit();
                return $this->renderSuccess('提交评论成功');
            }
            $error=$model->getError();
            Db::rollback();
     //   }else{
     //       $error='不是待评论状态';
      //  }
        return $this->renderError($error);
    }

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
         // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file
                ->validate(['size'=>15678,'ext'=>'jpg,png,gif'])
                ->move(dirname(ROOT_PATH) . 'web' . DS . 'uploads');
            if($info){
                $fileType= $info->getExtension();
                $fileName= $info->getFilename();
                $fileSize= $info->getSize();
                $uploadFile= $this->addUploadFile(0,$fileName, $fileSize, $fileType);
                // 图片上传成功
                return json(['code' => 1, 'msg' => '图片上传成功', 'data' => $uploadFile]);
            }else{
                return $this->renderError($file->getError());
            }
        }
    }

    /**
     * 添加文件库上传记录
     * @param $group_id
     * @param $fileName
     * @param $fileInfo
     * @param $fileType
     * @return UploadFile
     */
    private function addUploadFile($group_id, $fileName, $fileSize, $fileType)
    {
        // 存储引擎
        $storage ='local';
        // 存储域名
        $fileUrl ='';
        // 添加文件库记录
        $model = new UploadFile();
        $model->add([
            'group_id' => $group_id > 0 ? (int)$group_id : 0,
            'storage' => $storage,
            'file_url' => $fileUrl,
            'file_name' => $fileName,
            'file_size' =>$fileSize,
            'file_type' => 'image',
            'extension' => pathinfo($fileType, PATHINFO_EXTENSION),
            'shop_id'=>0
        ]);
        return $model;
    }
}
