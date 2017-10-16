# yii2-sms-module


* author: HuangYeWuDeng
* website: http://80x86.io

# installation

edit composer.json, and then add:
```json
 "repositories": [
    {
      "type": "git",
      "url": "https://github.com/ihacklog/yii2-sms-module.git"
    }
  ],
```

```json
  "require": {
    "ihacklog/yii2-sms-module": "^1.2"
  },
```

#create table
```sql
CREATE TABLE `sms` (
  `id` bigint(20) NOT NULL,
  `channel_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '通道类型//（1验证码通道，2 通知类短信通道）',
  `code_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '业务类型//(0注册会员, 1密码找回, 2修改密码, 3修改手机 ...)',
  `template_id` varchar(32) NOT NULL DEFAULT '0',
  `mobile` varchar(16) NOT NULL DEFAULT '' COMMENT '接收方手机号',
  `content` varchar(1024) NOT NULL DEFAULT '' COMMENT '短信内容',
  `device_id` varchar(32) DEFAULT '' COMMENT '设备ID号//（WEB端发起的填写web）',
  `verify_code` varchar(16) NOT NULL DEFAULT '' COMMENT '校验码内容',
  `verify_result` tinyint(4) NOT NULL DEFAULT '0' COMMENT '短信校验结果//（0,未校验，1成功，2失败）针对校验类短信',
  `send_status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '发送状态//0未发送，1发送成功，2发送失败',
  `error_msg` varchar(128) NOT NULL DEFAULT '' COMMENT '短信发送错误代码信息记录',
  `client_ip` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '客户端IPv4 地址',
  `provider` varchar(32) NOT NULL DEFAULT '' COMMENT '服务商名称',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信发送验证记录'
```

# config


## module config:


```php
    'modules' => [
        'sms' => [
            'class' => 'ihacklog\sms\Module',
            'userModelClass' => '\common\models\User', // optional. your User model. Needs to be ActiveRecord.
            'resendTimeSpan' => YII_ENV_PROD ? 60 : 3, //重发时间间隔(单位：秒）
            'singleIpTimeSpan' => YII_ENV_PROD ? 3600 : 0, //单个ip用于统计允许发送的最多次数的限定时间
            'singleIpSendLimit' => YII_ENV_PROD ? 20 : 0, //单个ip在限定的时间内允许发送的最多次数
            'verifyTimeout' => 300, //验证码超时(秒)
            'enableHttpsCertVerify' => YII_ENV_PROD ? true : false, //是否校验https证书,线上环境建议启用
            'testMobileNumber' => '12399996666', //自动化测试短信功能时使用的手机号
        ],
```

## component config:
```php
    'components' => [
        'sms' => [
            'class' => 'ihacklog\sms\Sms',
            'provider' => YII_ENV_PROD ? 'Alidayu' : 'File', //set default provider
            'services' => [
                //see http://www.yuntongxun.com/member/main
                'Yuntongxun' => [
                    'class' => 'ihacklog\sms\provider\Yuntongxun',
                    'apiUrl' => 'https://app.cloopen.com:8883',
                    //'apiUrl' => 'https://sandboxapp.cloopen.com:8883',
                    'appId' => 'AppID',
                    'accountSid' => 'ACCOUNT SID',
                    'accountToken' => 'AUTH TOKEN',
                    'softVersion' => '2013-12-26',
                ],
                'Alidayu' => [
                    'class' => 'ihacklog\sms\provider\Alidayu',
                    'signName' => '公司签名',
                    'accessKeyId' => 'this-is-accessKeyId',
                    'accessKeySecret' => 'this-is-accessKeySecret'
                ],
                'File' => [
                    'class' => 'ihacklog\sms\provider\File',
                ],
            ],
        ],
    ],
```


test

```php
use ihacklog\sms\template\alidayu\verify\Login;
use ihacklog\sms\template\TemplateFactory;

    public function testVerifySmsSend() {
        $sms = new \ihacklog\sms\models\Sms();
        $veryCode = mt_rand(1000, 9999);
        $mobile = '18812345678';
        $loginTemplate = new Login();
        $sendRs = $sms->sendVerify($mobile, $loginTemplate, $veryCode);
        //var_dump($sms->getErrors());die();
        var_dump($sendRs);
        //验证
        $verRs = $sms->verify($mobile, $loginTemplate, $veryCode);
        var_dump($verRs);
        die();
    }
        
        
    /**
     * 测试通知类短信发送
     */
    public function testNoticeSmsSend() {
        sleep(1);
        $sms = new Sms();
        $sms->getModule()->resendTimeSpan = 1;
        $mobile = $sms->getModule()->testMobileNumber;
        //get template
        $auditTemplate = (new TemplateFactory([
            'provider'=> 'alidayu', 
            'tplName' => 'OrderNotifyProdContact', 
            'tplType' => 'notice'
            ]))
            ->getTemplate();
        //or you can use this
/*        $auditTemplate = (new TemplateFactory())
            ->setProvider('alidayu')
            ->setTplName('OrderNotifyProdContact')
            ->setTplType('notice')
            ->getTemplate();*/
        $sendRs = $sms->sendNotice($mobile, $auditTemplate,'ORDER_NO_T_201710132241-' . mt_rand(1000,9999));
        $this->assertTrue($sendRs == true);
    }
```

# sms component usage

simple usage:
```php
use ihacklog\sms\template\alidayu\verify\General;
use ihacklog\sms\template\alidayu\verify\Login;

//General template has 2 params
Yii::$app->sms->send('18899998888', ['8899', '5']);

Yii::$app->sms->send('18899998888', ['8899']);
```

```php
//switch provider and set template id:
    Yii::$app->sms
    ->setProvider('File')
    ->send('18899998888', ['8899']);
```

template available:
```
验证码类：

一般只有一个参数， 参数：1 验证码

公共验证码General  有两个参数：参数：1 验证码， 2. 有效分钟（目前固定值5）

```php
//登录短信验证码
use ihacklog\sms\template\alidayu\verify\Login;

//重置支付密码验证码
use ihacklog\sms\template\alidayu\verify\ResetPayPwd;

//重置登录密码验证码
use ihacklog\sms\template\alidayu\verify\ResetLoginPwd;

//企业入驻短信验证码
use ihacklog\sms\template\alidayu\verify\CompanySettleIn;

//更换手机号码 验证原手机号码
use ihacklog\sms\template\alidayu\verify\ChangeMobilePhoneStepOne;

//更换手机号码 验证新手机号码
use ihacklog\sms\template\alidayu\verify\ChangeMobilePhoneStepTwo;

//公共验证码
use ihacklog\sms\template\alidayu\verify\General;
```
