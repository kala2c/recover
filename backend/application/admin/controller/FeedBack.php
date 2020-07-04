<?php


namespace app\admin\controller;

use app\common\model\Feedback as FeedBackModel;
use think\exception\DbException;
use think\exception\ValidateException;
use think\response\Json;

class FeedBack extends Base
{
    /**
     * 获取留言列表
     * @return Json
     * @throws DbException
     */
    public function getList()
    {
        $page = $this->request->get('page') ?? 1;
        $map = ['status' => 0];
        $list = FeedBackModel::pageUtil($page, $map, 20)
            ->with(['user'])
            ->order('id', 'desc')
            ->select();
        return success([
            'list' => $list,
            'total' => FeedBackModel::pageInfo()['total']
        ]);
    }

    public function delete()
    {
        $id = $this->request->get('id') ?? null;
        if (empty($id)) {
            throw new ValidateException('参数错误');
        }
        FeedBackModel::update(['status' => 1], ['id' => $id]);
        return successWithMsg('删除成功');
    }
}