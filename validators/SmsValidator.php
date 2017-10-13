<?php
/**
 * this validator does not supporting data validation without a model
 * Created by PhpStorm.
 * User: hacklog
 * Date: 12/14/16
 * Time: 10:55 AM
 * 短信验证码校验
 * 调用方法：
 *  use ihacklog\sms\validators\SmsValidator;
 *
 *     public function rules()
        {
        return [
           ...
['sms_verify_code', SmsValidator::className(), 'template' => '', 'mobileNumberAttribute' => 'mobile'],
            ];
        }
 */

namespace ihacklog\sms\validators;

use Yii;
use yii\validators\Validator;
use ihacklog\sms\models\Sms;
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

class SmsValidator extends Validator
{
    public $template = null;

    public $mobileNumberAttribute = 'mobile';

    /**
     * 初始化template
     * @throws \ErrorException
     */
    public function init()
    {
        parent::init();
        if (is_null($this->template)) {
            throw new \ErrorException('template can not be null!');
        }
        if (is_string($this->template)) {
            $this->template = Yii::createObject($this->template);
        }
    }

    /**
     * @param \yii\base\Model $model 要被验证的模型
     * @param string $attribute 对应模型要被验证的属性, 如 sms_verify_code
     */
    public function validateAttribute($model, $attribute)
    {
        $mobileAttr = $this->mobileNumberAttribute;
        if (!$this->validateVeifyCode($model->$mobileAttr, $model->$attribute)) {
            $this->addError($model, $attribute, '手机号码格式错误!');
        }
    }

    /* --------------------- 以下为工具方法 -----------------------/
    /**
     * Validates the SMS Veify Code.
     * This method serves as the inline validation for SMS Veify Code.
     */
    public function validateVeifyCode($mobile, $smsVerifyCode)
    {
        $sms = new Sms();
        if (!$sms->verify($mobile, $this->template, $smsVerifyCode)) {
            return false;
        }
        return true;
    }
}