<?php

namespace App\Tables;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentMail;

class Payment extends Model
{
	protected $table = 'payments';
	
	public function user()
    {
        return $this->belongsTo('App\User');
    }
	
	public function merchantReferenceUpdate()
	{
		$this->merchant_reference = 'G'.str_shuffle($this->id . uniqid());
		$this->save();
	}
}
