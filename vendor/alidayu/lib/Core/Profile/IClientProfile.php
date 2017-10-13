<?php

namespace ihacklog\sms\vendor\alidayu\lib\Core\Profile;

interface IClientProfile
{
	public function getSigner();
	
	public function getRegionId();
	
	public function getFormat();
	
	public function getCredential();
}