<?php
/**
 * 运营后台审核未通过
 * 您提交的{1}的{2}申请未通过审核，拒绝原因：{3}。
 * {1}表示公司名称
{2}表示申请审核名称。企业认证、地接应用、车调应用、银行卡审核
{3}表示审核未通过原因
 * User: hacklog
 * Date: 7/11/17
 * Time: 3:11 PM
 */
namespace ihacklog\sms\template\alidayu\notice;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class AdminAuditReject extends AlidayuTemplate
{
    public $varNum = 3;
    public $id = 'SMS_104710011';
    public $type = 'notice';
    public $template = '您提交的${para1}的${para2}申请未通过审核，拒绝原因：${para3}。';
}