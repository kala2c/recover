<?php


namespace app\web\controller;


use app\common\model\IndexPage as IndexPageModel;
use think\Controller;

class Index extends Controller
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

}