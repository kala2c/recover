<?php


namespace app\index\controller;


use think\Controller;

class Test extends Controller
{
    public function test() {
        return success(array_merge($this->request->get(), $this->request->post()));
    }
}