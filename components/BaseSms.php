<?php

/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 12/13/16
 * Time: 8:01 PM
 */

namespace ihacklog\sms\components;

use yii\base\Component;
use ihacklog\sms\components\traits\ModuleTrait;

class BaseSms extends Component
{
    use ModuleTrait;

    public $apiUrl = '';

    public $username = null;

    public $password = null;

    public $template = null;

    protected $_error = [];


    public function setTemplate($template = null)
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate() {
        return $this->template;
    }


    public function addErrMsg($code, $msg)
    {
        $this->_error[] = array(
            'code'=>$code,
            'msg'=>$msg
        );
        return $this;
    }

    public function getLastError()
    {
        return array_pop($this->_error);
    }

    public function getErrors()
    {
        return $this->_error;
    }

    /**
     * 记录日志到文件
     * @TODO 解耦
     *
     * @param mixed $err
     * @param string $level
     * @param string $category
     * @return void
     */
    public function logErr($err, $level = 'error', $category = 'sms')
    {
        Yii::log($err, $level, $category);
    }

    /**
     * 检测手机号码是否正确
     *
     */
    public function isMobile($moblie)
    {
        return  preg_match("/^0?1[3-9][0-9]\d{8}$/", $moblie);
    }

    protected function gbk2utf8($gbk_str)
    {
        return iconv('GBK', 'UTF-8', $gbk_str);
    }

    protected function utf82gbk($utf8_str)
    {
        return iconv('UTF-8', 'GBK', $utf8_str);
    }

    /**
     *
     * @param $xml_doc_str
     * @return SimpleXMLElement|bool
     */
    protected function parseXml($xml_doc_str)
    {
        $xml_ele = simplexml_load_string($xml_doc_str);
        if ($xml_ele instanceof SimpleXMLElement) {
            return $xml_ele;
        } else {
            return false;
        }
    }
}