<?php
/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 10/13/17
 * Time: 8:51 PM
 */

namespace ihacklog\sms\template\alidayu\notice;

use ihacklog\sms\template\alidayu\AlidayuTemplate;

class OrderNotifyProdContact extends AlidayuTemplate
{
    public $varNum = 1;
    public $id = 'SMS_93665010';
    public $type = 'notice';
    public $template = '尊敬的用户，您有一条门票订单支付成功，订单号为${para1}，请及时购票并确认。';
}
