<?php


namespace app\web\controller;


use app\web\util\Wx;
use think\Controller;

class Test extends Controller
{
    public function index()
    {
        dump(1);
        Wx::test();
    }
}