<?php
/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/13/17
 * Time: 3:06 PM
 */
namespace ihacklog\sms\demo;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use ihacklog\sms\validators\SmsValidator;

class LoginForm extends Model
{
    public $mobile;
    public $sms_verify_code;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile'], 'required'],
            ['sms_verify_code', SmsValidator::className(), 'template' => 'ihacklog\sms\template\alidayu\verify\Login', 'mobileNumberAttribute' => 'mobile'],
        ];
    }

    /**
     * login
     *
     * @return bool|null the saved model or null if saving fails
     */
    public function login()
    {
        if ($this->validate()) {
            return true;
        }
        return null;
    }
}