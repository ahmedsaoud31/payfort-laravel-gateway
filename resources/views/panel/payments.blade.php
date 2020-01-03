@extends ('layouts.dashboard')
@section('page_heading', $title)

@section('section')
<style>
.copy-link span{
	font-size: 28px;
	color: #AAA;
	text-decoration: none;
}
.copy-link span:hover{
	color: #4285f4;
	cursor: pointer;
}
#copyToClipboard{
	display: none;
	height: 0px;
}
</style>
<textarea id="copyToClipboard"></textarea>
<div class="col-sm-12">
<div class="row">
	<div class="col-lg-12">
		<div class="flash-message">
		@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		  @if(Session::has('alert-' . $msg))
		  <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		  @endif
		@endforeach
		</div> <!-- end .flash-message -->
		<div>
		  @if(Session::has('link'))
		  Copy this link: <a href="{{ Session::get('link') }}">{{ Session::get('link') }}</a>
			<span class="copy-link">
				<span data-href="{{ Session::get('link') }}" class="fa fa-files-o" title="Copy"></span> <i></i>
			</span>
		  @endif
		</div>
		<br />
	</div>
    <div class="col-lg-12">
        <form role="form" method="POST" action="{{ url ('/dashboard/payments/add') }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="row">
				<div class="col-lg-8">
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<label>Payment name</label>
						<input class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter payment name">
						@if ($errors->has('name'))
							<span class="help-block">{{ $errors->first('name') }}</span>
						@endif
					</div>
				</div>
				<div class="col-lg-8">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
								<label>Amount</label>
								<input class="form-control" name="amount" value="{{ old('amount') }}" placeholder="Enter amount">
								@if ($errors->has('amount'))
									<span class="help-block">{{ $errors->first('amount') }}</span>
								@endif
							</div>
							<div>
								<span>Fee: <strong class="fee"></strong></span>
								<span>Total amount: <strong class="total-amount"></strong></span>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }}">
								<label>Choose currency</label>
								<select class="form-control" name="currency">
									<option value="USD">USD</option>
									<option value="EGP">EGP</option>
								</select>
								@if ($errors->has('currency'))
									<span class="help-block">{{ $errors->first('currency') }}</span>
								@endif
							</div>
						</div>
						<div class="col-lg-12">
							<div class="{{ $errors->has('without_fee') ? ' has-error' : '' }}">
								<input name="without_fee" type="checkbox"> Without fee
								@if ($errors->has('without_fee'))
									<span class="help-block">{{ $errors->first('without_fee') }}</span>
								@endif
							</div>
						</div>
					</div>
				</div>
            </div>
			<br />
			<div class="row">
				<div class="col-lg-8">
					<div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
						<label>Payment Details</label>
						<textarea class="form-control" name="comment" placeholder="Enter comment details">{{ old('comment') }}</textarea>
						@if ($errors->has('comment'))
							<span class="help-block">{{ $errors->first('comment') }}</span>
						@endif
					</div>
				</div>
			</div>
            <button type="submit" class="btn btn-default">Create payment link</button>
        </form>
    </div>
</div>
<hr>
<div class="row">
	<div class="panel-group" id="accordion">
		@if(auth()->user()->id == 1)
			@php($payments = \App\Tables\Payment::orderBy('id', 'DESC')->paginate(20))
		@else
			@php($payments = \App\Tables\Payment::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->paginate(20))
		@endif
		<div class="text-center">{{ $payments->links() }}</div>
		<table class="table table-condensed">
			<thead>
				<tr>
					<th class="text-center">Copy Link</th>
					<th style="min-width: 250px;">Info.</th>
					<th style="min-width: 200px;">Amount</th>
					<th>Details</th>
					<th>Tools</th>
				</tr>
			</thead>
			<tbody>
				@foreach($payments as $payment)
				<tr>
					<td class="text-center copy-link">
						<span data-href="{{ url('/') }}/pay?payment_id={{ $payment->payment_id }}" class="fa fa-files-o" title="Copy"></span>
						<div><i></i></div>
					</td>
					<td>
						<div>Refrance: <a href="{{ url('/') }}/pay?payment_id={{ $payment->payment_id }}">#{{ $payment->id }}</a></div>
						<div>Payment ID: {{ $payment->payment_id }}</div>
						<div>Status:
							@if($payment->status == 'success')
								<span class="text-success"><strong>Success</strong></span>
							@else
								<span class="text-warning"><strong>Pending</strong></span>
							@endif
						</div>
						@if(auth()->user()->email == 'info@reflectionstravel.net')
						<div>Merchant:
							<small>({{ $payment->user->email }})</small>
						</div>
						@endif
					</td>
					<td>
						<table>
							<tr><td>Net Amount:</td><td><strong>{{ number_format($payment->amount, 2) }}</strong></td></tr>
							<tr style="border-bottom: 1px solid #DDD;"><td>Fee:</td><td><strong>{{ number_format($payment->fee, 2) }}</strong></td></tr>
							<tr><td>Total Amount:</td><td><strong class="text-success">{{ number_format($payment->amount+$payment->fee, 2) }} {{$payment->currency}}</strong></td></tr>
							@if(auth()->user()->id == 1)
							<tr><td>Net Amount:</td><td><strong>{{ number_format($payment->amount, 2) }} {{$payment->currency}}</strong></td></tr>
							@endif
						</table>
					</td>
					<td>
						<div>Name: {{ $payment->name }}</div>
						@if(auth()->user()->email == 'info@reflectionstravel.net')
						<div>Email:
							<small>{{ $payment->email }}</small>
						</div>
						@endif
						<div>Comment: {{ $payment->comment }}</div>
						
					</td>
					<td>
						@if($payment->status != 'success')
						<a href="{{ url('/') }}/dashboard/payments/{{ $payment->id }}/edit">Edit</a> |
						<a class="delete" href="{{ url('/') }}/dashboard/payments/{{ $payment->id }}/delete" data-id="{{ $payment->id }}">Delete
							<form action="{{ url('/') }}/dashboard/payments/{{ $payment->id }}/delete" method="POST">
								<input type="hidden" name="delete" value="" /> 
							</form>
						</a>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<div class="text-center">{{ $payments->links() }}</div>
	</div>
</div>
</div>
@stop
@section('script')
<script>
$(function(){
	$('.delete').click(function(){
		var id = $(this).attr('data-id');
		var _this = $(this);
		swal({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then(function () {
			var sendData = {"_token": "{{csrf_token()}}",
							"ajax": true};
			$.ajax({
				url: "{{ url('/dashboard/payments') }}"+"/"+id+"/delete",
				type: "POST",
				data: sendData,
				beforeSend: function ( xhr ) {
					googleBar.start();
				}
				}).done(function ( data ) {
					if(typeof data.error !== 'undefined'){
						swal(
						  'Oops...',
						  data.error,
						  'error'
						)
					}else{
						_this.parent().parent().fadeOut();
					}
					googleBar.stop();
			});
			return false;
		})
		return false;
	});
	$('[name=amount]').bind( "change input", function() {
		changeTotalAmount();
	});
	$('[name=without_fee]').bind( "change", function() {
		changeTotalAmount();
	});
	$('.copy-link').click(function(){
		$('#copyToClipboard').val($(this).find('span').attr('data-href')).show();
		$('#copyToClipboard')[0].select();
		document.execCommand('copy');
		$('#copyToClipboard').hide();
		$(this).find('i').text('Copied').show().fadeOut(1000);
		return false;
	});
	
	function changeTotalAmount(){
		var percentage_fee = parseFloat({{ auth()->user()->percentage_fee }});
		var fixed_fee = parseFloat({{ auth()->user()->fixed_fee }});
		var amount = parseFloat($('[name=amount]').val());
		var fee = Math.ceil(((percentage_fee/100)*amount))+fixed_fee;
		if($('[name=without_fee]').is(':checked')){
			fee = 0;
		}
		$('.fee').text(fee.formatMoney(2, '.', ','));
		$('.total-amount').text((amount+fee).formatMoney(2, '.', ','));
	}
});
Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
</script>
@stop