<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use DB;

class DeparmentController extends Controller
{
	//--------------------------------------------------------------
	// Funciones para obtener la información de los departamentos almacenados en la base de datos mediante procedimientos de almacenado
	//--------------------------------------------------------------
	public function index(Request $request){
		// Ejecutar el procedimiento de sqlsserver
		//$departments = DB::select("exec sp_search_deparments");
		$departments = Department::with('Municipalities')->get();

		if(sizeof($departments) != 0 || sizeof($departments) != null){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'departments'	=> $departments
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
		// Ejecutar el procedimiento sqlserver
		$department = DB::select("exec sp_search_deparments_by_id ?", [$id]);

		if(sizeof($department) != 0 || sizeof($department) != null){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'department'	=> $department
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado ningun departamento con el id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function showByCountryId($country_id, Request $request){
		// Buscar el dato en la base de datos 
		$departments = Department::where('country_id', $country_id)
								->get();

		if(is_object($departments)){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'departments'	=> $departments
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado ningun departamento relacionado con un pais de id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}
}
