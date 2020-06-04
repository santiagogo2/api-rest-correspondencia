<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserClasification;
use DB;

class UserClasificationController extends Controller
{
	public function __construct(){
		$this->middleware('api-auth')->except([
			'index',
			'show'
		]);
	}
	//--------------------------------------------------------------
	// Funciones para obtener la información de la clasificación de usuarios almacenados en la base de datos mediante procedimientos de almacenado
	//--------------------------------------------------------------
	public function index(){
		$usersClasification = UserClasification::all();
		// Ejecutar el procedimiento de sqlsserver
		//$usersClasification = DB::select("exec sp_search_users_clasification");

		if(is_object($usersClasification) && sizeof($usersClasification)!=0){
			$data = array(
				'status'				=> 'success',
				'code'					=> 200,
				'usersClasification'	=> $usersClasification
			);
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'No se han encontrado registro en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function show($id){		
		$userClasification = UserClasification::find($id);
		// Ejecutar el procedimiento de sqlsserver
		//$userClasification = DB::select("exec sp_search_users_clasification_by_id ?", [$id]);

		if(is_object($userClasification)){
			$data = array(
				'status'				=> 'success',
				'code'					=> 200,
				'userClasification'		=> $userClasification
			);
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'No se han encontrado ninguna clasificación de usuario con el id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para almacenar de la clasificación de usuarios en la base de datos
	//--------------------------------------------------------------
	public function store(Request $request){
		// Obtener los datos json del request
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(is_object($params) && $params != null){
			// validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'name'					=> 'required|unique:User_clasification'
			]);
			if($validate->fails()){
				$data = array(
					'status'			=> 'error',
					'code'				=> 400,
					'message'			=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'			=> $validate->errors()
				);
			} else {
				// Guardar el registro
				$userClasification = new UserClasification();

				$userClasification->name = strtoupper($params->name);

				$userClasification->save();

				$data = array(
					'status'			=> 'success',
					'code'				=> 200,
					'message'			=> 'La nueva clasificación de usuario se ha guardado correctamente'
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
	// Funciones para actualizar de la clasificación de usuarios en la base de datos
	//--------------------------------------------------------------
	public function update(Request $request, $id){
		// Validar si la clasificación existe en la base de datos
		$userClasification = UserClasification::find($id);

		if(is_object($userClasification) && $userClasification != null){
			// Recoger los datos json del request
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if(is_object($params) && $params != null){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'name'				=> 'required|unique:User_clasification,name,'.$id
				]);
				if($validate->fails()){
					$data = array(
						'status'		=> 'error',
						'code'			=> 400,
						'message'		=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'		=> $validate->errors()
					);
				} else {
					// Eliminar lo que no se desea actualizar
					unset($params_array['id']);
					unset($params_array['created_at']);
					unset($params_array['updated_at']);

					$params_array['name'] = strtoupper($params_array['name']);

					$userClasification = UserClasification::where('id', $id)
														  ->update($params_array);
					if($userClasification != 0){
						$data = array(
							'status'	=> 'success',
							'code'		=> 200,
							'message'	=> 'La clasificación de usuario '.$params_array['name'].' se ha actualizado correctamente'
						);
					} else{
						$data = array(
							'status'	=> 'error',
							'code'		=> 400,
							'message'	=> 'La clasificación de usuario '.$params_array['name'].' no ha podido ser actualizada'
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
				'message'				=> 'La clasificación de usuario que desea actualizar no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para eliminar de la clasificación de usuarios en la base de datos
	//--------------------------------------------------------------
	public function destroy($id){
		// Buscar si la clasificación existe en la base de datos
		$userClasification = UserClasification::find($id);

		if(is_object($userClasification) && $userClasification != null){
			// Eliminar el registro
			$name = $userClasification->name;

			$userClasification->delete();

			$data = array(
				'status'				=> 'success',
				'code'					=> '200',
				'message'				=> 'La clasificación de usuario '.$name.' se ha eliminado correctamente'
			);
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'La clasificación de usuario que desea eliminar no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}
}
