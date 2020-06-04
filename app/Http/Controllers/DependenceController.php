<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dependence;
use DB;

class DependenceController extends Controller
{
	public function __construct(){
		$this->middleware('api-auth')->except([
			'index',
			'show'
		]);
	}

	//--------------------------------------------------------------
	// Funciones para obtener la información de las dependencias almacenados en la base de datos mediante procedimientos de almacenado
	//--------------------------------------------------------------
	public function index(){
		$dependences = Dependence::orderBy('code')->get();
		// Ejecutar el procedimiento de sqlsserver
		//$dependence = DB::select("exec sp_search_dependences");

		if(is_object($dependences) && sizeof($dependences)!=0){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'dependences'	=> $dependences
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado registro en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function show($id){
		$dependence = Dependence::find($id);
		// Ejecutar el procedimiento de sqlsserver
		//$dependence = DB::select("exec sp_search_dependences_by_id ?", [$id]);

		if(is_object($dependence)){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'dependence'	=> $dependence
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado ninguna dependencia con el id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para guardar la información de las dependencias en la base de datos
	//--------------------------------------------------------------
	public function store(Request $request){
		// Recoger los datos json del request
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(is_object($params) && $params != null){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'code'					=> 'required|numeric|unique:dependence',
				'name'					=> 'required|regex:/^[\pL\s\-]+$/u',
				'email'					=> 'required|email'
			]);
			if($validate->fails()){
				$data = array(
					'status'			=> 'error',
					'code'				=> 400,
					'message'			=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'			=> $validate->errors()
				);
			} else {
				$dependence = new Dependence();

				$dependence->code 		= $params->code;
				$dependence->name 		= strtoupper($params->name);
				$dependence->email 		= $params->email;

				$dependence->save();

				$data = array(
					'status'			=> 'success',
					'code'				=> 200,
					'message'			=> 'La dependencia se ha guardado correctamente'
				);
			}
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para actualizar la información de las dependencias en la base de datos
	//--------------------------------------------------------------
	public function update(Request $request, $id){
		// Buscar si la dependencia que se desea actualizar existe en la base de datos
		$dependence = Dependence::find($id);

		if(is_object($dependence) && $dependence != null){
			// Recoger los datos json del request
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if(is_object($params) && $params != null){
				// validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'code'				=> 'required|numeric|unique:dependence,code,'.$id,
					'name'				=> 'required|regex:/^[\pL\s\-]+$/u',
					'email'				=> 'required|email'
				]);
				if($validate->fails()){
					$data = array(
						'status'		=> 'error',
						'code'			=> 400,
						'message'		=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'		=> $validate->errors()
					);
				} else {
					// Eliminar las dependencias que no se desean actualizar
					unset($params_array['id']);
					unset($params_array['created_at']);
					unset($params_array['updated_at']);

					$dependence = Dependence::where('id', $id)
											->update($params_array);
					if($dependence != 0){
						$data = array(
							'status'	=> 'success',
							'code'		=> 200,
							'message'	=> 'La dependencia '.$params_array['code'].'-'.$params_array['name'].' se ha actualizado correctamente'
						);
					} else{
						$data = array(
							'status'	=> 'error',
							'code'		=> 400,
							'message'	=> 'La dependencia '.$params_array['code'].'-'.$params_array['name'].' no ha podido ser actualizada'
						);
					}
				}
			} else {
				$data = array(
					'status'			=> 'error',
					'code'				=> 400,
					'message'			=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
				);
			}
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'La dependencia que desea actualizar no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para eliminar la información de las dependencias en la base de datos
	//--------------------------------------------------------------
	public function destroy($id){
		// Buscar si la dependencia existe en la base de datos
		$dependence = Dependence::find($id);

		if(is_object($dependence) && $dependence != null){
			// Eliminar el registro
			$name = $dependence->code.'-'.$dependence->name;

			$dependence->delete();
			$data = array(
				'status'				=> 'success',
				'code'					=> '200',
				'message'				=> 'La dependencia '.$name.' se ha eliminado correctamente'
			);
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'La dependencia que desea eliminar no existe en la base de datos'
			);
		}

		// Devolver respuesta 
		return response()->json($data, $data['code']);
	}
}
