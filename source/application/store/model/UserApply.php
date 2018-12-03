<?php

namespace app\store\model;


use think\Model;
use think\Request;

/**
 * 用户模型
 * Class User
 * @package app\store\model
 */
class UserApply extends Model
{

    public function user()
    {
        return $this->belongsTo("user","user_id","user_id")->field('user_id,nickName');
    }
    public function getList($type)
    {
        return $this->where(['type'=>$type])
            ->with(['user'])
            ->order('create_time desc')
            ->paginate(10, false, [
                'query' => Request::instance()->request()
            ]);
    }
}