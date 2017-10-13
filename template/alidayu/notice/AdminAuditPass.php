<?php
/**
 * 运营后台审核通过
 * 您提交的{1}的{2}申请已通过审核。
 * {1}表示公司名称
{2}表示申请审核名称。企业认证、地接应用、车调应用、银行卡审核
{3}表示审核未通过原因
 * User: hacklog
 * Date: 7/11/17
 * Time: 3:11 PM
 */
namespace ihacklog\sms\template\alidayu\notice;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class AdminAuditPass extends AlidayuTemplate
{
    public $varNum = 2;
    public $id = 'SMS_104725015';
    public $type = 'notice';
    public $template = '您提交的${para1}的${para2}申请已通过审核。';
}