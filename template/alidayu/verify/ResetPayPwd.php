<?php
/**
 * 重置支付密码验证码
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\alidayu\verify;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class ResetPayPwd extends AlidayuTemplate
{
    public $varNum = 1;
    public $id = 'SMS_104745009';
    public $type = 'verify';
    public $template = '验证码${para1}，您正在重置支付密码，请勿泄露验证码。';
}