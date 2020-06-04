<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FiledIn;

class FiledInController extends Controller
{
	//--------------------------------------------------------------
	// Funciones para obtener la información del radicado en la base de datos
	//--------------------------------------------------------------
	public function index(){
		$filed = FiledIn::with('AppUsers')
						->with('Country')
						->with('Department')
						->with('Municipality')
						->with('Dependence')
						->with('Documents')
						->get();

		if(is_object($filed) && sizeof($filed)!=0){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'filed'				=> $filed
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'filed'				=> 'No se han encontrado registro en la base de datos'
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	public function show($id){
		$filed = FiledIn::with('AppUsers')
						->with('Dependence')
						->with('Documents')
						->with('Attached')
						->find($id);

		if(is_object($filed)){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'filed'			=> $filed
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 400,
				'message'			=> 'No se han encontrado ningún Radicado de Entrada con el ID '.$id
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	public function showByAppUsersId($userId){
		$filed = FiledIn::with('AppUsers')
						->with('Dependence')
						->with('Documents')
						->with('Attached')
						->where('app_users_id', $userId)
						->get();

		if(is_object($filed)){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'filed'			=> $filed		
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado ningún Radicado de Entrada perteneciente al usuario con documento '.$userId	
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function showByAffair($affair){
		$filed = FiledIn::with('AppUsers')
						->with('Dependence')
						->with('Documents')
						->with('Attached')
						->where('affair', 'like', '%'.$affair.'%')
						->get();

		if(is_object($filed)){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'filed'			=> $filed
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No se han encontrado ningún Radicado de Entrada cuyo asunto contenga '.$affair	
			);
		}

		// Devolver Respuesta
		return response()->json($data, $data['code']);

	}

	//--------------------------------------------------------------
	// Función para guardar la información del radicado requerido en la base de datos
	//--------------------------------------------------------------
	public function store(Request $request){
		// Recoger los datos json del header
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(!empty($params_array)){
			// Validar los datos recibidos
			$validate = \Validator::make($params_array, [
				'app_users_id'			=> 'required|numeric',
				'dependence_id'			=> 'required|numeric',
				'documents_id'			=> 'nullable|numeric',
				'document_date'			=> 'nullable',
				'affair'				=> 'required',
				'number_folios'			=> 'required|numeric',
        		'attached_id'			=> 'required|numeric',
        		'reference'				=> 'nullable',
        		'guide'					=> 'nullable'
			]);
			if($validate->fails()){
				$data = array(
					'status'		=> 'error',
					'code'			=> 400,
					'message'		=> 'La validación de datos ha fallado',
					'errors'		=> $validate->errors()
				);
			} else{
				// Guardar el registro
				$filed = new FiledIn();
				$filed->app_users_id	= $params_array['app_users_id'];
				$filed->dependence_id	= $params_array['dependence_id'];
				$filed->documents_id	= $params_array['documents_id'];
				$filed->document_date	= $params_array['document_date'];
				$filed->affair			= $params_array['affair'];
				$filed->number_folios	= $params_array['number_folios'];
				$filed->attached_id		= $params_array['attached_id'];
				$filed->reference		= $params_array['reference'];
				$filed->guide			= $params_array['guide'];

				$filed->save();
				$data = array(
					'status'		=> 'success',
					'code'			=> 200,
					'message'		=> 'El radicado se ha guardado correctamente',
					'filed'			=> $filed
				);
			}
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 411,
				'message'			=> 'Ha ingrasado los datos de manera incorrecta o incompletos'
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Función para actualizar la información del radicado requerido en la base de datos
	//--------------------------------------------------------------
	public function update($id, Request $request){
		// Recoger los datos json del header
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(!empty($params_array)){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'app_users_id'			=> 'required|numeric',
				'dependence_id'			=> 'required|numeric',
				'documents_id'			=> 'required|numeric',
				'document_date'			=> 'nullable',
				'affair'				=> 'required',
				'number_folios'			=> 'required|numeric',
        		'attached_id'			=> 'required|numeric',
        		'reference'				=> 'nullable',
        		'guide'					=> 'nullable'
			]);

			if($validate->fails()){
				$data = array(
					'status'		=> 'error',
					'code'			=> 400,
					'message'		=> 'La validación de datos ha fallado',
					'errors'		=> $validate->errors()
				);
			} else{
				// Remover los datos que no queremos actualizar
				unset($params_array['id']);
				unset($params_array['created_at']);
				unset($params_array['updated_at']);

				// Actualizar los datos de la BD
				$filed = FiledIn::where('id', $id)->update($params_array);
				// Devolver array con el resultado
				if($filed != 0){
					$data = array(
						'status'			=> 'success',
						'code'				=> 200,
						'message'			=> 'El radicado '.$params->id. ' se ha actualizado correctamente',
						'changes'			=> $params_array
					);
				} else{
					$data = array(
						'status'			=> 'error',
						'code'				=> 404,
						'message'			=> 'No se ha podido actualizar el radicado: '.$params->id
					);
				}
			}
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 411,
				'message'			=> 'Ha ingrasado los datos de manera incorrecta o incompletos'
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Función para eliminar la información del radicado requerido en la base de datos
	//--------------------------------------------------------------
	public function destroy($id, Request $request){
		// Comprobar si el radicado existe
		$filed = FiledIn::find($id);
		if(is_object($filed)){
			$filed->delete();

			$data = array(
				'status'	=> 'success',
				'code'		=> 200,
				'message'	=> 'El radicado se ha eliminado correctamente',
				'filed'	=> $filed
			);
		} else{
			$data = array(
				'status'	=> 'error',
				'code'		=> 404,
				'message'	=> 'No existe ningun radicado con el id: '.$id
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}
}
