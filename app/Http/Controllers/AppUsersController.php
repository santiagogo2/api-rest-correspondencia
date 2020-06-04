<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppUsers;
use DB;

class AppUsersController extends Controller
{
	public function __construct(){
		$this->middleware('api-auth')->only('destroy');
	}
	//--------------------------------------------------------------
	// Funciones para obtener la información de la app users almacenados en la base de datos mediante procedimientos de almacenado
	//--------------------------------------------------------------
	public function index(){
		$appUsers = AppUsers::all();
		// Ejecutar el procedimiento de sqlsserver
		//$appUsers = DB::select("exec sp_search_app_users");

		if(is_object($appUsers) && sizeof($appUsers)!=0){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'appUsers'			=> $appUsers
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 404,
				'message'			=> 'No se han encontrado registro en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function show($id){		
		$appUser = AppUsers::where('id', $id)
						   ->with('Municipality')
						   ->get();
		// Ejecutar el procedimiento de sqlsserver
		//$AppUser = DB::select("exec sp_search_app_users_by_id ?", [$id]);

		if(is_object($appUser)){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'appUser'			=> $appUser
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 404,
				'message'			=> 'No se han encontrado ningún app users con el id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function showByNameAndClasification($text, $clasification){
		// Realizar la busqueda en la BD
		$appUsers = AppUsers::where('name', 'like', '%'.$text.'%')
							->where('user_clasification_id', $clasification)
							->orWhere('surname', 'like', '%'.$text.'%')
							->orWhere('second_surname', 'like', '%'.$text.'%')
							->with('Municipality')
							->get();

		if(is_object($appUsers)){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'appUsers'			=> $appUsers
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 404,
				'message'			=> 'No se han encontrado ningún app users con los parametros ingresados.'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para almacenar nuevos app users en la base de datos
	//--------------------------------------------------------------
	public function store(Request $request){
		// Recoger el json del request
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);



		if(!empty($params_array)){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'id'					=> 'required|unique:App_users',
				'user_clasification_id'	=> 'required|numeric',
				'name'					=> 'required|regex:/^[\pL\s\-]+$/u',
				'surname'				=> 'required|regex:/^[\pL\s\-]+$/u',
				'second_surname'		=> 'regex:/^[\pL\s\-]+$/u|nullable',
				'phone'					=> 'nullable',
				'address'				=> 'nullable',
				'email'					=> 'nullable|email',
				'country_id'			=> 'required|numeric',
				'department_id'			=> 'numeric|nullable',
				'municipality_id'		=> 'numeric|nullable'
			]);
			if($validate->fails()){
				$data = array(
					'status'		=> 'error',
					'code'			=> 400,
					'message'		=> 'La validación de datos ha fallado',
					'errors'		=> $validate->errors()
				);
			} else{
				// Guardar el nuevo usuario
				$app_user = new AppUsers();
				$app_user->id = $params_array['id'];
				$app_user->user_clasification_id = $params_array['user_clasification_id'];
				$app_user->name = $params_array['name'];
				$app_user->surname = $params_array['surname'];
				$app_user->second_surname = $params_array['second_surname'];
				$app_user->phone = $params_array['phone'];
				$app_user->address = $params_array['address'];
				$app_user->email = $params_array['email'];
				$app_user->country_id = $params_array['country_id'];
				$app_user->department_id = $params_array['department_id'];
				$app_user->municipality_id = $params_array['municipality_id'];

				$app_user->save();
				$data = array(
					'status'		=> 'success',
					'code'			=> 200,
					'message'		=> 'El usuario se ha guardado correctamente',
					'app_user'		=> $app_user
				);
			}
		} else {
			$data = array(
				'status'		=> 'error',
				'code'			=> 411,
				'message'		=> 'Ha ingrasado los datos de manera incorrecta o incompletos'
			);
		}

		// Devolver la respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para editar app users en la base de datos
	//--------------------------------------------------------------
	public function update($id, Request $request){
		// Obtener los datos json
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(!empty($params_array)){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'user_clasification_id'	=> 'required|numeric',
				'name'					=> 'required|regex:/^[\pL\s\-]+$/u',
				'surname'				=> 'required|regex:/^[\pL\s\-]+$/u',
				'second_surname'		=> 'nullable|regex:/^[\pL\s\-]+$/u',
				'phone'					=> 'nullable',
				'address'				=> 'nullable',
				'email'					=> 'nullable|email',
				'country_id'			=> 'required|numeric',
				'department_id'			=> 'numeric|nullable',
				'municipality_id'		=> 'numeric|nullable'
			]);
			if($validate->fails()){
				$data = array(
					'status'		=> 'error',
					'code'			=> 400,
					'message'		=> 'La validación de datos ha fallado',
					'errors'		=> $validate->errors()
				);
			} else{
				// Retirar el contenido que no se desea actualizar
				unset($params_array['id']);
                unset($params_array['created_at']);
                unset($params_array['updated_at']);

				// Actualizar los datos de la BD
				$app_user = AppUsers::where('id', $id)->update($params_array);
				// Devolver array con el resultado
				if($app_user != 0){
					$data = array(
						'status'	=> 'success',
						'code'		=> 200,
						'message'	=> 'El usuario '.$params->id. ' se ha actualizado correctamente',
						'changes'	=> $params_array
					);
				} else{
					$data = array(
						'status'	=> 'error',
						'code'		=> 404,
						'message'	=> 'No se ha podido actualizar el usuario: '.$params->id.'. El usuario no existe'
					);
				}
			}
		} else {
			$data = array(
				'status'		=> 'error',
				'code'			=> 411,
				'message'		=> 'Ha ingrasado los datos de manera incorrecta o incompletos'
			);
		}

		// Devolver la respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para eliminar app users en la base de datos
	//--------------------------------------------------------------
	public function destroy($id, Request $request){
		// Comprobar si el usuario existe
		$app_user = AppUsers::find($id);
		if(is_object($app_user)){
			$app_user->delete();

			$data = array(
				'status'	=> 'success',
				'code'		=> 200,
				'message'	=> 'El app usuario se ha eliminado correctamente',
				'app_user'	=> $app_user
			);
		} else{
			$data = array(
				'status'	=> 'error',
				'code'		=> 404,
				'message'	=> 'No existe ningun app usuario con el id: '.$id
			);
		}

		// Devolver la respuesta
		return response()->json($data, $data['code']);
	}
}
