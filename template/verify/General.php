<?php
/**
 * 通用短信验证码
 * {1}表示4位随机数字验证码，有效期5分钟
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\verify;

use ihacklog\sms\components\BaseTemplate;

class General extends BaseTemplate
{
    public $varNum = 2;
    public $id = '150294';
    public $type = 'verify';
    public $template = '您的验证码为{1}，请于{2}分钟内正确输入，如非本人操作，请忽略此短信。';
}