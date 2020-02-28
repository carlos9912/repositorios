<?php
namespace App\Models;


define('SMS_AUTH_KEY', 'MA1pFlUBfDu79dgzUMNyFwDs6eA0Dj');
define('API_KEY','83B110A407D8DD17EAC65351E89AA161');
define('API_SECRET','ybQ4Bl6YLeFuUqMKQIkWnBimVyEyrsUKz23DBnXj+IY=');
define('HTTP_USER','901192632');
define('HTTP_PASSWORD','j4HvuzJkQ$DpJTt');
define('HTTP_HOST','https://winred.co/api');


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Enums\ETipoUbicacion;
use Carbon\Carbon;

/**
 *
 * @tutorial Working Class
 * ;
 * @since {28/05/2018}
 */
class Winred extends Model
{

    protected $table = 'accesos';
    protected $primaryKey = 'id_acceso';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',// integer,
        'fecha_ingreso',// date NOT NULL default now(),
        'hora_ingreso',// time without time zone NOT NULL default now(),
        'direccion_ip',// character varying(50),
        'navegador'// text,
    ];

    
    public function getHash($string2hash){
		return base64_encode(hash_hmac('sha256',$string2hash,API_SECRET,true));
    }
    
	private function getHeader($request_id,$request_date){
	
                $string2hash=HTTP_USER.$request_id.$request_date.API_KEY;
                $hash_key=$this->getHash($string2hash);
                $header= array(
                        'api_version'=>'1.0',
                        'api_key'=>API_KEY,
                        'request_id'=>$request_id.'',
                        'hash_key'=>$hash_key,
                        'request_date'=>$request_date
                );
		return $header;
    }
    
	private function httpRequest($request,$service){
	                $url=HTTP_HOST.$service;
                        $process = curl_init();
                        //curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
                        curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept: text/plain', 'Content-Type: text/plain'));
                        curl_setopt($process, CURLOPT_HEADER, false);
                        curl_setopt($process, CURLOPT_USERPWD, HTTP_USER . ":" . HTTP_PASSWORD);
                        curl_setopt($process, CURLOPT_TIMEOUT, 30);
                        curl_setopt($process, CURLOPT_POST, true);
                        curl_setopt($process, CURLOPT_POSTFIELDS, $request);
                        curl_setopt($process, CURLOPT_URL,$url);
                        curl_setopt($process,CURLOPT_SSL_VERIFYPEER, false );
                        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
                        $return = utf8_encode(curl_exec($process));
                        curl_close($process);
                        return $return;
	}
    public function queryTx($request_id,$request_date,$suscriber){
        try{
			$header=$this->getHeader($request_id,$request_date);
                        $data=array('suscriber'=>$suscriber);
			$string2hash=json_encode($header).json_encode($data).API_KEY;
			$signature=$this->getHash($string2hash);
                        $request=array(
                                'header'=>$header,
                                'data'=>$data,
                                'signature'=>$signature
                        );
			$response=$this->httpRequest(json_encode($request),'/querytx');
			return $response;

        }catch(Exception $e){
                return $e->getMessage();
        }
    }
	public function topUp($request_id,$request_date,$product_id,$amount,$suscriber,$sell_from='S'){
		try{
				$header=$this->getHeader($request_id,$request_date);
				$data=array(
					'product_id'=>$product_id,
					'amount'=>$amount,
					'suscriber'=>$suscriber,
					'sell_from'=>$sell_from,
					);
				$string2hash=json_encode($header).json_encode($data).API_KEY;
				$signature=$this->getHash($string2hash);
				$request=array(
					'header'=>$header,
					'data'=>$data,
					'signature'=>$signature
				);
				$response=$this->httpRequest(json_encode($request),'/topup');
				return $response;	

		}catch(Exception $e){
			return $e->getMessage();
		}
	}
	
	public function snr($request_id,$request_date,$product_id,$amount,$suscriber,$phone,$email,$office_code,$commit=false,$sell_from='S'){
		try{
				$header=$this->getHeader($request_id,$request_date);
				$data=array(
					'product_id'=>$product_id,
					'amount'=>$amount,
					'suscriber'=>$suscriber,
										'phone'=>$phone,
					'email'=>$email,
					'office_code'=>$office_code,
					'commit'=>$commit,
					'sell_from'=>$sell_from,
					);
				$string2hash=json_encode($header).json_encode($data).API_KEY;
				$signature=$this->getHash($string2hash);
				$request=array(
					'header'=>$header,
					'data'=>$data,
					'signature'=>$signature
				);
				$response=$this->httpRequest(json_encode($request),'/snr');
				return $response;	

		}catch(Exception $e){
			return $e->getMessage();
		}
	}
    
    public function sportBet($request_id,$request_date,$product_id,$amount,$suscriber,$phone,$commit=false,$sell_from='S'){
			try{
					$header=$this->getHeader($request_id,$request_date);
					$data=array(
						'product_id'=>$product_id,
						'amount'=>$amount,
						'suscriber'=>$suscriber,
						'phone'=>$phone,
						'commit'=>$commit,
						'sell_from'=>$sell_from,
						);
					$string2hash=json_encode($header).json_encode($data).API_KEY;
					$signature=$this->getHash($string2hash);
					$request=array(
						'header'=>$header,
						'data'=>$data,
						'signature'=>$signature
					);
					$response=$this->httpRequest(json_encode($request),'/sportbet');
					return $response;	
	
			}catch(Exception $e){
				return $e->getMessage();
			}
		}
		
		public function soat($request_id,$request_date,$product_id,$amount,$suscriber,$client_id_type,$client_id,$class,$phone,$email,$commit=false,$sell_from='S'){
			try{
					$header=$this->getHeader($request_id,$request_date);
					$data=array(
						'product_id'=>$product_id,
						'amount'=>$amount,
						'suscriber'=>$suscriber,
						'client_id_type'=>$client_id_type,
						'client_id'=>$client_id,
						'class'=>$class,
											'phone'=>$phone,
						'email'=>$email,
						'commit'=>$commit,
						'sell_from'=>$sell_from,
						);
					$string2hash=json_encode($header).json_encode($data).API_KEY;
					$signature=$this->getHash($string2hash);
					$request=array(
						'header'=>$header,
						'data'=>$data,
						'signature'=>$signature
					);
					$response=$this->httpRequest(json_encode($request),'/soat');
					return $response;	
	
			}catch(Exception $e){
				return $e->getMessage();
			}
		}
		public function pin($request_id,$request_date,$product_id,$amount,$sell_from='S'){
			try{
							$header=$this->getHeader($request_id,$request_date);
							$data=array(
									'product_id'=>$product_id,
									'amount'=>$amount,
									'sell_from'=>$sell_from,
									);
							$string2hash=json_encode($header).json_encode($data).API_KEY;
							$signature=$this->getHash($string2hash);
							$request=array(
									'header'=>$header,
									'data'=>$data,
									'signature'=>$signature
							);
							$response=$this->httpRequest(json_encode($request),'/pin');
							return $response;

			}catch(Exception $e){
					return $e->getMessage();
			}
		}


}

