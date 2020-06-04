<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Municipality;
use DB;

class MunicipalityController extends Controller
{
	//--------------------------------------------------------------
	// Funciones para obtener la información de los municipios almacenados en la base de datos mediante procedimientos de almacenado
	//--------------------------------------------------------------
	public function index(Request $request){
		$municipalities = Municipality::all();
		// Ejecutar el procedimiento de sqlsserver
		//$municipalities = DB::select("exec sp_search_municipalities");

		if(sizeof($municipalities) != 0 || sizeof($municipalities) != null){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'municipalities'	=> $municipalities
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'	=> 'No se han encontrado registro en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function show($id, Request $request){
		$municipality = Municipality::find($id);
		//$municipality = DB::select("exec sp_search_municipalities_by_id ?", [$id]);
		//var_dump($municipality); die();

		if(is_object($municipality)){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'municipality'	=> $municipality
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado ningun municipio con el id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function showMunicipalitiesByDepartmentId($department_id){
		// Buscar los datos en la BD
		$municipalities = Municipality::where('department_id', $department_id)
									  ->get();

		if(is_object($municipalities)){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'municipalities'=> $municipalities
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado ningun municipio relacionado con un departamento de id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}
}
