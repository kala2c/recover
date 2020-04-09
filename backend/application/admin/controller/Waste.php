<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use app\common\exception\ApiException;
use app\common\error\ErrorCode;
use app\common\model\Waste as WasteModel;
use app\common\BaseController;

class Waste extends BaseController
{


    public function WasteList()
    {
        //用于模糊查询
        $keyword = Request::param('query') . '%';
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取所有的废品信息(带模糊查询)
        $query = WasteModel::pageUtil($pagenum, [['name', 'like', $keyword]], $pagesize)->select();
        //获取废品种类的数量
        $count = WasteModel::pageInfo()['total'];
        return success([
            'wastelist' => $query,
            'total' => $count
        ]);
    }

    public function WasteInfo()
    {
        if (Request::isGet()) {

            $id = Request::param('id');

            $waste = WasteModel::get($id);

            return success([
                'wasteinfo' => $waste,
            ]);
        }
        if (Request::isPut()) {
            $id = Request::param('id');
            $name = Request::param('name');
            $price = Request::param('price');
            $unit = Request::param('unit');
            $waste = WasteModel::get($id);
            $waste->name = $name;
            $waste->price = $price;
            $waste->unit = $unit;
            $waste->save();
            $waste = WasteModel::get($id);
            return success([
                'wasteinfo' => $waste,
            ]);
        }
        if (Request::isDelete()) {
        }


    }
}