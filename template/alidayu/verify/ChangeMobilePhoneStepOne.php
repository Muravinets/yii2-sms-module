<?php
/**
 * 更换手机号码 验证原手机号码
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\alidayu\verify;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class ChangeMobilePhoneStepOne extends AlidayuTemplate
{
    public $varNum = 1;
    public $id = 'SMS_104575008';
    public $type = 'verify';
    public $template = '验证码${para1}，您正在验证原手机号码，请勿泄露验证码。';
}