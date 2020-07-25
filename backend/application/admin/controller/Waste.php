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


    const WASTEKINDNAMELIST = ['','衣服','纸壳','塑料','数码产品','家电产品','金属产品'];
    public function WasteList()
    {
        //GET请求，获取废品列表
        if (Request::isGet()) {
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
        //PUT请求，新增废品
        if (Request::isPut()) {
            $waste = new WasteModel();
            $waste->name = Request::param('name');
            $waste->price = Request::param('price');
            $waste->unit = Request::param('unit');
            $waste->wastekindid = Request::param('wastekindid');
            $waste->wastekindname = self::WASTEKINDNAMELIST[$waste->wastekindid];
            $waste->save();
            return success([
                'wasteinfo' => $waste,
            ]);
        }
        //DELETE请求，删除废品
        if (Request::isDelete()) {
            $waste = WasteModel::get(Request::param('id'));
            $waste->delete();
            return success([
                'wasteinfo' => $waste,
            ]);
        }
    }

    public function WasteInfo()
    {
        //GET请求，根据id获取某个废品的信息
        if (Request::isGet()) {

            $id = Request::param('id');

            $waste = WasteModel::get($id);

            return success([
                'wasteinfo' => $waste,
            ]);
        }
        //PUT请求，修改废品信息
        if (Request::isPut()) {
            $waste = WasteModel::get(Request::param('id'));
            $waste->name = Request::param('name');
            $waste->price = Request::param('price');
            $waste->unit = Request::param('unit');
            $waste->isrecover = Request::param('isrecover');
            $waste->wastekindid = Request::param('wastekindid');
            $waste->wastekindname = self::WASTEKINDNAMELIST[$waste->wastekindid];
            $waste->save();
            return success([
                'wasteinfo' => $waste,
            ]);
        }
    }


}