<?php
/**
 * 企业入驻短信验证码
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\alidayu\verify;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class CompanySettleIn extends AlidayuTemplate
{
    public $varNum = 1;
    public $id = 'SMS_104785009';
    public $type = 'verify';
    public $template = '验证码${para1}，您正在申请企业入驻，请勿泄露验证码。';
}