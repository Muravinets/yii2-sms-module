<?php
/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 7/11/17
 * Time: 4:51 PM
 */

namespace ihacklog\sms\components;

use ihacklog\sms\components\traits\ModuleTrait;
use yii\base\Component;

class BaseTemplate extends Component
{
    use ModuleTrait;

    protected $_placeHolderNumStart = 0;

    public $argsPlaceHolder;

    public $args;

    /**
     * @var int 模块变量数量
     */
    public $varNum = 0;

    /**
     * @var string 模板ID
     */
    public $id;
    /**
     * @var string 模块类型
     */
    public $type;

    /**
     * @var string 模块内容
     */
    public $template;

    public function getParamPlaceHolder()
    {
        $this->_placeHolderNumStart++;
        return sprintf('{%d}', $this->_placeHolderNumStart);
    }

    public function getReplacement()
    {
        for($i=0; $i< $this->varNum; $i++) {
            $this->argsPlaceHolder[] = $this->getParamPlaceHolder();
        }
        return $this->argsPlaceHolder;
    }

    public function parseTemplate()
    {
        $args = func_get_args();
        if (isset($args[0]) && is_array($args[0])) {
            $argc = count($args[0]);
            $args = $args[0];
        } else {
            $argc = func_num_args();
        }
        if ($argc != $this->varNum) {
            throw new \ErrorException('invalid arg num');
        }
        $this->args = $args;
        $content = str_replace($this->getReplacement(), $this->args, $this->template);
        return $content;
    }
}