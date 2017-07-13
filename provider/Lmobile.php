<?php
/**
 * @Author: 荒野无灯
 * @Date: 13-7-15
 * @Time: 上午11:36
 * @Description:
 * 编码格式请使用UTF-8
 * 接口API url http://cf.lmobile.cn/submitdata/Service.asmx
 * ip.addr == 60.28.200.150
 */

/**
 *
 * 短信接口提交
接口参数填写规范
除企业代码(scorpid)外，其余参数必填；企业代码是在有自由扩展需求的情况下填写。
帐号无效
请联系业务人员确认帐号密码是否正确，请确认您填写的帐号密码跟业务人员提供的完全一样(请特别注意个别字母如大写的i于小写的L是否混淆)
产品编号错误
请检查接口地址跟产品编号是否对应，此接口只能提交触发产品。
程序代码第一行报错
请检查接口地址是否正确：接口地址后面需要添加方法名(g_Submit)
乱码问题
程序编码格式请使用UTF-8
信息提交成功之后没有收到信息
提交成功表示成功提交到平台，但并不代表信息发送成功；如果提交成功之后长时间收不到信息，请联系业务人员查询
 *
 * Class lmobile_cn
 */

namespace ihacklog\sms\provider;

use ihacklog\sms\ISms;
use ihacklog\sms\components\BaseSms;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class Lmobile extends BaseSms implements ISms
{
    public $apiUrl;

    public $corpName;

    public $username;

    public $password;

    public $scorpid;

    public $sprdid;

    private $date_format = 'Y-m-d H:i:s';

    private $common_data = array();

    public function setTemplateId() {
        return false;
    }

    /**
     * called by Object
     */
    public function init()
    {
        //初始化通用数据
        $this->common_data = array(
            'sname' => $this->username,
            'spwd' => $this->password,
            'scorpid' => $this->scorpid,
            'sprdid' => $this->sprdid,
        );
    }

    /**
     * 发送单条短信
     * 短信内容最长为70个字符。
     * 发送的短信内容最后必须附加<strong>【公司名】</strong>字串。
     * 发送成功返回xml:
     * <code>
     * object(SimpleXMLElement)[7]
        public 'State' => string '0' (length=1)
        public 'MsgID' => string '30716130959040764' (length=17)
        public 'MsgState' => string '提交成功' (length=12)
        public 'Reserve' => string '0' (length=1)
     * </code>
     * @param $mobile 单个手机号码
     * @param $sms_content
     * @param array $extra_params
     * @return bool 发送成功与否
     */
    public function send($mobile, $sms_content)
    {

        $url = $this->getApiUrl('g_Submit');
        $data = array(
            'sdst'=>$mobile,
            'smsg'=>$sms_content . $this->corpName,
        );
        $data = array_merge($this->common_data, $data);
        $client = new Client();
        try {
            $response = $client->post($url, [
                'form_params' => $data
            ]);
        } catch (TransferException $e) {
            \Yii::error(strtr('SMS sending to SMS online results in system error: {error}', [
                '{error}' => $e->getMessage()
            ]), self::className());

            throw $e;
        }
        $rs = (string) $response->getBody();
        $xml_ele = $this->parseXml($rs);
        if ($xml_ele) {
            //提交成功（不一定成功发送)
            if ($xml_ele->State == '0') {
                $this->sp_sms_id = $xml_ele->MsgID;
                //审核是否通过标记
                $this->sms_audit_stat = $xml_ele->MsgState;
                return true;
            } else {
                $this->addErrMsg($xml_ele->State, $xml_ele->MsgState);
                return false;
            }
        } else {
            $this->addErrMsg('1001', '连接短信服务器失败！' . $rs);
            return false;
        }
    }

    /**
     * 获取配置中的api url
     * @param string $function 需要调用的功能名称
     * @return string
     */
    private function getApiUrl($function = 'g_Submit')
    {
        return rtrim($this->apiUrl, '/') . '/'. $function;
    }

    public function supportTemplate() {
        return false;
    }
}
