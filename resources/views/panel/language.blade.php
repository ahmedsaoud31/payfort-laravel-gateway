@extends ('layouts.dashboard')
@section('page_heading', ucfirst($title))

@section('section')
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
		</div>
		<div class="col-lg-6">
			<form role="form" method="POST" action="{{ url ('/panel/language') }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
							<label>Choose language</label>
							<select class="form-control" name="id">
								<option value="es">Spanish</option>
								<option value="it">Italian</option>
								<option value="pt">Portuguese</option>
								<option value="fr">French</option>
								<option value="tr">Turkish</option>
							</select>
							<input type="hidden" name="name" value="{{ old('name')?old('name'):'Spanish' }}">
							@if ($errors->has('id'))
								<span class="help-block">{{ $errors->first('id') }}</span>
							@endif
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-default">Create new language</button>
			</form>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-lg-12">
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th>
						<th>Name</th>
						<th>Code</th>
						<th>Visible</th>
						<th>Tools</th>
					</tr>
				</thead>
				<tbody>
				@foreach(\App\Tables\Language::all() as $key=>$row)
					<tr data-id="{{$row->id}}">
						<td>{{ $key+1 }}</td>
						<td class="name">{{ $row->name }}</td>
						<td class="id">{{ $row->id }}</td>
						@if($row->visible == 1)
							<td role="button" class="visible icon-success"><i class="fa fa-eye"></i></td>
						@else
							<td role="button" class="visible icon-default"><i class="fa fa-eye-slash"></i></td>
						@endif
						<td><a href="#" class="delete">Delete</a></td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(function(){
	$('[name=id]').change(function(){
		$('[name=name]').val($(this).find('option[value='+$(this).val()+']').text());
	});
	
	$('.visible').click(function(){
		var thisElem = $(this);
		var sendData = {"id": thisElem.parent().attr('data-id'),
						"sign": 1,
						"_token": "{{csrf_token()}}"
						};
		if($(this).find('.fa-eye').length > 0){
			sendData.sign = 0;
		}else if($(this).find('.fa-eye-slash').length > 0){
			sendData.sign = 1;
		}else{
			return false;
		}
		$.ajax({
			url: "{{url('/ajax/panel/language/change')}}",
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
					if(sendData.sign == 1){
						thisElem.removeClass('icon-default').addClass('icon-success');
						thisElem.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
					}else{
						thisElem.removeClass('icon-success').addClass('icon-default');
						thisElem.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
					}
				}
				googleBar.stop();
		});
		return false;
	});
	
	$('.delete').click(function(){
		var thisElem = $(this);
		swal({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then(function () {
			var sendData = {"id": thisElem.parent().parent().attr('data-id'),
							"_token": "{{csrf_token()}}",
							"_method": "PATCH"
							};
			$.ajax({
				url: "{{url('/ajax/panel/language/delete')}}",
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
						thisElem.parent().parent().remove();
					}
					googleBar.stop();
			});
		})
		return false;
	});
});
</script>
@stop