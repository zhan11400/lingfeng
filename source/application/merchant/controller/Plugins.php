<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2018/11/22
 * Time: 1:07
 */

namespace app\merchant\controller;

class Plugins extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}