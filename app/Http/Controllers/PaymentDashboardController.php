<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Payfort;
use App\Http\Requests;

use App\Tables\Payment;

class PaymentDashboardController extends Controller
{
	
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(Request $request)
    {
		$validator =  \Validator::make($request->all(), [
							'amount' => 'required',
							'currency' => 'required'
						]);
        if (count($validator->errors()) > 0) {
			$request->session()->flash('alert-danger', 'Error, not added!');
            return redirect()->back()->withErrors($validator)->withInput();
        }
		$payment = new Payment;
		$payment->name = ($request->name)?$request->name:null;
		$payment->amount = (float)$request->amount;
		if($request->without_fee){
			$payment->fee = 0;
		}else{
			$payment->fee = ceil((auth()->user()->percentage_fee/100)*$request->amount)+auth()->user()->fixed_fee;
		}
		$payment->currency = $request->currency;
		$payment->comment = ($request->comment)?$request->comment:null;
		$payment->status = 'pending';
		$payment->user_id = auth()->user()->id;
		$payment->save();
		$payment->payment_id = str_shuffle(uniqid().$payment->id);
		$payment->save();
		$request->session()->flash('alert-success', "Successful added!");
		$request->session()->flash('link', url('/?payment_id=').$payment->payment_id);
		return redirect("/dashboard/payments")->withInput();
    }
	
	public function edit(Request $request, $id)
	{
		$data['title'] = 'Edit payment';
		$payment = Payment::where('id', $id)->first();
		if(!$payment){
			$data['title'] = '404';
			return view('panel.404', $data);
		}
		if($payment->user_id != auth()->user()->id || $payment->status != 'pending'){
			$data['title'] = '404';
			return view('panel.404', $data);
		}
		$data['payment'] = $payment;
		return view('panel.payments-edit', $data);
		
	}
	
	public function update(Request $request, $id)
	{
		$validator =  \Validator::make($request->all(), [
							'amount' => 'required',
							'currency' => 'required'
						]);
		$payment = Payment::where('id', $id)->first();
		if(!$payment){
			$validator->errors()->add('id', 'Payment not found.');
		}
		if($payment->user_id != auth()->user()->id || $payment->status != 'pending'){
			$validator->errors()->add('id', 'Access deny.');
		}
        if (count($validator->errors()) > 0) {
			$request->session()->flash('alert-danger', 'Error, not added!');
            return redirect()->back()->withErrors($validator)->withInput();
        }
		$update_data = [
				'name' => ($request->name)?$request->name:null,
				'amount' => (float)$request->amount,
				'fee' => ceil((auth()->user()->percentage_fee/100)*$request->amount)+auth()->user()->fixed_fee,
				'currency' => $request->currency,
				'comment' => ($request->comment)?$request->comment:null
			];
		if($request->without_fee){
			$update_data['fee'] = 0;

		}
		Payment::where('id', $payment->id)
				->update($update_data);
		$request->session()->flash('alert-success', "Successful updated!");
		$request->session()->flash('link', url('/?payment_id=').$payment->payment_id);
		return redirect("/dashboard/payments/{$payment->id}/edit")->withInput();
	}
	
	public function delete(Request $request, $id)
	{
		if(!$request->has('ajax')){
			return response()->json(['error' => 'Not vaild request.']);
		}
		$payment = Payment::where('id', $id)->first();
		if(!$payment){
			return response()->json(['error' => 'Payment not found.']);
		}
		if($payment->user_id != auth()->user()->id || $payment->status != 'pending'){
			return response()->json(['error' => 'Access deny.']);
		}
		Payment::where('id', $id)->delete();
		return response()->json([]); 
	}
}
