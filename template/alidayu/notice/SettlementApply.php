<?php
/**
 * 上游申请结算 通知 批发商产品联系人第一人
 *
1、申请结算的企业名称
2、申请结算的金额
3、产品名称
4、订单号
5、出发日期
6、订单人数
 * User: hacklog
 * Date: 7/11/17
 * Time: 3:11 PM
 */
namespace ihacklog\sms\template\alidayu\notice;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class SettlementApply extends AlidayuTemplate
{
    public $varNum = 7;
    public $id = 'SMS_110470030';
    public $type = 'notice';
    public $template = '您好，{para1}申请结算{para2}元，请及时处理。产品：{para3}，订单号：{para4}，出发日期：{para5}，出游人数：{para6}人';
}