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

use ihacklog\sms\template\alidayu\AlidayuTemplate;
use ihacklog\sms\vendor\alidayu\lib\Core\Config;
use ihacklog\sms\vendor\alidayu\lib\Core\Profile\DefaultProfile;
use ihacklog\sms\vendor\alidayu\lib\Core\DefaultAcsClient;
use ihacklog\sms\vendor\alidayu\lib\Api\Sms\Request\V20170525\SendSmsRequest;
use ihacklog\sms\vendor\alidayu\lib\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use yii\log\Logger;

class Alidayu extends BaseSms implements ISms
{
    //apiUrl https://$this->ServerIP:$this->serverPort/

    public $accessKeyId;

    public $accessKeySecret;

    /**
     * @var string 短信签名，应严格"签名名称"填写，参考：https://dysms.console.aliyun.com/dysms.htm#/sign
     */
    public $signName = '这里是签名'; // 短信签名

    // 短信API产品名
    public $product = 'Dysmsapi';

    // 短信API产品域名
    public $domain = 'dysmsapi.aliyuncs.com';

    // 暂时不支持多Region
    public $region = 'cn-hangzhou';

    // 服务结点
    public $endPointName = 'cn-hangzhou';

    public $acsClient;

    public function init()
    {
        parent::init();
        // 加载区域结点配置
        Config::load();

        $profile = DefaultProfile::getProfile($this->region, $this->accessKeyId, $this->accessKeySecret);
        // 增加服务结点
        DefaultProfile::addEndpoint($this->endPointName, $this->region, $this->product, $this->domain);
        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);
    }

    /**
     * 发送短信
     *
     * @param $phoneNumbers string 短信接收彿手机号码集合,用英文逗号分开
     * @param $templateParam array 内容数据
     * @return boolean
     */
    public function send($phoneNumbers, $templateParam = null)
    {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);
        // 必填，设置签名名称
        $request->setSignName($this->signName);
        // 必填，设置模板CODE (e.g. SMS_0001) https://dysms.console.aliyun.com/dysms.htm#/template
        $request->setTemplateCode($this->getTemplate()->id);
        // 可选，设置模板参数
        if ($templateParam) {
            $request->setTemplateParam($this->getTemplate()->getParamsJson($templateParam));
        }
        // 可选，设置发送短信流水号 (e.g. 1234)
        $outId = null;
        if($outId) {
            $request->setOutId($outId);
        }
        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);
        // 打印请求结果
//        var_dump($acsResponse);die();

        if ($acsResponse->Code == 'OK' && $acsResponse->Message == 'OK') {
            \Yii::info('sendmobile:' . $phoneNumbers . 'sendsms: code:' . $acsResponse->Code . ';message:'
                . $acsResponse->Message);
            return true;
        } else {
            \Yii::error('sendmobile:' . $phoneNumbers . 'sendsms: code:' . $acsResponse->Code . ';message:'
                . $acsResponse->Message);
            $this->addErrMsg($acsResponse->Code, $acsResponse->Message);
            return false;
        }
    }

    /**
     * 查询短信发送情况范例
     *
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param string $sendDate 必填，短信发送日期，格式Ymd，支持近30天记录查询 (e.g. 20170710)
     * @param int $pageSize 必填，分页大小
     * @param int $currentPage 必填，当前页码
     * @param string $bizId 选填，短信发送流水号 (e.g. abc123)
     * @return stdClass
     */
    public function queryDetails($phoneNumbers, $sendDate, $pageSize = 10, $currentPage = 1, $bizId=null) {
        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();
        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNumbers);
        // 选填，短信发送流水号
        $request->setBizId($bizId);
        // 必填，短信发送日期，支持近30天记录查询，格式Ymd
        $request->setSendDate($sendDate);
        // 必填，分页大小
        $request->setPageSize($pageSize);
        // 必填，当前页码
        $request->setCurrentPage($currentPage);
        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);
        // 打印请求结果
        // var_dump($acsResponse);
        return $acsResponse;
    }

    public function supportTemplate() {
        return true;
    }
}