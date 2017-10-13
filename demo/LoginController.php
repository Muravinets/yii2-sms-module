<?php
/**
 * 这里演示了如何便捷调用GetSmsAction实际一个短信发送接口
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/13/17
 * Time: 3:29 PM
 */

namespace ihacklog\sms\demo;

use ihacklog\sms\actions\GetSmsAction;

class LoginController
{

    public function actions()
    {
        return [
            //获取登录短信
            'get-login-sms' => [
                'class' => GetSmsAction::className(),
                //'initCallback' => [$this, 'loginSmsInitCallback'],
                'beforeCallback' => [$this, 'loginSmsBeforeCallback'],
                'mobile' => \Yii::$app->getRequest()->post('mobile'),
                //此处的template要与LoginForm中的template对应
                'template' => 'ihacklog\sms\template\alidayu\verify\Login',
            ],
        ];
    }

    /**
     * 手机注册获取短信前验证(示例：获取短信前先校验图形验证码是否正确)
     * 此demo不能实际运行，因为这里只有部分代码
     * @param $action
     * @return bool
     */
    public function loginSmsBeforeCallback($action)
    {
        $model = new LoginForm();
        $model->setScenario('sms_login_pre');
        $model->load(Yii::$app->getRequest()->post(), '');
        //前置操作，校验图形验证码是否正确
        if ($model->validate()) {
            return true;
        }
        $action->error = current($model->getErrors())[0];
        $action->data = ['sessionKey' => $model->captchaSessionKey];
        return false;
    }

}