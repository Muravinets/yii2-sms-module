<?php
/**
 * Created by PhpStorm.
 * User: sh4d0walker
 * Date: 9/17/15
 * Time: 8:51 PM
 */

namespace ihacklog\sms\components\traits;

use ihacklog\sms\Module;
use Yii;

/**
 * Class ModuleTrait
 * @package ihacklog\sms\components\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \vova07\users\Module|null Module instance
     */
    private $_module;

    /**
     * @return \vova07\users\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $module = Module::getInstance();
            if ($module instanceof Module) {
                $this->_module = $module;
            } else {
                $this->_module = Yii::$app->getModule('sms');
            }
        }
        return $this->_module;
    }
}