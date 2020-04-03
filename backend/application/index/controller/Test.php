<?php


namespace app\index\controller;


use think\Controller;

class Test extends Controller
{
    public function test() {
        dump($this->request->post());
        return 'test';
    }
}