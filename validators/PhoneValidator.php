<?php
/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 12/14/16
 * Time: 10:55 AM
 * 手机号码格式验证
 * 调用方法：
 *  use ihacklog\sms\validators\PhoneValidator;
 *
 *     public function rules()
        {
        return [
           ...
            ['mobile', PhoneValidator::className()],
            ];
        }
 */

namespace ihacklog\sms\validators;

use yii\validators\Validator;

class PhoneValidator extends Validator
{
    private static $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[6780]|18[0-9]|14[57])[0-9]{8}$/';

    /**
     * @param \yii\base\Model $model 要被验证的模型
     * @param string $attribute 对应模型要被验证的属性
     */
    public function validateAttribute($model, $attribute)
    {
        if (!self::validatePhone($model->$attribute)) {
            $this->addError($model, $attribute, '手机号码格式错误!');
        }
    }

    /**
     * Validates a value.
     * A validator class can implement this method to support data validation out of the context of a data model.
     * @param mixed $value the data value to be validated.
     * @return array|null the error message and the parameters to be inserted into the error message.
     * Null should be returned if the data is valid.
     * @throws NotSupportedException if the validator does not supporting data validation without a model
     */
    protected function validateValue($value)
    {
        if(!$this->validatePhone($value)) {
            return ['手机号码格式错误!'];
        }
        return null;
    }

    /* --------------------- 以下为工具方法 -----------------------/
    /**
     * 验证手机号码格式
     * @param $phone
     * @return bool
     */
    public static function validatePhone($phone)
    {
        if (!preg_match(self::$pattern, $phone)) {
            return false;
        }
        return true;
    }
}