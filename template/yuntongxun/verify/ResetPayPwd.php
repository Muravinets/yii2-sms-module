<?php
/**
 * 重置支付密码验证码
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\yuntongxun\verify;

use ihacklog\sms\template\yuntongxun\YuntongxunTemplate;

class ResetPayPwd extends YuntongxunTemplate
{
    public $varNum = 1;
    public $id = '177553';
    public $type = 'verify';
    public $template = '验证码{1}，您正在重置支付密码，请勿泄露验证码。';
}