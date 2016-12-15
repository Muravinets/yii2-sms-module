<?php
/**
 * Created by PhpStorm.
 * User: HuangYeWuDeng
 * Date: 12/13/16
 * Time: 8:09 PM
 */

namespace ihacklog\sms\provider;

use ihacklog\sms\ISms;
use ihacklog\sms\components\BaseSms;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class Yuntongxun extends BaseSms implements ISms
{
    //apiUrl https://$this->ServerIP:$this->serverPort/

    public $appId;

    public $accountSid;

    public $accountToken;

    public $softVersion;

    public function setTemplateId()
    {
        return false;
    }

    /**
     * @param $mobile 短信接收彿手机号码集合,用英文逗号分开
     * @param $data array 内容数据
     * @return bool
     */
    public function send($mobile, $data)
    {
        $error = '';
        $timestampParam = date('YmdHis');
        // 大写的sig参数
        $sig = strtoupper(md5($this->accountSid . $this->accountToken . $timestampParam));
        // 生成请求URL
        $url = $this->apiUrl . "/$this->softVersion/Accounts/$this->accountSid/SMS/TemplateSMS?sig=$sig";
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->accountSid . ':' . $timestampParam);
        // 生成包头
        $header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json;charset=utf-8',
            'Authorization' => $authen
        ];
        // 发送请求
        $client = new Client();
        try {
            //"{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[".$data."]}";
            $body = [
                'to' => $mobile,
                'templateId' => $this->templateId,
                'appId' => $this->appId,
                'datas' => $data
            ];
            // Request gzipped data, but do not decode it while downloading
            $response = $client->post($url, [
                'headers' => $header,
                'json' => $body
            ]);
        } catch (TransferException $e) {
            $error = sprintf('class: %s, error: %s', self::className(), $e->getMessage());
        }
        $result = (string)$response->getBody();
        $json = json_decode($result);
        if ($json->statusCode == 0) {
            return true;
        } else {
//            var_dump($json->statusMsg);die();
            $this->addErrMsg($json->statusCode, $json->statusMsg);
            return false;
        }
    }
}