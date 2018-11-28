<?php

namespace app\store\model;

use app\common\model\PtGoodsSpec as PtGoodsSpecModel;

/**
 * 商品规格模型
 * Class GoodsSpec
 * @package app\store\model
 */
class PtGoodsSpec extends PtGoodsSpecModel
{
    /**
     * 批量添加商品sku记录
     * @param $goods_id
     * @param $spec_list
     * @return array|false
     * @throws \Exception
     */
    public function addSkuList($goods_id, $spec_list)
    {
        $data = [];
        foreach ($spec_list as $item) {
            $data[] = array_merge($item['form'], [
                'spec_sku_id' => $item['spec_sku_id'],
                'pt_goods_id' => $goods_id,
                'wxapp_id' => self::$wxapp_id,
            ]);
        }
        return $this->saveAll($data);
    }

    /**
     * 添加商品规格关系记录
     * @param $goods_id
     * @param $spec_attr
     * @return array|false
     * @throws \Exception
     */
    public function addGoodsSpecRel($goods_id, $spec_attr)
    {
        $data = [];
        array_map(function ($val) use (&$data, $goods_id) {
            array_map(function ($item) use (&$val, &$data, $goods_id) {
                $data[] = [
                    'pt_goods_id' => $goods_id,
                    'spec_id' => $val['group_id'],
                    'spec_value_id' => $item['item_id'],
                    'wxapp_id' => self::$wxapp_id,
                ];
            }, $val['spec_items']);
        }, $spec_attr);
        $model = new PtGoodsSpecRel;
        return $model->saveAll($data);
    }

    /**
     * 移除指定商品的所有sku
     * @param $goods_id
     * @return int
     */
    public function removeAll($goods_id)
    {
        $model = new PtGoodsSpecRel;
        $model->where('pt_goods_id','=', $goods_id)->delete();
        return $this->where('pt_goods_id','=', $goods_id)->delete();
    }

}
