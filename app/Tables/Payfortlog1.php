<?php

namespace App\Tables;

use Illuminate\Database\Eloquent\Model;

class Payfortlog1 extends Model
{
	protected $table = 'payfortlog1';
	
	public function payment()
    {
        return $this->belongsTo('App\Tables\Payment');
    }
}
