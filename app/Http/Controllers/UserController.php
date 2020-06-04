<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
	//--------------------------------------------------------------
	// Funciones para obtener los usuarios de la base de datos
	//--------------------------------------------------------------
	public function index(Request $request){
		// Buscar el usuario en la base de datos
		$users = User::all();

		if(is_object($users) && $users != null){
			$data = array(
				'status'				=> 'success',
				'code'					=> 200,
				'users'					=> $users
			);
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'No se han encontrado usuarios en la base de datos'
			);
		}

		// Devolver respuesta 
		return response()->json($data, $data['code']);
	}

	public function show($id, Request $request){
		// Buscar el usuario en la base de datos
		$user = User::find($id);

		if(is_object($user) && $user != null){
			$data = array(
				'status'				=> 'success',
				'code'					=> 200,
				'user'					=> $user
			);
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'No se ha encontrado ningún usuario en la base de datos con el id '.$id
			);
		}

		// Devolver respuesta 
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para almacenar usuarios en la base de datos
	//--------------------------------------------------------------
	public function store(Request $request){
		// Recoger el json de la request
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(is_object($params) && $params != null){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'user_alias'			=> 'required|unique:users',
				'name'					=> 'required|regex:/^[\pL\s\-]+$/u',
				'surname'				=> 'required|regex:/^[\pL\s\-]+$/u',
				'role'					=> 'required',
				'password'				=> 'required'
			]);
			if($validate->fails()){
				$data = array(
					'status'			=> 'error',
					'code'				=> 400,
					'message'			=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'			=> $validate->errors()
				);
			} else {
				// Cifrar la contraseña
				$password_hash = hash('SHA256', $params->password);
				// Guardar el usuario en la base de datos
				$user = new User();
				$user->user_alias 		= $params->user_alias;
				$user->name 			= $params->name;
				$user->surname 			= $params->surname;
				$user->role 			= $params->role;
				$user->password 		= $password_hash;

				$user->save();

				$data = array(
					'status'	=> 'success',
					'code'		=> 201,
					'message'	=> 'Se ha registrado correctamente el nuevo usuario',
					'user'		=> $user
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
	// Funciones para actualizar los campos de los usuarios de la base de datos
	//--------------------------------------------------------------
	public function update($id, Request $request){
		// Buscar el usuario que se desea actualizar
		$user = User::find($id);

		if(is_object($user) && $user != null){
			// Recoger el json del request
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if(is_object($params) && $params != null){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'user_alias'		=> 'required|unique:users,user_alias,'.$id,
					'name'				=> 'required|regex:/^[\pL\s\-]+$/u',
					'surname'			=> 'required|regex:/^[\pL\s\-]+$/u',
					'role'				=> 'required'
				]);
				if($validate->fails()){
					$data = array(
						'status'			=> 'error',
						'code'				=> 400,
						'message'			=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'			=> $validate->errors()
					);
				} else{
					// Eliminar lo que no se desea actualizar 
					unset($params_array['id']);
					unset($params_array['password']);
					unset($params_array['created_at']);
					unset($params_array['updated_at']);

					// Actualizar el usuario
					$user = User::where('id', $id)
								->update($params_array);
					if($user != 0){
						$data = array(
							'status'	=> 'success',
							'code'		=> 201,
							'message'	=> 'Se ha actualizado el usuario '.$params_array['user_alias'].' correctamente',
							'changes'	=> $params_array
						);
					} else{
						$data = array(
							'status'	=> 'error',
							'code'		=> 404,
							'message'	=> 'No se ha podido actualizar el usuario solicitado '.$params_array['user_alias']
						);					
					}
				}
			} else{
				$data = array(
					'status'				=> 'error',
					'code'					=> 400,
					'message'				=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
				);
			}
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'El usuario con el id '.$id.' no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function updatePassword($id, Request $request){
		// Buscar el usuario que se desea actualizar
		$user = User::find($id);

		if(is_object($user) && $user != null){
			// Recoger el json del request
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);
			$user_alias = $params->user_alias;

			if(is_object($params) && $params != null){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'password'			=> 'required'
				]);
				if($validate->fails()){
					$data = array(
						'status'			=> 'error',
						'code'				=> 400,
						'message'			=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'			=> $validate->errors()
					);
				} else{
					// Eliminar lo que no se desea actualizar 
					unset($params_array['id']);
					unset($params_array['user_alias']);
					unset($params_array['name']);
					unset($params_array['surname']);
					unset($params_array['role']);
					unset($params_array['created_at']);
					unset($params_array['updated_at']);

					// Cifrar la contraseña
					$params_array['password'] = hash('SHA256', $params_array['password']);

					// Actualizar el usuario
					$user = User::where('id', $id)
								->update($params_array);
					if($user != 0){
						$data = array(
							'status'	=> 'success',
							'code'		=> 201,
							'message'	=> 'Se ha actualizado la contraseña del usuario '.$user_alias.' correctamente',
							'changes'	=> $params_array
						);
					} else{
						$data = array(
							'status'	=> 'error',
							'code'		=> 404,
							'message'	=> 'No se ha podido actualizar la contraseña del usuario solicitado '.$user_alias
						);					
					}
				}
			} else{
				$data = array(
					'status'				=> 'error',
					'code'					=> 400,
					'message'				=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
				);
			}
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'El usuario con el id '.$id.' no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Funciones para eliminar usuarios de la base de datos
	//--------------------------------------------------------------
	public function destroy($id){
		// Buscar si el usuario existe en la base de datos
		$user = User::find($id);

		if(is_object($user) && $user != null){
			$user_alias = $user->user_alias;
			$user->delete();
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'message'		=> 'El usuario '.$user_alias.' se ha eliminado correctamente'
			);
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'El usuario con el id '.$id.' no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Función login de usuario
	//--------------------------------------------------------------
	public function login(Request $request){
		// Definir la variable jwtAuth
		$jwtAuth = new \JwtAuth();

		// Recibir los datos por POST
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(is_object($params) && $params != null){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'user_alias'			=> 'required',
				'password'				=> 'required'
			]);
			if($validate->fails()){
				$data = array(
					'status'	=> 'error',
					'code'		=> 400,
					'message'	=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'	=> $validate->errors()
				);
			} else{
				// Cifrar la contraseña
				$hash_password = hash('SHA256', $params->password);

				// Devolver el token
				$signup = $jwtAuth->signup($params->user_alias, $hash_password);
				if(isset($params->gettoken)){
					$signup = $jwtAuth->signup($params->user_alias, $hash_password, true);
				}

				if($signup){
					$data = array(
						'status'	=> 'success',
						'code'		=> 200,
						'signup'	=> $signup
					);
				} else{
					$data = array(
						'status'	=> 'error',
						'code'		=> 401,
						'message'	=> 'Los datos ingresados son incorrectos. Login incorrecto'
					);
				}
			}
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}
}
