<?php


namespace app\common\model;


class IndexPage extends Base
{

    const TYPE_BANNER = 1;
    const TYPE_CUSTOM = 2;
    const TYPE_CITY = 3;


    static public function getBannerList()
    {
        return self::where('type', self::TYPE_BANNER)->select();
    }

    static public function getCustomList()
    {
        return self::where('type', self::TYPE_CUSTOM)->select();
    }

    static public function getCityText()
    {
        return self::where('type', self::TYPE_CITY)->find();
    }

}