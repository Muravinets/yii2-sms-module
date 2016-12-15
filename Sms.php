<?php
/**============================================================================
#     FileName: ihacklogSms.class.php
#         Desc: 短信发送
#       Author: 荒野无灯
#      Version: 0.0.1
#   Created: 2013-11-15 11:50:40
#   Mod: 2016-12-13 22:07:22 Wed
#      History:
#      Usage:
```php
'components' => [
    'sms' => [
        'class' => 'ihacklog\sms\Sms',
        'services' => [
            //see http://www.yuntongxun.com/member/main
            'Yuntongxun' => [
                'class' => 'ihacklog\sms\provider\Yuntongxun',
                'apiUrl' => 'https://app.cloopen.com:8883',
                //                  'apiUrl' => 'https://sandboxapp.cloopen.com:8883',
                'templateId' => 1,
                'appId' => 'AppID',
                'accountSid' => 'ACCOUNT SID',
                'accountToken' => 'AUTH TOKEN',
                'softVersion' => '2013-12-26',
            ],
        ],
    ],
],
```
调用：
Yii::$app->sms->send('手机号', '尊敬的用户，您的测试验证码为：' . mt_rand(1000,9999));
/*=============================================================================*/

/*
 *
 *  建议发送流程：接口发送 ----|> 记录短信数据到本地sms表  ----|> return TRUE
 */
namespace ihacklog\sms;

use yii\base\Component;

class Sms extends Component
{
    /**
     * List of SMS service prodiver
     * @var ISms[]
     */
    public $services = [];

    public $provider = null;

    protected $_templateId = null;

    public function init() {
        if (count($this->services) == 0) {
            \Yii::error('No sms servers configured');
            return false;
        }

        $provider = $this->provider;
        if ($provider === null) {
            $provider = array_keys($this->services)[0];
        }
        $this->provider = $provider;
    }

    public function setTemplateId($templateId)
    {
        $this->_templateId = $templateId;
        return $this;
    }

    public function getTemplateId()
    {
        $provider = $this->provider;
        return $this->services[$provider]->getTemplateId();
    }

    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * 发送短信
     */
    public function send($mobile, $content)
    {
        $provider = $this->provider;

        if (!is_object($this->services[$provider])) {
            $this->services[$provider] = \Yii::createObject($this->services[$provider]);
        }
        if (!is_null($this->_templateId)) {
            $this->services[$provider]->setTemplateId($this->_templateId);
        }
        return $this->services[$provider]->send($mobile, $content);
    }

    public function getLastError() {
        $provider = $this->provider;
        return $this->services[$provider]->getLastError();
    }

    public function getErrors() {
        $provider = $this->provider;
        return $this->services[$provider]->getErrors();
    }
}//end class
