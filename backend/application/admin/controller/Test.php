<?php


namespace app\admin\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use think\Controller;

class Test extends Controller
{
    public function test() {
        throw new ApiException(ErrorCode::OK);
    }
    
    public function insertData()
    {
        echo 111;
        $model = new \app\common\model\Area;
        $area = $model->where('id', 54)->find();
        $area->administrator_id = 1;
        $area->save();
        // $model->save([
        //     'level' => 3,
        //     'name' => '莱山区',
        //     'top_id' => 0,
        //     'city_id' => 1,
        //     'status' => 0
        // ]);
        // $list = $model->select();
        // return json($list);
        // $model->add(53, '初家');
    }
}