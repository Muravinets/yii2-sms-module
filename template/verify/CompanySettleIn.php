<?php
/**
 * 企业入驻短信验证码
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 5:07 PM
 */

namespace ihacklog\sms\template\verify;

use ihacklog\sms\components\BaseTemplate;

class CompanySettleIn extends BaseTemplate
{
    public $varNum = 1;
    public $id = '177550';
    public $type = 'verify';
    public $template = '验证码{1}，您正在申请企业入驻，请勿泄露验证码。';
}