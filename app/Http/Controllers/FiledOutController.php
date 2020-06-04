<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FiledOut;

class FiledOutController extends Controller
{
	//--------------------------------------------------------------
	// Funciones para obtener la información del radicado en la base de datos
	//--------------------------------------------------------------
	public function index(){
		$filed = FiledOut::with('AppUsers')
						 ->with('Dependence')
						 ->with('Documents')
						 ->with('Attached')
						 ->get();
		if(is_object($filed) && sizeof($filed) != 0){
			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'filed'			=> $filed
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 400,
				'filed'			=> 'No se han encontrado registros en la base de datos'
			);
		}

		// Devolver el resultado
		return response()->json($data, $data['code']);
	}

	public function show($id){
		$filed = FiledOut::with('AppUsers')
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
				'message'		=> 'No se ha encontrado ningún app users con el id '.$id
			);
		}

		//Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function showByAppUsersId($userId){
		$filed = FiledOut::with('AppUsers')
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
				'code'			=> 400,
				'message'		=> 'No se han encontrado ningún Radicado de Salida perteneciente al usuario con documento '.$userId	
			);
		}

		// Devolver respuesta
		return response()->json($data, $data['code']);
	}

	public function showByAffair($affair){
		$filed = FiledOut::with('AppUsers')
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
				'message'		=> 'No se han encontrado ningún Radicado de Salida cuyo asunto contenga '.$affair	
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
				'filed_in_id'			=> 'nullable|numeric',
				'dependence_id'			=> 'required|numeric',
				'documents_id'			=> 'nullable|numeric',
				'document_date'			=> 'nullable',
				'affair'				=> 'required',
				'number_folios'			=> 'required|numeric',
        		'attached_id'			=> 'required|numeric',
        		'reference'				=> 'nullable',
        		'guide'					=> 'nullable',
        		'desName'				=> 'required',
        		'desSurname'			=> 'required',
        		'desEntity'				=> 'required',
        		'desAddress'			=> 'required'
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
				$filed = new FiledOut();
				$filed->app_users_id	= $params_array['app_users_id'];
				$filed->filed_in_id		= $params_array['filed_in_id'];
				$filed->dependence_id	= $params_array['dependence_id'];
				$filed->documents_id	= $params_array['documents_id'];
				$filed->document_date	= $params_array['document_date'];
				$filed->affair			= $params_array['affair'];
				$filed->number_folios	= $params_array['number_folios'];
				$filed->attached_id		= $params_array['attached_id'];
				$filed->reference 		= $params_array['reference'];
				$filed->guide 			= $params_array['guide'];
				$filed->desName 		= $params_array['desName'];
				$filed->desSurname 		= $params_array['desSurname'];
				$filed->desEntity 		= $params_array['desEntity'];
				$filed->desAddress 		= $params_array['desAddress'];	

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

		// Devolver el resultado
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
				'filed_in_id'			=> 'nullable|numeric',
				'dependence_id'			=> 'required|numeric',
				'documents_id'			=> 'nullable|numeric',
				'document_date'			=> 'nullable',
				'affair'				=> 'required',
				'number_folios'			=> 'required|numeric',
        		'attached_id'			=> 'required|numeric',
        		'reference'				=> 'nullable',
        		'guide'					=> 'nullable',
        		'desName'				=> 'required',
        		'desSurname'			=> 'required',
        		'desEntity'				=> 'required',
        		'desAddress'			=> 'required'
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

				// Actualizar la BD
				$filed = FiledOut::where('id', $id)->update($params_array);
				// Devolver el array con el resultado
				if($filed != 0){
					$data = array(
						'status'		=> 'success',
						'code'			=> 200,
						'message'		=> 'El Radicado de Salida '.$id.' se ha actualizado correctamente',
						'changes'		=> $params_array
					);
				} else{
					$data = array(
						'status'		=> 'error',
						'code'			=> 404,
						'message'		=> 'No se ha podido actualizar el Radicado de Salida: '.$id
					);
				}
			}
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 411,
				'message'		=> 'Ha ingrasado los datos de manera incorrecta o incompletos'
			);
		}

		// Devolver el resultado
		return response()->json($data, $data['code']);
	}

	public function updateDocument($id, Request $request){
		// Recoger los datos json del header
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);
		
		if(!empty($params_array)){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'documents_id'			=> 'required|numeric',
				'document_date'			=> 'nullable'
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
				unset($params_array['app_users_id']);
				unset($params_array['filed_in_id']);
				unset($params_array['dependence_id']);
				unset($params_array['affair']);
				unset($params_array['number_folios']);
				unset($params_array['attached_id']);
				unset($params_array['reference']);
				unset($params_array['guide']);
				unset($params_array['desName']);
				unset($params_array['desSurname']);
				unset($params_array['desEntity']);
				unset($params_array['desAddress']);
				unset($params_array['created_at']);
				unset($params_array['updated_at']);

				unset($params_array['app_users']);
				unset($params_array['dependence']);
				unset($params_array['documents']);
				unset($params_array['attached']);

				// Actualizar la BD
				$filed = FiledOut::where('id', $id)->update($params_array);
				$data = array(
						'status'		=> 'success',
						'code'			=> 200,
						'changes'		=> $params_array
					);
				// Devolver el array con el resultado
				if($filed != 0){
					$data = array(
						'status'		=> 'success',
						'code'			=> 200,
						'message'		=> 'El Radicado de Salida '.$id.' se ha actualizado correctamente con el documento de id: '.$params->documents_id,
						'changes'		=> $params_array
					);
				} else{
					$data = array(
						'status'		=> 'error',
						'code'			=> 404,
						'message'		=> 'No se ha podido actualizar el Radicado de Salida: '.$id
					);
				}
			}
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 411,
				'message'		=> 'Ha ingrasado los datos de manera incorrecta o incompletos'
			);
		}

		// Devolver el resultado
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Función para eliminar la información del radicado requerido en la base de datos
	//--------------------------------------------------------------
	public function destroy($id, Request $request){
		// Comprobar si el radicado existe
		$filed = FiledOut::find($id);
		if(is_object($filed)){
			$filed->delete();

			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'message'		=> 'El radicado se ha eliminado correctamente',
				'filed'			=> $filed
			);
		} else{
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'No existe ningun radicado con el id: '.$id
			);
		}

		// Devolver el resultado
		return response()->json($data, $data['code']);
	}
}
