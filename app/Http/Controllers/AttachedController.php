<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attached;
use DB;

class AttachedController extends Controller
{
	public function __construct(){
		$this->middleware('api-auth')->except([
			'index',
			'show'
		]);
	}

	public function index(){
		$attached = Attached::all();

		if(is_object($attached) && sizeof($attached)!=0){
			$data = array(
				'status'				=> 'success',
				'code'					=> 200,
				'attached'				=> $attached
			);
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'No se han encontrado registros en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function show($id){
		$attached = Attached::where('id', $id)
							->first();

		if(is_object($attached)){
			$data = array(
				'status'				=> 'success',
				'code'					=> 200,
				'attached'				=> $attached
			);
		} else{
			$data = array(
				'status'				=> 'error',
				'code'					=> 404,
				'message'				=> 'No se han encontrado ningún adjunto con el id '.$id
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function store(Request $request){
		// Obtener los datos del request
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(is_object($params) && $params != null){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'name'					=> 'required|unique:attached'
			]);
			if($validate->fails()){
				$data = array(
					'status'			=> 'error',
					'code'				=> 400,
					'message'			=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'			=> $validate->errors()
				);
			} else {
				// Guardar usuario en la base de datos
				$attached = new Attached();

				$attached->name = strtoupper($params->name);

				$attached->save();

				$data = array(
					'status'			=> 'success',
					'code'				=> 200,
					'message'			=> 'El tipo de adjunto se ha guardado correctamente'
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

	public function update(Request $request, $id){
		// Buscar si el tipo que se desea actualizar existe en la base de datos
		$attached = Attached::find($id);

		if(is_object($attached) && $attached != null){
			// Recibir los datos json del request
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if(is_object($params) && $params != null){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'name'				=> 'required|unique:attached,name,'.$id
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

					// Actualizar el usuario
					$attached = Attached::where('id', $id)
										->update($params_array);
					if($attached != 0){
						$data = array(
							'status'	=> 'success',
							'code'		=> 200,
							'message'	=> 'El tipo de adjunto '.$params_array['name'].' se ha actualizado correctamente'
						);
					} else{
						$data = array(
							'status'	=> 'error',
							'code'		=> 400,
							'message'	=> 'El tipo de adjunto '.$params_array['name'].' no ha podido actualizar'
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

	public function destroy(Request $request, $id){
		// Buscar si el tipo que se desea actualizar existe en la base de datos
		$attached = Attached::find($id);

		if(is_object($attached) && $attached != null){
			// Eliminar el tipo de adjunto
			$name = $attached->name;

			$attached->delete();
			$data = array(
				'status'				=> 'success',
				'code'					=> '200',
				'message'				=> 'El tipo de adjunto '.$name.' se ha eliminado correctamente'
			);
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'El tipo de adjunto que desea eliminar no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}
}