<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Payfort;
use App\Http\Requests;

use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentMail;

use App\Tables\Payment;
use App\Tables\Payfortlog1;

class PaymentController extends Controller
{
	
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
		$payment = Payment::where('payment_id', $request->payment_id)->first();
		if(!$payment)
		{
			$data['alerts']['alert-danger'][] = 'Payment ID not found, ask the merchant to resend the payment link to you again.';
		}
		$data['payment'] = $payment;
		return view('welcome', $data);
    }
	
	public function callback(Request $request)
	{
		
		$payment = Payment::where('merchant_reference', $request->merchant_reference)->first();
		if(!$payment){
			$data['alerts']['alert-danger'][] = 'Payment not found.';
		}
		
		$data['payment'] = $payment;
		
		if($payment->status == 'success'){
			return redirect('/?payment_id='.$payment->payment_id);
		}
		
		
		$payfort = new Payfort;
		$payfort->customer_email = $payment->email;
		$payfort->merchant_reference = $payment->merchant_reference;
		$payfort->currency = $payment->currency;
		$result = $payfort->pay($payment->amount+$payment->fee);
		
		if($result['response_code'] == '-1'){
			$data['alerts']['alert-danger'][] = $result['message'];
			return view('welcome', $data);
		}
		
		if($result['response_code'] == '20'){
			$response = $result['response'];
			return redirect($response['3ds_url']);
		}
		
		if($result['response_code'] == '000'){
			$payment->ccname = isset($result['response']['card_holder_name'])?preg_replace('/[^0-9A-Za-z ]/', '', $result['response']['card_holder_name']):null;
			$payment->status = 'success';
			$payment->fort_id = $result['fort_id'];
			$payment->save();
			$payment->paid_at = $payment->updated_at;
			$payment->save();
			// Mail::to($payment->user->email)->send(new PaymentMail($payment));
			return redirect('/?payment_id='.$payment->payment_id);
		}else{
			$data['alerts']['alert-danger'][] = $result['message'];
			return view('welcome', $data);
		}
	}
	
	public function ajaxGetFormData(Request $request)
	{
		if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
			return response()->json(['error' => 'Not vaild Email.']);
		}
		$payment = Payment::where('payment_id', $request->payment_id)->first();
		if(!$payment){
			return response()->json(['error' => 'Not vaild request.']);
		}
		if($payment->status == 'success'){
			return response()->json(['error' => 'This amount is already paid.']);
		}else if($payment->status != 'pending'){
			return response()->json(['error' => 'Status not found!']);
		}
		
		$payfort = new Payfort;
		$result = $payfort->getStatusByMerchantReference($payment->merchant_reference);
		if(isset($result['transaction_code'])){
			$transaction_code = substr($result['transaction_code'], 2);
			if($transaction_code === '000'){
				if($payment->status == 'pending'){
					$payment->ccname = isset($result['card_holder_name'])?preg_replace('/[^0-9A-Za-z ]/', '', $result['card_holder_name']):null;
					$payment->status = 'success';
					$payment->fort_id = isset($result['fort_id'])?preg_replace('/[^0-9]/', '', $result['fort_id']):0;
					$payment->save();
					$payment->paid_at = $payment->updated_at;
					$payment->save();
					// Mail::to($payment->user->email)->send(new PaymentMail($payment));
				}
				return response()->json(['success' => 'This transaction already paid!']);
			}else{
				$payment->merchantReferenceUpdate();
			}
		}else{
			$payment->merchantReferenceUpdate();
		}
		$payfort->merchant_reference = $payment->merchant_reference;
		$payfort->currency = $payment->currency;
		$data = $payfort->getFormData();
		$host = $data['host'];
		unset($data['host']);
		$payfortlog1 = new Payfortlog1;
		$payfortlog1->environment = ($payfort->liveMode)?"Live":"SandBox";
		$payfortlog1->merchant_reference = $payfort->merchant_reference;
		$payfortlog1->ip = request()->ip();
		$payfortlog1->amount = $payment->amount+$payment->fee;
		$payfortlog1->payment_id = $payment->id;
		$payfortlog1->save();
		$payment->email = $request->email;
		$payment->save();
		return response()->json(['host' => $host, 'inputs' => $data]); 
	}
}
