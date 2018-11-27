<?php
namespace app\api\validate;
use think\Request;

class BaseValidate extends \think\Validate
    {
    /**
     * 检查手机号码格式
     * @param $value 手机号码
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {

            return false;
        }
    }

    /**
     * 检查固定电话
     * @param $value
     * @return bool
     */
    protected function check_telephone($value)
    {
        if(preg_match('/^([0-9]{3,4}-)?[0-9]{7,8}$/',$value))
            return true;
        return false;
    }
        public function ckeckUserApply($params)
        {
            $this->rule = [
                'type' => 'require',
                'real_name' => 'require',
                'mobile' => 'require|isMobile',
                'province' => 'require',
                'city' => 'require',
                'area' => 'require',
                'address' => 'require',
            ];
            $this->message = [
                'type.require' => '申请类型必传',
                'mobile.require' => '手机号码必传',
                'mobile.isMobile' => '手机号码格式错误',
                'real_name.require' => '姓名必填',
                'code' => '微信授权失败',
                'province.require' => '省份必传',
                'city.require' => '城市必传',
                'area.require' => '县区必传',
                'address.require' => '详细地址必传',
            ];
            if (!$this->check($params)) {
                 $this->error;
                return false;
            }
            return true;
        }
 }