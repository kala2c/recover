<?php


namespace app\admin\controller;
use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\model\IndexPage as IndexPageModel;
use think\exception\ValidateException;
use think\facade\Validate;

class IndexPage extends Base
{
    /**
     * 获取banner列表
     */
    public function getBannerList()
    {
        $bannerList = IndexPageModel::getBannerList();
        foreach ($bannerList as $banner) {
            $banner->url = $this->request->domain() . '/uploads/' . $banner->url;
        }
        return success($bannerList);
    }

    /**
     * 增加banner图
     * @throws ApiException
     */
    public function addBanner()
    {
        $image = $this->request->file('file');

        $info = $image->move('./public/uploads/');

        $url = $info->getSaveName();

        $data = ['url' => $url, 'type' => IndexPageModel::TYPE_BANNER];
        $rlt = IndexPageModel::create($data);
        if (!$rlt) {
            throw new ApiException(ErrorCode::SET_INDEX_PAGE_INFO_FAILED);
        }
        return successWithMsg('添加成功');
    }

    /**
     * 删除banner
     * @throws ApiException
     */
    public function delBanner()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'id' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $id = $data['id'];
        $banner = IndexPageModel::get($id);
        // 不能删除banner以外的
        if ($banner->type != IndexPageModel::TYPE_BANNER) {
            throw new ApiException(ErrorCode::DEL_INDEX_PAGE_INFO_FAILED);
        }
        // 执行删除
        $rlt = IndexPageModel::destroy($id);
        if (!$rlt) {
            throw new ApiException(ErrorCode::DEL_INDEX_PAGE_INFO_FAILED);
        }
        return successWithMsg('删除成功');
    }

    public function setCity()
    {

    }

    public function setCustom()
    {

    }
}