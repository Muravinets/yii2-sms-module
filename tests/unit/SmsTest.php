<?php
namespace tests\codeception\common\unit;

use Yii;
use Codeception\Specify;
use yii\codeception\TestCase as Yii2TestCase;
use ihacklog\sms\template\notice\AdminAuditPass;
use ihacklog\sms\template\notice\AdminAuditReject;
use ihacklog\sms\models\Sms;
use ihacklog\sms\template\verify\Login;
use ihacklog\sms\demo\LoginForm;

class SmsTest extends Yii2TestCase
{
    public $appConfig = '@tests/codeception/config/common/unit.php';

    /**
     * @var \tests\codeception\common\UnitTester
     */
    protected $tester;


    protected function _before()
    {
    }


    protected function _after()
    {
    }

    /**
     * 测试模板解析（通过审核）
     */
    public function testTemplateAdminAuditPass()
    {
        $smsAuditPass = new AdminAuditPass();
        $content = $smsAuditPass->parseTemplate('x科技公司', '银行卡审核');
        echo $content;
        $this->assertTrue(!empty($content));
        $this->assertTrue($content === '您提交的x科技公司的银行卡审核申请已通过审核。');
    }

    /**
     * 测试模板解析（拒绝审核）
     */
    public function testTemplateParseAdminAuditReject()
    {
        $smsAuditPass = new AdminAuditReject();
        $content = $smsAuditPass->parseTemplate('x科技公司', '银行卡审核', '信息不完整');
        echo $content;
        $this->assertTrue(!empty($content));
        $this->assertTrue($content === '您提交的x科技公司的银行卡审核申请未通过审核，拒绝原因：信息不完整。');
    }

    /**
     * 测试验证码类短信发送与验证(106通道）
     */
    public function testVerifySmsSendAndVerify() {
        $sms = new Sms();
        $mobile = $sms->getModule()->testMobileNumber;
        $veryCode = mt_rand(1000, 9999);
        $loginTemplate = new Login();
        $sendRs = $sms->sendVerify($mobile, $loginTemplate, $veryCode);
//        var_dump($sms->getErrors());die();
        $this->assertTrue($sendRs == true);
        //验证
        $verRs = $sms->verify($mobile, $loginTemplate, $veryCode);
        $this->assertTrue($verRs == true);
    }

    /**
     * 测试通知类短信发送
     */
    public function testNoticeSmsSend() {
        sleep(1);
        $sms = new Sms();
        $sms->getModule()->resendTimeSpan = 1;
        $mobile = $sms->getModule()->testMobileNumber;
        $auditTemplate = new AdminAuditReject();
        $sendRs = $sms->sendNotice($mobile, $auditTemplate,
            'super-man科技有限公司', '银行卡6228480********' . mt_rand(1000, 9999) . '审核', '资料不全');
        $this->assertTrue($sendRs == true);
    }

    public function testValidatorOK()
    {
        sleep(1);
        $sms = new Sms();
        $mobile = $sms->getModule()->testMobileNumber;
        $sms->getModule()->resendTimeSpan = 1;
        $veryCode = mt_rand(1000, 9999);
        $loginTemplate = new Login();
        $sendRs = $sms->sendVerify($mobile, $loginTemplate, $veryCode);
        $this->assertTrue($sendRs == true);

        //let's start validate
        $form = new LoginForm();
        $form->mobile = $mobile;
        $form->sms_verify_code = $veryCode;
        $this->assertTrue($form->validate() == true);
    }

    public function testValidatorErr()
    {
        sleep(1);
        $sms = new Sms();
        $sms->getModule()->resendTimeSpan = 1;
        $mobile = $sms->getModule()->testMobileNumber;
        $veryCode = mt_rand(1000, 9999);
        $loginTemplate = new Login();
        $sendRs = $sms->sendVerify($mobile, $loginTemplate, $veryCode);
        $this->assertTrue($sendRs == true);

        //let's start validate
        $form = new LoginForm();
        $form->mobile = $mobile;
        $form->sms_verify_code = '1234';
        $this->assertFalse($form->validate() == true);
    }
}