<?php

namespace ihacklog\sms;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'ihacklog\sms\controllers';

    public $userModelClass = '\common\models\User';
    
    /**
     * @var int 重发时间间隔(单位：秒）
     */
    public $resendTimeSpan = 120;

    /**
     * @var int 单个ip用于统计允许发送的最多次数的限定时间
     */
    public $singleIpTimeSpan = 0;

    /**
     * @var int 单个ip在限定的时间内允许发送的最多次数
     */
    public $singleIpSendLimit = 0;

    public $verifyTimeout = 300;

    /**
     * @var string 测试手机号
     */
    public $testMobileNumber = '18800000000';

    /**
     * @var string 测试时固定验证码数字
     */
    public $testFixedCode = '';

    /**
     * @var bool 是否启用https证书校验
     */
    public $enableHttpsCertVerify = true;

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
