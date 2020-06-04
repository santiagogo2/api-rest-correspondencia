<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SoapController extends SoapBaseController
{
	public function clima(){
        try {
            self::setWsdl('http://webservices.oorsprong.org/websamples.countryinfo/CountryInfoService.wso?WSDL');
            $this->service = InstanceSoapClient::init();
            $cities = $this->service->ListOfCountryNamesByCode();
            //var_dump($cities->ListOfCountryNamesByCodeResult);
            $ciudades = $this->loadXmlStringAsArray($cities->ListOfCountryNamesByCodeResult->tCountryCodeAndName);
            //var_dump($ciudades);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
