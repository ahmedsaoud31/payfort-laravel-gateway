@extends ('layouts.dashboard')
@section('page_heading', ucfirst($type))

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
			<form role="form" method="POST" action="{{ url ('/panel/tour/extension?type='.$type) }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group{{ $errors->has('line') ? ' has-error' : '' }}">
							<label>Add new {{$type}} extension</label>
							<input class="form-control" name="line" value="{{ old('line') }}" placeholder="Enter an extension">
							@if ($errors->has('line'))
								<span class="help-block">{{ $errors->first('line') }}</span>
							@endif
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-default">Create new {{$type}} extension</button>
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
						<th>Line</th>
						<th>Tools</th>
					</tr>
				</thead>
				<tbody>
				@foreach($extension = \App\Tables\Extension::where('type', $type)->paginate(20) as $key=>$row)
					<tr data-id="{{$row->id}}">
						<td>{{ $key+1 }}</td>
						<td class="line" contenteditable="true" data-line="{{ $row->line }}">{{ $row->line }}</td>
						<td><a href="#" class="delete">Delete</a></td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="text-center">{{ $extension->appends(['type' => $type])->links() }}</div>
		</div>
	</div>
</div>
<script>
$(function(){
	$('#to_date').datepicker({});
	$('#from_date').datepicker({});
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
				url: "{{url('/ajax/panel/tour/extension/delete')}}",
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
	$(".line").focusout(function () {
		var thisElem = $(this);
		if($(this).text() != $(this).attr('data-line')){
			var sendData = {"id": thisElem.parent().attr('data-id'),
							"line": thisElem.text(),
							"_token": "{{csrf_token()}}"
							};
			$.ajax({
				url: "{{url('/ajax/panel/tour/extension/edit')}}",
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