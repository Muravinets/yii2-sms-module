<?php
/**
 * 更换手机号码 验证新手机号码
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\yuntongxun\verify;

use ihacklog\sms\template\yuntongxun\YuntongxunTemplate;

class ChangeMobilePhoneStepTwo extends YuntongxunTemplate
{
    public $varNum = 1;
    public $id = '177557';
    public $type = 'verify';
    public $template = '验证码{1}，您正在更换手机号码，请勿泄露验证码。';
}