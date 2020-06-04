<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Attached;

class SendEmailController extends Controller
{
    function send(Request $request){
    	$json = $request->input('json', null);
    	$params = json_decode($json);
    	$params_array = json_decode($json, true);

    	if(!empty($params_array)){
    		$validate = \Validator::make($params_array, [
                'sub'                   => 'required|numeric',
                'title'                 => 'required',
    			'name'                  => 'required',
                'destinatary'           => 'required',
    			'surname'               => 'required',
                'second_surname'        => 'nullable',
                'email'                 => 'required|email',
                'affair'                => 'required',
    			'attached_id'           => 'required|numeric',
    			'number_folios'	        => 'required|numeric',
                'filename'              => 'required'
    		]);

    		if($validate->fails()){
    			$data = array(
    				'status' 	=> 'error',
    				'code'		=> 400,
    				'message'	=> 'La validación de los datos ha fallado',
    				'errors'	=> $validate->errors()
    			);
    		} else{
                $attached = Attached::find($params->attached_id);
                $filePath = storage_path('app')."/radicados/".$params->filename;
    			$dataEmail = array(
                    'sub'                   => $params->sub,
                    'title'                 => $params->title,
    				'name'                  => $params->name,
                    'destinatary'           => $params->destinatary,
    				'surname'               => $params->surname,
                    'second_surname'        => $params->second_surname,
                    'affair'                => $params->affair,
    				'attached'              => $attached->name,
                    'number_folios'         => $params->number_folios,
                    'filePath'              => $filePath
    			);
    			Mail::to($params->email)->send(new SendMail($dataEmail));

    			$data = array(
    				'status' 	=> 'success',
    				'code'		=> 200,
    				'message'	=> 'Se ha enviado un email al correo: '.$params->email.' con los documentos correspondientes'
    			);
    		}
    	} else{
    		$data = array(
                'status' => 'error',
                'code' => 411,
                'message' => 'Ha ingrasado los datos de manera incorrecta o incompletos'
            );
    	}

    	return response()->json($data, $data['code']);
    }
}
