@extends('emails.oxygen.layout')
@section('title', $title)
@section('content1')
<tr>
	<td class="free-text">
		
	</td>
</tr>
<tr>
	<td class="w320">
	  <table cellpadding="0" cellspacing="0" width="100%">
		<tr>
		  <td>
			<table cellpadding="0" cellspacing="0" width="100%">
			  <tr>
				<td class="mini-block-padding">
				  <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:separate !important;">
					<tr>
					  <td class="mini-block">
						<div><span>Reference: </span><strong><a href="{{ url('/') }}?payment_id={{ $payment->payment_id }}">#{{$payment->id}}</a></strong></div>
						<div><span>Title: </span><strong>{{$payment->name}}</strong></div>
						<div><span>Name: </span><strong>{{$payment->ccname}}</strong></div>
						<div><span>Paid Date: </span><strong>{{ date('d M Y', strtotime($payment->paid_at)) }}</strong></div>
						<div><span>Net Amount: </span><strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong></div>
						<div><span>Fee: </span><strong>{{ number_format($payment->fee, 2) }} {{ $payment->currency }}</strong></div>
						<hr />
						<div><span>Total Amount: </span><strong>${{ number_format($payment->amount+$payment->fee, 2) }} {{ $payment->currency }}</strong></div>
					  </td>
					</tr>
				  </table>
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</td>
</tr>
@endsection

@section('content2')

@endsection