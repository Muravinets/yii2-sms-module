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
     * 发送验证码类短信
     * @param $mobile
     * @param $params
     * @param $extra
     * @return mixed
     */
    public function sendVerify($mobile, $params, $extra = []);

    /**
     * 发送验证码类短信
     * @param $mobile
     * @param $params
     * @param $extra
     * @return mixed
     */
    public function sendNotice($mobile, $params, $extra = []);

    /**
     * 是否支持模板自解析
     * @return bool
     */
    public function supportTemplate();

    /**
     * 短信验证码调用自己的接口校验
     * @return bool
     */
    public function supportSelfVerify();
}//end class
