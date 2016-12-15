# yii2-sms-module

# yii2-sms component

* author: HuangYeWuDeng
* website: http://80x86.io


# installation

edit composer.json, and then add:
```json
 "repositories": [
   {
     "type": "git",
     "url": "https://github.com/ihacklog/yii2-sms.git"
   }
  ],
```

```json
  "require": {
    "ihacklog/yii2-sms": "^1.0"
  },
```

# config

```php
    'components' => [
        'sms' => [
            'class' => 'ihacklog\sms\Sms',
            'provider' => 'Yuntongxun', //set default provider
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

#usage

simple usage:

        Yii::$app->sms->send('18899998888', ['6532','5']);

switch provider and set template id:
```php
    Yii::$app->sms
    ->setProvider('File')
    ->setTemplateId(3)
    ->send('18899998888', ['6532','5']);
```