<?php

namespace App\Libs;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;

use App\Tables\Payfortlog1;

class Payfort
{
    public $liveMode;
	
	public $sandboxHost;
	public $liveHost;
	public $host;
	
	public $sandboxOperationHost;
	public $liveOperationHost;
	public $operationHost;
	
	public $sandboxPaymentsHost;
	public $livePaymentsHost;
	public $paymentsHost;
	
	public $SHARequestPhrase;
	public $SHAResponsePhrase;
	public $SHAType;
	
    public $service_command;
    public $command;
    public $access_code;
    public $merchant_identifier;
    public $merchant_reference;
    public $language;
    public $signature;
    public $token_name;
    public $expiry_date;
    public $card_number;
    public $card_security_code;
    public $card_holder_name;
    public $customer_email;
    public $remember_me;
    public $return_url;
    public $amount;
    public $currency;
	
	public function __construct()
	{
		$this->liveMode = env('PAYFORT_MERCHANT_IDENTIFIER', false);
		
		$this->sandboxHost = 'https://sbcheckout.payfort.com/FortAPI/paymentPage';
		$this->liveHost = 'https://checkout.payfort.com/FortAPI/paymentPage';
		
		$this->sandboxOperationHost = 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi';
		$this->liveOperationHost = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
		
		$this->sandboxPaymentsHost = 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi';
		$this->livePaymentsHost = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
		
		if(!$this->liveMode){
			$this->host = $this->sandboxHost;
			$this->operationHost = $this->sandboxOperationHost;
			$this->paymentsHost = $this->sandboxPaymentsHost;
			$this->merchant_identifier = env('PAYFORT_TEST_MERCHANT_IDENTIFIER', '');
			$this->access_code = env('PAYFORT_TEST_ACCESS_CODE', '');
			$this->SHARequestPhrase = env('PAYFORT_TEST_SHAREQUEST_PHRASE', '');
			$this->SHAResponsePhrase = env('PAYFORT_TEST_SHARESPONSE_PHRASE', '');
			$this->SHAType = env('PAYFORT_TEST_SHATYPE', '');
		}else{
			$this->host = $this->liveHost;
			$this->operationHost = $this->liveOperationHost;
			$this->paymentsHost = $this->livePaymentsHost;
			$this->merchant_identifier = env('PAYFORT_MERCHANT_IDENTIFIER', '');
			$this->access_code = env('PAYFORT_ACCESS_CODE', '');
			$this->SHARequestPhrase = env('PAYFORT_SHAREQUEST_PHRASE', '');
			$this->SHAResponsePhrase = env('PAYFORT_SHARESPONSE_PHRASE', '');
			$this->SHAType = env('PAYFORT_SHATYPE', '');
		}
		
		$this->service_command = 'TOKENIZATION';
		$this->command = 'PURCHASE';
		$this->merchant_reference = uniqid();
		$this->language  = 'en';
		$this->signature  = null;
		$this->token_name  = null;
		$this->expiry_date  = null;
		$this->card_number  = null;
		$this->card_security_code  = null;
		$this->card_holder_name  = null;
		$this->customer_email  = 'test@test.test';
		$this->remember_me  = 'NO';
		$this->return_url  = url('/callback');
		
		$this->amount  = 0;
		$this->currency = 'USD';
		
	}
	
	private function run()
	{
		$this->return_url  = url('/callback');;
	}
	
	public function getFormData()
	{
		$this->run();
		$data = $this->getFirstRequest();
		$data['signature'] = $this->calculateSignature($this->getFirstRequest(), 'request');
		$this->signature = $data['signature'];
		$data['host'] = $this->host;
		return $data;
	}
	
	public function getFirstRequest()
	{
		$this->run();
		return [
					'service_command' => $this->service_command,
					'merchant_identifier' => $this->merchant_identifier,
					'access_code' => $this->access_code,
					'merchant_reference' => $this->merchant_reference,
					'language' => $this->language,
					'return_url' => $this->return_url
				];
	}
		
	public function getFirstResponse()
	{
		$this->run();
		$data = request()->all();
		unset($data['signature']);
		return $data;
	}
	
	public function getSecondRequest()
	{
		$this->run();
		return [
            'merchant_reference' => $this->merchant_reference,
            'access_code' => $this->access_code,
            'command' => $this->command,
            'merchant_identifier' => $this->merchant_identifier,
            'customer_ip' => Request::ip(),
            'amount' => $this->amount*100,
            'currency' => strtoupper($this->currency),
            'customer_email' => $this->customer_email,
            'customer_name' => request()->card_holder_name,
            'token_name' => $this->token_name,
            'language' => $this->language,
            'return_url' => $this->return_url,
            /*'check_3ds' => 'NO',*/
            'remember_me' => $this->remember_me
        ];
	}
	
	public function getSecondResponse($data)
	{
		$this->run();
		unset($data['r']);
		unset($data['signature']);
		unset($data['integration_type']);
		unset($data['integration_type']);
		return $data;
	}
	
	public function pay($amount)
	{
		$merchant_reference = str_replace('[^a-z0-9]', '', request()->merchant_reference);
		$signature = str_replace('[^a-z0-9]', '', request()->signature);
		$response_code = str_replace('[^0-9]', '', request()->response_code);
		$response_message = str_replace('[^A-Za-z0-9 .]', '', request()->response_message);
		$token_name = str_replace('[^A-Za-z0-9]', '', request()->token_name);
		$payfortlog1 = Payfortlog1::where('merchant_reference', request()->merchant_reference)->first();
		if(!$payfortlog1){
			return ['response_code' => '-1', 'message' => 'Missing data.'];
		}
		
		$payfortlog1->response_code = $response_code;
		$payfortlog1->response_message = $response_message;
		$payfortlog1->token_name = $token_name;
		$payfortlog1->save();
		
		$this->merchant_reference = $payfortlog1->merchant_reference;
		
		if($this->calculateSignature($this->getFirstResponse(), 'response') != request()->signature){
			$payfortlog1->response_message = "Signature not matched";
			$payfortlog1->save();
			return ['response_code' => '-1', 'message' => $payfortlog1->response_message];
		}
		
		if(substr(request()->response_code, 2) != '000'){
			return ['response_code' => '-1', 'message' => $payfortlog1->response_message];
		}
		
		
		if(request()->has('amount')){
			$response_code = substr(request()->response_code, 2);
			if($response_code != '000'){
				$payfortlog1->response_code2 = request()->response_code;
				$payfortlog1->response_message2 = request()->response_message;
				$payfortlog1->save();
				return ['response_code' => $response_code, 'message' => $payfortlog1->response_message2];
			}else{
				$payfortlog1->response_code2 = request()->response_code;
				$payfortlog1->response_message2 = request()->response_message;
				$payfortlog1->fort_id = request()->fort_id;
				$payfortlog1->authorization_code = request()->authorization_code;
				$payfortlog1->save();
				return ['response_code' => $response_code,
						'message' => 'success',
						'merchant_reference' => preg_replace('/[^a-zA-Z0-9.]/', '', request()->merchant_reference),
						'fort_id' => preg_replace('/[^0-9]/', '', request()->fort_id),
						'response' => request()->all()
						];
			}
		}
		
		
		$this->amount = $amount;
		$this->token_name = request()->token_name;
		$result = $this->merchantPageNotifyFort();
		if(!isset($result['response_code'])){
			$payfortlog1->response_message2 = "process error.";
			$payfortlog1->save();
			return ['response_code' => '-1', 'message' => $payfortlog1->response_message2];
		}
		
		$response_code = substr($result['response_code'], 2);
		
		if($this->calculateSignature($this->getSecondResponse($result), 'response') != $result['signature']){
			$payfortlog1->response_message2 = "Signature not matched";
			$payfortlog1->save();
			return ['response_code' => '-1', 'message' => $payfortlog1->response_message2];
		}
		
		if(isset($result['3ds_url'])){
			return ['response_code' => '20', 'message' => $result['response_message'], 'response' => $result];
		}else{
			if($response_code != '000'){
				$payfortlog1->response_code2 = $result['response_code'];
				$payfortlog1->response_message2 = $result['response_message'];
				$payfortlog1->save();
				return ['response_code' => $response_code, 'message' => $payfortlog1->response_message2, 'response' => $result];
			}else{
				$payfortlog1->response_code2 = $result['response_code'];
				$payfortlog1->response_message2 = $result['response_message'];
				$payfortlog1->fort_id = $result['fort_id'];
				$payfortlog1->authorization_code = $result['authorization_code'];
				$payfortlog1->save();
				return ['response_code' => $response_code,
						'message' => 'success',
						'merchant_reference' => preg_replace('/[^a-zA-Z0-9.]/', '', $result['merchant_reference']),
						'fort_id' => preg_replace('/[^0-9]/', '', $result['fort_id']),
						'response' => $result['fort_id']
						];
			}
		}
	}
	
	
    private function merchantPageNotifyFort()
    {
        $postData = $this->getSecondRequest();
        $postData['signature'] = $this->calculateSignature($this->getSecondRequest(), 'request');
        return  $this->callApi($postData);
    }
	
	private function callApi($postData)
	{
        $ch = curl_init();
        $useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0";
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json;charset=UTF-8',
        ));
        curl_setopt($ch, CURLOPT_URL, $this->operationHost);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "compress, gzip");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        $array_result = json_decode($response, true);
        if (!$response || empty($array_result)) {
            return false;
        }
        return $array_result;
	}
	
	
	public function calculateSignature($data, $type = 'request')
	{
		ksort($data);
		$out = '';
		foreach($data as $key => $value)
		{
			$out .= "{$key}={$value}";
		}
		if($type == 'request'){
            $out = $this->SHARequestPhrase.$out.$this->SHARequestPhrase;
        }else{
            $out = $this->SHAResponsePhrase.$out.$this->SHAResponsePhrase;
        }
		return hash($this->SHAType, $out);
	}
	
	public function getStatusByMerchantReference($merchant_reference)
	{
		$postData = [
						'query_command' => 'CHECK_STATUS',
						'access_code' => $this->access_code,
						'merchant_identifier' => $this->merchant_identifier,
						'merchant_reference' => $merchant_reference,
						'language' => $this->language
					];
		$postData['signature'] = $this->calculateSignature($postData, 'request');
		return $this->getStatus($postData);
	}
	
	public function getStatusByFortID($fort_id)
	{
		$postData = [
						'query_command' => 'CHECK_STATUS',
						'access_code' => $this->access_code,
						'merchant_identifier' => $this->merchant_identifier,
						'fort_id' => $fort_id,
						'language' => $this->language
					];
		$postData['signature'] = $this->calculateSignature($postData, 'request');
		return $thi->getStatus($postData);
	}
	
	private function getStatus($postData)
	{
		return $this->callApi($postData);
	}
}
