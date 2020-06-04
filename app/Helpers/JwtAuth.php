<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{
	public $key;

	public function __construct(){
		$this->key = 'Esta_es_la-Key_para-el_aplicativo_De_CORRESPONDENCIA_#2020';
	}

	public function signup($alias, $password, $getToken = null){
		// Buscar si existe el usuario con las credenciales
		$user = User::where([
			'user_alias'			=> $alias,
			'password'				=> $password
		])->first();

		$signup = false;
		if(is_object($user) && $user != null){
			$signup = true;
		}

		// Generar el token del usuario
		if($signup){
			$token = array(
				'sub'				=> $user->id,
				'alias'				=> $user->user_alias,
				'name'				=> $user->name,
				'surname'			=> $user->surname,
				'role'				=> $user->role,
				'iat'				=> time(),
				'exp'				=> time() + (24*60*60)
			);

			$jwt = JWT::encode($token, $this->key, 'HS256');

			// Devolver los datos decodificados o el token en función del parametro
			if(is_null($getToken)){
				$data = $jwt;
			} else{
				$decode = JWT::decode($jwt, $this->key, ['HS256']);
				$data = $decode;
			}
			return $data;
		}
		return false;
	}

	public function checkToken($jwt, $getIdentity=false){
		$auth = false;

		try{
			$jwt = str_replace('"', '', $jwt);
			$decode = JWT::decode($jwt, $this->key, ['HS256']);
		} catch(\UnexpectedValueException $e){
			$auth = false;
		} catch(\DomainException $e){
			$auth = false;
		}

		if(!empty($decode) && is_object($decode) && isset($decode->sub)){
			$auth = true;
		} else {
			$auth = false;
		}

		if($getIdentity){
			return $decode;
		}
		return $auth;
	}
}
?>