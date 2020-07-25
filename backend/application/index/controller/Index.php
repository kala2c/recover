<?php
namespace app\index\controller;

use app\common\model\User;
use think\facade\Cache;
use think\Controller;
use think\facade\Request;

class Index extends Controller
{
    public function index()
    {
        $echostr = Request::param('echostr');
        $xml = file_get_contents('php://input');
        $p = xml_parser_create();
        xml_parse_into_struct($p, $xml, $val, $index);
        xml_parser_free($p);
        if (!empty($val) && !empty($index)) {
            $openid = $val[$index["FROMUSERNAME"][0]]["value"];
            $latitude = $val[$index["LATITUDE"][0]]["value"] ?? null;
            $longitude = $val[$index["LONGITUDE"][0]]["value"] ?? null;
            if ($latitude != null && $longitude != null) {
                Cache::set("$openid.location","$latitude,$longitude");
            }
//            $user = User::get(['openid' => $openid]);
//            if ($user) {
//                $user->location = "$latitude,$longitude";
//                $user->save();
//            }
        }

        echo $echostr;
    }
    public function wxCheck()
    {
        $signature = Request::param('signature');
        $timestamp = Request::param('timestamp');
        $nonce = Request::param('nonce');
        $echostr = Request::param('echostr');
        $token = 'recover';
        //校验微信服务器的接入请求
//        $tmpArr = array($token, $timestamp, $nonce);
//        sort($tmpArr, SORT_STRING);
//        $tmpStr = implode( $tmpArr );
//        $tmpStr = sha1( $tmpStr );
//        if ($tmpStr == $signature){
//            return $echostr;
//        }
        return $echostr;
    }
}
