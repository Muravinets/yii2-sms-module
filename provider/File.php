<?php
namespace ihacklog\sms\provider;

use ihacklog\sms\ISms;
use ihacklog\sms\components\BaseSms;


/**
 * Class File
 */
class File extends BaseSms implements ISms
{
    /**
     * File where sms will be saved
     * @var string
     */
    public $file = '@runtime/sms-file.log';
    /**
     * Template of the string
     * @var string
     */
    public $template = "[{date}]\t{phone}\t{message}\n";

    /**
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send($phone, $message)
    {
        $f = fopen(\Yii::getAlias($this->file), 'a+');
        $string = strtr($this->template, [
            '{date}' => date_create('now')->format('Y-m-d H:M:s'),
            '{phone}' => $phone,
            '{message}' => is_array($message) ? json_encode($message, JSON_UNESCAPED_UNICODE) : $message,
        ]);
        fwrite($f, $string);
        fclose($f);
        return true;
    }

    /**
     * we do not support template self parsing
     * @return bool
     */
    public function supportTemplate() {
        return false;
    }
}