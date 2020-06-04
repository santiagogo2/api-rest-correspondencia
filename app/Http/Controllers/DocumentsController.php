<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Documents;
use DB;

class DocumentsController extends Controller
{
	//--------------------------------------------------------------
	// Funciones para obtener la información del documento requerido en la base de datos
	//--------------------------------------------------------------
	public function index(){
		$documents = Documents::all();

		if(is_object($documents) && sizeof($documents)!=0){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'documents'			=> $documents
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'No se han encontrado registro en la base de datos'
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	public function show($id){
		$document = Documents::find($id);

		if(is_object($document)){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'document'			=> $document
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'No se han encontrado ningún app users con el id '.$id
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	public function getFile($filename, Request $request){
		$isset = \Storage::disk('radicados')->exists($filename);
		if($isset){
			$file = \Storage::disk('radicados')->get($filename);
			return new Response($file, 200);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'El archivo '.$filename.' no existe en el servidor.'
			);
			return response()->json($data, $data['code']);
		}
	}

	//--------------------------------------------------------------
	// Función para almacenar los documentos almacenados en la base de datos
	//--------------------------------------------------------------
	public function store(Request $request){
		// Recoger el json del header
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(!empty($params_array)){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'name'				=> 'required',
				'document_name'		=> 'required'
			]);
			if($validate->fails()){
				$data = array(
					'status'		=> 'error',
					'code'			=> 400,
					'message'		=> 'La validación de datos ha fallado',
					'errors'		=> $validate->errors()
				);
			} else{
				// Guardar los datos del documento cargado.
				$document = new Documents();
				$document->name = $params_array['name'];
				$document->document_name = $params_array['document_name'];

				$document->save();
				$data = array(
					'status'		=> 'success',
					'code'			=> 200,
					'message'		=> 'El documento se ha guardado correctamente',
					'document'		=> $document
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

	public function uploadFile(Request $request){
		// Recoger los datos de la petición
		$file = $request->file('file0');

		// Validación del archivo.
		$validate = \Validator::make($request->all(), [
			'file0'		=> 'required'
		]);
		if($validate->fails()){
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'La validación de los datos ha fallado. No ha subido los archivos correctamente.',
				'file'				=> $validate->errors()
			);
		} else{
			// Guardar la imágen
			if($file){
				$file_name = time().$file->getClientOriginalName();
                \Storage::disk('radicados')->put($file_name, \File::get($file));

				$data = array(
					'status'		=> 'success',
					'code'			=> 200,
					'message'		=> 'El archivo '.$file->getClientOriginalName().' se ha subido correctamente al servidor.',
					'file'			=> $file_name
				);
			} else{
				$data = array(
					'status'		=> 'error',
					'code'			=> 411,
					'message'		=> 'No se ha podido subir el archivo '.$file->getClientOriginalName().' al servidor.'
				);
			}				
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	//--------------------------------------------------------------
	// Función para actualizar un documento en la base de datos
	//--------------------------------------------------------------
	public function update($id, Request $request){
		// Recoger el json del header
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(!empty($params_array)){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'name'				=> 'required',
				'document_name'		=> 'required'
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

				// Actualizar los datos en la BD
				$document = Documents::where('id', $id)->update($params_array);
				// Devolver array con el resultado
				if($document != 0){
					$data = array(
						'status'			=> 'success',
						'code'				=> 200,
						'message'			=> 'El usuario '.$params->id. ' se ha actualizado correctamente',
						'changes'			=> $params_array
					);
				} else{
					$data = array(
						'status'			=> 'error',
						'code'				=> 404,
						'message'			=> 'No se ha podido actualizar el usuario: '.$params->id
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
	// Función para eliminar los documentos almacenados en la base de datos
	//--------------------------------------------------------------
	public function destroy($id){
		// Comprobar si el usuario existe
		$document = Documents::find($id);
		if(is_object($document)){
			$document->delete();

			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'message'			=> 'El documento se ha eliminado correctamente',
				'document'			=> $document
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 404,
				'message'			=> 'No existe ningun documento con el id: '.$id
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}

	public function deleteFile($filename, Request $request){
		$isset = \Storage::disk('radicados')->exists($filename);

		if($isset){
			$file = \Storage::disk('radicados')->delete($filename);
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'message'			=> 'El archivo '.$filename.' se ha eliminado correctamente.'
			);
		} else{
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'El archivo '.$filename.' no existe en el servidor.'
			);
		}

		// Devolver el resultado.
		return response()->json($data, $data['code']);
	}
}
