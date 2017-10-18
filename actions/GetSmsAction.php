<?php
/**
 * 获取短信验证码
 * Created by PhpStorm.
 * User: hacklog
 * Date: 12/18/16
 * Time: 5:11 PM
 */

namespace ihacklog\sms\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidParamException;
use ihacklog\sms\models\Sms;
use ihacklog\sms\template\TemplateFactory;
//登录短信验证码
use ihacklog\sms\template\alidayu\verify\Login;
//重置支付密码验证码
use ihacklog\sms\template\alidayu\verify\ResetPayPwd;
//重置登录密码验证码
use ihacklog\sms\template\alidayu\verify\ResetLoginPwd;
//企业入驻短信验证码
use ihacklog\sms\template\alidayu\verify\CompanySettleIn;
//更换手机号码 验证原手机号码
use ihacklog\sms\template\alidayu\verify\ChangeMobilePhoneStepOne;
//更换手机号码 验证新手机号码
use ihacklog\sms\template\alidayu\verify\ChangeMobilePhoneStepTwo;
//公共验证码
use ihacklog\sms\template\alidayu\verify\General;

class GetSmsAction extends Action
{
    const ST_CODE_SUCC = 1;
    const ST_CODE_FAIL = 0;

    /**
     * @var string 手机号码
     */
    public $mobile;

    /**
     * @var 短信业务类型
     * 业务类型//(0注册会员, 1密码找回, 2修改密码, 3修改手机, 4发送新手机验证码, 5提现)
     */
    public $code_type;

    /**
     * @var int 通道类型//（1验证码通道，2 通知类短信通道）
     */
    public $channel_type = Sms::CHANNEL_TYPE_VERIFY;

    public $initCallback;

    /**
     * @var \Closure
     */
    public $beforeCallback;


    /**
     * @var \Closure
     */
    public $afterCallback;

    public $error = null;

    public $data = null;

    public $template = null;

    public $templateType = null;

    protected function formatResponse($status, $message = '', $url = '', $data = [])
    {
        return ['status' => $status, 'message' => $message, 'url' => $url, 'data' => $data];
    }

    public function run()
    {
        if ($this->initCallback && ($this->initCallback instanceof \Closure || is_callable($this->initCallback))) {
            call_user_func_array($this->initCallback, [$this]);
        }
        if (is_null($this->template)) {
            throw new \ErrorException('template can not be null!');
        }
        if (is_string($this->template)) {
            //full namespace
            if (strpos($this->template, '\\') > 0) {
                $this->template = Yii::createObject($this->template);
            } else {
                //just name and type
                $this->template = (new TemplateFactory())
                    ->setTplType($this->templateType)
                    ->setTplName($this->template)
                    ->getTemplate();
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $sms = new Sms();
        if (empty($this->mobile)) {
            return $this->formatResponse(self::ST_CODE_FAIL, '手机号错误', '', $this->data);
        }
        $beforeCbSucc = true;
        if ($this->beforeCallback && ($this->beforeCallback instanceof \Closure || is_callable($this->beforeCallback))) {
            $beforeCbSucc = call_user_func_array($this->beforeCallback, [$this,]);
            if (!$beforeCbSucc) {
                $error = is_null($this->error) ? 'beforeCallback fail' : $this->error;
                return $this->formatResponse(self::ST_CODE_FAIL, $error, '', $this->data);
            }
        }
        if ($this->channel_type == Sms::CHANNEL_TYPE_VERIFY) {
            //@TODO 根据不同的业务类型，自动获取相应服务商不同的短信模板id
            //fixup General 验证码模板有两个参数
            if ($this->template->varNum == 2) {
                $sendRs = $sms->sendVerify($this->mobile, $this->template, mt_rand(1000, 9999), 5);
            } else {
                $sendRs = $sms->sendVerify($this->mobile, $this->template, mt_rand(1000, 9999));
            }
            $errors = $sms->getFirstErrors();
            $this->error = !empty($errors) ? current($errors) : '';
        } else {
            return $this->formatResponse(self::ST_CODE_FAIL, '当前只支持验证类短信发送', '', $this->data);
        }
        $afterCallbackRet = null;
        if ($this->afterCallback && ($this->afterCallback instanceof \Closure || is_callable($this->afterCallback))) {
            $afterCallbackRet = call_user_func_array($this->afterCallback, [$this, $sendRs]);
        }
        if ($afterCallbackRet) {
            return $afterCallbackRet;
        }
        $statusRet = $sendRs ? self::ST_CODE_SUCC : self::ST_CODE_FAIL;
        return $this->formatResponse($statusRet, $sms->getFirstError('id'), '', ['resendTimeSpan' => Yii::$app->getModule('sms')->resendTimeSpan ]);
    }
}