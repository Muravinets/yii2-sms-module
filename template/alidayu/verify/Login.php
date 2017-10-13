<?php
/**
 * 登录短信验证码
 * {1}表示4位随机数字验证码，有效期5分钟
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\alidayu\verify;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class Login extends AlidayuTemplate
{
    public $varNum = 1;
    public $id = 'SMS_104825006';
    public $type = 'verify';
    public $template = '验证码${para1}，用于登录账号，请勿泄露验证码。';
}