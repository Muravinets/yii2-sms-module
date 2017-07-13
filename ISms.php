<?php
/*=============================================================================
#     FileName: ISms.php
#         Desc:
#       Author: 荒野无灯
#      Version: 0.0.1
#   LastChange: 2015-06-23 17:28:26
#      History:
=============================================================================*/
namespace ihacklog\sms;

interface ISms
{
    /**
     * 发送短信
     * @param $mobile
     * @param $content
     * @return mixed
     */
    public function send($mobile, $content);

    /**
     * @return bool 是否支持模板自解析
     */
    public function supportTemplate();
}//end class
