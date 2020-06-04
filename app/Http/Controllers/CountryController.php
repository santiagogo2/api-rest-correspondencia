<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\Department;
use DB;

class CountryController extends Controller
{
	//--------------------------------------------------------------
	// Funciones para obtener la información de los paises almacenados en la base de datos mediante procedimientos de almacenado
	//--------------------------------------------------------------
    public function index(Request $request){
    	// Ejecutar el procedimiento de sqlserver
    	//$countries = DB::select("exec sp_search_countries");
    	$countries = Country::with('Departments')
                            ->get();

    	if(sizeof($countries)!=0 || sizeof($countries)!=null){
    		$data = array(
    			'status'	=> 'success',
    			'code'		=> 200,
    			'countries'	=> $countries
    		);
    	} else{
    		$data = array(
    			'status' 	=> 'error',
    			'code'		=> 404,
    			'message'	=> 'No se han encontrado registro en la base de datos'
    		);
    	}

    	// Devolver respuesta
    	return response()->json($data, $data['code']);
    }

    public function show($id, Request $request){
    	// Ejecutar el procedimiento de sqlserver
    	$country = DB::select("exec sp_search_countries_by_id ?", [$id]);

    	if(sizeof($country)!=0 || sizeof($country)!=null){
    		$data = array(
    			'status'	=> 'success',
    			'code'		=> 200,
    			'country'	=> $country
    		);
    	} else {
    		$data = array(
    			'status' 	=> 'error',
    			'code'		=> 404,
    			'message'	=> 'No se han encontrado ningun pais con el id '.$id
    		);    		
    	}

    	// Devolver respuesta
    	return response()->json($data, $data['code']);
    }
}
