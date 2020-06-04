<?php

namespace App\Http\Controllers;
use SoapClient;

use Illuminate\Http\Request;

class InstanceSoapClient extends SoapBaseController implements InterfaceInstanceSoap
{
	public static function init(){
		$wsdlUrl = self::getWsdl();
		$soapClientOptions = [
			'stream_context' => self::generateContext(),
			'cache_wsdl'     => WSDL_CACHE_NONE
		];

		$prueba = new SoapClient($wsdlUrl, $soapClientOptions);

		return $prueba;
	}
}
