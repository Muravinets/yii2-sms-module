<?php

namespace ihacklog\sms\models;

use Yii;

/**
 * This is the model class for table "sms_template".
 *
 * @property string $id
 * @property string $template_name
 * @property string $template_content
 * @property integer $template_type
 */
class SmsTemplate extends \yii\db\ActiveRecord
{
    const TEMPLATE_TYPE_SMS = '1';
    const TEMPLATE_TYPE_MESSAGE = '2';
    const TEMPLATE_TYPE_EMAIL = '3';

    private $_parsedContent = '';

    //0注册会员, 1密码找回, 2修改密码, 3修改手机, 4发送新手机验证码, 5提现

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_name', 'template_content', 'template_label'], 'required'],
            [['id', 'template_type'], 'integer'],
            [['template_label'], 'string', 'max' => 60],
            [['template_name'], 'string', 'max' => 60],
            [['template_content'], 'string', 'max' => 2048],
            ['template_type', 'default', 'value' => self::TEMPLATE_TYPE_SMS],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'template_name' => '模板名称',
            'template_content' => '模板内容',
            'template_type' => '模板类型',
            'template_label' => '模块标识(短信以sms_开头,站内信msg_, 邮件email_)'
        ];
    }

    /**
     * @inheritdoc
     * id不自动生成
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->id = date('YmdHis') . mt_rand(1000,9999);
            }
            return true;
        } else {
            return false;
        }
    }

    public function parse($kvArr = [])
    {
        $varArr = [];
        $replaceArr = [];
        if (!empty($kvArr)) {
            $varArr = array_keys($kvArr);
            $replaceArr = array_values($kvArr);
        }
        if (empty($this->_parsedContent)) {
            $this->_parsedContent = $this->template_content;
        }
        $this->_parsedContent = str_replace($varArr, $replaceArr, $this->_parsedContent);
        return $this;
    }

    public function getParsedContent()
    {
        return $this->_parsedContent;
    }


    public static function getTemplateTypeArr()
    {
        return [
            self::TEMPLATE_TYPE_SMS => '短信模板',
            self::TEMPLATE_TYPE_MESSAGE => '站内信模板',
            self::TEMPLATE_TYPE_EMAIL => '邮件模板',
        ];
    }
}
