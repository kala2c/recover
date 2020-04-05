<?php


namespace app\web\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use think\Controller;
use think\exception\ValidateException;
use think\facade\Validate;
use \app\common\model\Feedback as FeedbackModel;

class Feedback extends Controller
{
    public function create()
    {
        $post = $this->request->post();
        $validate = Validate::make([
           'phone' => 'require',
           'message' => 'require'
        ], [
            'phone.require' => '请填写联系方式',
            'message.require' => '请填写内容'
        ]);

        if (!$validate->check($post)) {
            throw new ValidateException($validate->getError());
        }

        $post = FeedbackModel::addTimeField($post);

        $rlt = FeedbackModel::create($post);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_FEEDBACK_FAILED);
        }
        return successWithMsg('提交成功');
    }
}