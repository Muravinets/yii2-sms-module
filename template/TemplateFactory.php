<?php
/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 10/13/17
 * Time: 8:42 PM
 */

namespace ihacklog\sms\template;

use Yii;
use yii\base\Component;
use yii\base\Exception;

class TemplateFactory extends Component
{
    public $provider = '';

    public $tplType = 'verify';

    public $tplName = 'General';

    public static $tplContainer;

    /**
     * @author HuangYeWuDeng
     * @return mixed
     * @throws Exception
     */
    public function getTemplate()
    {
        //@TODO detect Yii::$app->sms->provider more effectively
        $this->provider = empty($this->provider) ? Yii::$app->sms->provider : $this->provider;
        $templatePath = __DIR__  . '/' . strtolower($this->provider) . '/' . $this->tplType . '/' . $this->tplName . '.php';
        $templateFullDomainClass = 'ihacklog\sms\template'. '\\' . $this->provider . '\\' . $this->tplType . '\\' . $this->tplName;
        $tplPathHash = md5($templateFullDomainClass);
        if (!is_file($templatePath)) {
            throw new Exception('template file ' . $templatePath . ' does not exists!');
        }
        if (!isset(self::$tplContainer[$tplPathHash])) {
            require $templatePath;
            $t = new $templateFullDomainClass;
            self::$tplContainer[$tplPathHash] = $t;
        } else {
            $t = self::$tplContainer[$tplPathHash];
        }
        return $t;
    }

    public function setTplName($name) {
        $this->tplName = $name;
        return $this;
    }

    public function setTplType($type) {
        $this->tplType = $type;
        return $this;
    }
}