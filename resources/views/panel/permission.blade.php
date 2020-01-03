@extends('layouts.dashboard')
@section('page_heading','Permissions')

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
		<div class="col-lg-12">
			<form role="form" method="POST" action="{{ url ('/panel/auth/permission') }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<label>Add new permission</label>
							<input class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter a permission">
							@if ($errors->has('name'))
								<span class="help-block">{{ $errors->first('name') }}</span>
							@endif
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-default">Create new permission</button>
			</form>
		</div>
	<hr>
		<div class="col-sm-12">
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th>
						<th>Name</th>
						<th>Description</th>
						<th>Tools</th>
					</tr>
				</thead>
				<tbody>
				@foreach($permission = \App\Tables\Permission::orderBy('name', 'ASC')->paginate(20) as $key=>$row)
					<tr data-id="{{$row->id}}">
						<td>{{ $key+1 }}</td>
						<td class="name" data-name="{{ $row->name }}">{{ $row->name }}</td>
						<td class="desc" contenteditable="true" data-desc="{{ $row->desc }}">{{ $row->desc }}</td>
						<td><a href="#" class="delete">Delete</a></td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="text-center">{{ $permission->links() }}</div>
		</div>
	</div>
</div>
<script>
$(function(){
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
				url: "{{url('/ajax/panel/auth/permission/delete')}}",
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
	$(".name, .desc").focusout(function () {
		var thisElem = $(this).parent();
		var sendData = {"id": thisElem.attr('data-id'),
						"name": thisElem.find('.name').text(),
						"desc": thisElem.find('.desc').text(),
						"_token": "{{csrf_token()}}"
						};
		if(thisElem.find('.name').attr('data-name') != sendData.name
			|| thisElem.find('.desc').attr('data-desc') != sendData.desc){
			$.ajax({
				url: "{{url('/ajax/panel/auth/permission/edit')}}",
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
						thisElem.attr('data-line', sendData.line);
					}
					googleBar.stop();
			});
		}
	});
});
</script>
@stop