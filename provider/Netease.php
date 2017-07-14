<?php
/**
 * Created by PhpStorm.
 * User: sh4d0walker
 * Date: 9/14/16
 * Time: 4:49 PM
 */

/**
 *
 * http://dev.netease.im/docs?doc=server_sms
 * http://dev.netease.im/docs/product/%E7%9F%AD%E4%BF%A1/%E7%9F%AD%E4%BF%A1%E6%8E%A5%E5%8F%A3%E6%8C%87%E5%8D%97
 *
 */
namespace ihacklog\sms\provider;

use ihacklog\sms\ISms;
use ihacklog\sms\components\BaseSms;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class Netease extends BaseSms implements ISms
{
    const STATUS_SUCC = 200;

    private $return_data = null;

    private $app_key = '';

    private $app_secret = '';

    private $api_url = 'https://api.netease.im/sms/';

    private $is_verify = true;

    function __construct($config)
    {
        parent::__construct($config);
        $this->app_key = $this->get_username();
        $this->app_secret = $this->get_password();
    }

    public function get_provider() {
        return 'Netease';
    }

    public static function checksum_builder($app_secret, $nonce, $cur_time) {
        return sha1($app_secret . $nonce . $cur_time);
    }

    /**
     * 生成http头array
     * http://dev.netease.im/docs?doc=server&#接口概述
     * 接口说明
        所有接口都只支持POST请求；
        所有接口请求Content-Type类型为：application/x-www-form-urlencoded;charset=utf-8；
        所有接口返回类型为JSON，同时进行UTF-8编码。
        以下参数需要放在Http Request Header中
        AppKey	开发者平台分配的appkey
        Nonce	随机数（最大长度128个字符）
        CurTime	当前UTC时间戳，从1970年1月1日0点0 分0 秒开始到现在的秒数(String)
        CheckSum	SHA1(AppSecret + Nonce + CurTime),三个参数拼接的字符串，进行SHA1哈希计算，转化成16进制字符(String，小写)
        CheckSum有效期：出于安全性考虑，每个checkSum的有效期为5分钟(用CurTime计算)，建议每次请求都生成新的checkSum，
        同时请确认发起请求的服务器是与标准时间同步的，比如有NTP服务。
        CheckSum检验失败时会返回414错误码，具体参看code状态表。
     *
     * @return array
     */
    public function header_builder() {
        $nonce = self::create_nonce();
        $cur_time = time();
        return array(
            'AppKey' => $this->app_key,
            'CurTime' => $cur_time,
            'CheckSum' => self::checksum_builder($this->app_secret, $nonce, $cur_time),
            'Nonce' => $nonce,
            'Content-Type' => 'application/x-www-form-urlencoded',
        );
    }

    /**
     * 发送单条短信
     * 短信内容最长为70个字符。
     * 发送的短信内容最后必须附加<strong>【公司名】</strong>字串。
     * 发送成功返回xml:
     *返回值：错误描述对应说明 发送成功：平台消息编号
     * 如： <?xml version="1.0" encoding="utf-8"?><string xmlns="http://tempuri.org/">6288499252231274047</string>
     * @param $mobile 单个手机号码
     * @param $sms_content
     * @param array $extra_params
     * @return bool 发送成功与否
     */
    public function send_verify($mobile, $sms_content, $template_id = null, $extra_params = array())
    {
        //先检测黑名单
        if ($this->has_blackword($mobile, $sms_content)) {
            return false;
        }
        $this->is_verify = true;
        $url = $this->get_api_url('sendcode.action');
        $data = array(
            'mobile' => $mobile,
            'deviceId' => '',
        );
        $rs = $this->post($url, $data, $this->header_builder());
        $this->parse_json($rs);
        if (self::STATUS_SUCC == $this->get_code()) {
            return true;
        } else {
            $this->add_errmsg($this->get_code(), '发送失败失败！' . $rs);
            return false;
        }
    }

    /**
     * 发送成功则返回相关信息。msg字段表示此次发送的sendid；obj字段表示此次发送的验证码，为4位数字。
     * @return mixed
     */
    public function get_verify_code() {
        return $this->is_verify ? $this->get_obj() : 'not verify mode!';
    }

    public function get_sendid() {
        return $this->is_verify ? $this->get_msg() : $this->get_obj();
    }

    public function send_notice($mobile, $sms_content, $template_id = null, $extra_params = array())
    {
        //先检测黑名单
        if ($this->has_blackword($mobile, $sms_content)) {
            return false;
        }
        $this->is_verify = false;
        $url = $this->get_api_url('sendtemplate.action');
        //接收者号码列表，JSONArray格式,如["186xxxxxxxx","186xxxxxxxx"]
        if (is_array($mobile)) {
            $mobileJson = json_encode($mobile);
        } else if (strpos($mobile, ',')) {
            $mobileArr = explode(',', $mobile);
            $mobileJson = json_encode($mobileArr);
        } else {
            $mobileJson = json_encode(array($mobile));
        }
        $extra_params = json_encode($extra_params);
        $data = array(
            'templateid' => $template_id,
            'mobiles' => $mobileJson,
            'params' => $extra_params,
        );
        $rs = $this->post($url, $data, $this->header_builder());
        if (false === $rs) {
            $this->add_errmsg('400', '发送失败失败！网络错误。');
            return false;
        }
        $this->parse_json($rs);
        if (self::STATUS_SUCC == $this->get_code()) {
            return true;
        } else {
            $this->add_errmsg($this->get_code(), '发送失败失败！' . $rs);
            return false;
        }
    }

    /**
     * 验证code 是否正确
     * @param $mobile
     * @param $code
     * @return bool
     */
    public function verify_code($mobile, $code) {
        $url = $this->get_api_url('verifycode.action');
        $data = array(
            'mobile' => $mobile,
            'code' => $code,
        );
        $rs = $this->post($url, $data, $this->header_builder());
        if (false === $rs) {
            $this->add_errmsg('400', '发送失败失败！网络错误。');
            return false;
        }
        $this->parse_json($rs);
        if (self::STATUS_SUCC == $this->get_code()) {
            return true;
        } else {
            $this->add_errmsg($this->get_code(), '发送失败失败！' . $rs);
            return false;
        }
    }

    /**
     * 获取配置中的appkey
     * @return string
     */
    private function get_username()
    {
        return $this->config('USERNAME');
    }

    /**
     * 获取配置中的api url
     * @param string $function 需要调用的功能名称
     * @return string
     */
    private function get_api_url($function = 'sendcode.action')
    {
        return rtrim($this->config('API_URL'), '/') . '/' . $function;
    }

    /**
     * 获取密app secret
     * @return string
     */
    private function get_password()
    {
        return $this->config('PASSWORD');
    }

    /**
     *
     * @param $json_str
     * @return obj
     */
    private function parse_json($json_str)
    {
        if ($json_str === false || empty($json_str)) {
            return false;
        }
        $json_obj = json_decode($json_str);
        if ($json_obj) {
            $this->return_data = $json_obj;
            return $json_obj;
        } else {
            return false;
        }
    }

    private function get_code() {
        return is_object($this->return_data) ? $this->return_data->code : '';
    }

    private function get_msg() {
        return is_object($this->return_data) ? $this->return_data->msg : '';
    }

    private function get_obj() {
        return is_object($this->return_data) ? $this->return_data->obj : '';
    }

    /**
     * 帐号余额查询
     */
    public function get_balance()
    {
        return 'NOT SUPPORTED.';
    }

    public function get_sent_count()
    {
        return 'NOT SUPPORTED.';
    }

    /**
     * 自行解析模板
     * @return bool
     */
    public function supportTemplate() {
        return true;
    }

    /**
     * 短信验证码调用自己的接口校验
     * @return bool
     */
    public function supportSelfVerify() {
        return true;
    }
}