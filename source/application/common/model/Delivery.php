<?php

namespace app\common\model;

use think\Request;

/**
 * 配送模板模型
 * Class Delivery
 * @package app\common\model
 */
class Delivery extends BaseModel
{
    protected $name = 'delivery';

    /**
     * 关联配送模板区域及运费
     * @return \think\model\relation\HasMany
     */
    public function rule()
    {
        return $this->hasMany('DeliveryRule');
    }
    /**
     * 计费方式
     * @param $value
     * @return mixed
     */
    public function getMethodAttr($value)
    {
        $method = [10 => '按件数', 20 => '按重量'];
        return ['text' => $method[$value], 'value' => $value];
    }

    /**
     * 获取全部
     * @return mixed
     */
    public static function getAll()
    {
        $model = new static;
        $data=[];
        if(session('merchant_store')){
            $data['shop_id']=session('merchant_store')['shop_id'];
        }
        return $model->where($data)->order(['sort' => 'asc'])->select();
    }

    /**
     * 获取列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        $data=[];
        $data['shop_id']=0;
        if(session('merchant_store')){
            $data['shop_id']=session('merchant_store')['shop_id'];
        }
        return $this->with(['rule'])
            ->where($data)
            ->order(['sort' => 'asc'])
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 运费模板详情
     * @param $delivery_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($delivery_id)
    {
        return self::get($delivery_id, ['rule']);
    }

}
