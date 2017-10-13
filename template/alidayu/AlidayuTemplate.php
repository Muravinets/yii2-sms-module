<?php
/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 10/13/17
 * Time: 9:26 PM
 */

namespace ihacklog\sms\template\alidayu;

use ihacklog\sms\components\BaseTemplate;

class AlidayuTemplate extends BaseTemplate
{

    public function getParamPlaceHolder()
    {
        $this->_placeHolderNumStart++;
        return sprintf('$' . '{para%d}', $this->_placeHolderNumStart);
    }

    public function getReplacement()
    {
        $this->_placeHolderNumStart = 0;
        for($i=0; $i< $this->varNum; $i++) {
            $this->argsPlaceHolder[] = $this->getParamPlaceHolder();
        }
        return $this->argsPlaceHolder;
    }

    public function getParamKey()
    {
        $this->_placeHolderNumStart++;
        return sprintf('para%d', $this->_placeHolderNumStart);
    }

    public function getParamsJson($paramsArr)
    {
        $arr = [];
        $this->_placeHolderNumStart = 0;
        for ($i = 0; $i< $this->varNum; $i++) {
            $arr[$this->getParamKey()] = $paramsArr[$i];
        }
//        var_dump($this->varNum, $paramsArr);die();
        return json_encode($arr);
    }
}