<?php

namespace ihacklog\sms\vendor\alidayu\lib\Core\Auth;

interface ISigner
{
	public function  getSignatureMethod();
	
	public function  getSignatureVersion();
	
	public function signString($source, $accessSecret); 
}