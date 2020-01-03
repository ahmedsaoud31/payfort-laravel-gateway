@extends('layouts.dashboard')
@section('page_heading','Role Permissions')

@section('section')
<div class="col-sm-12">
	<div class="row">
		<div class="col-sm-12">
			@foreach($role = App\Tables\Role::paginate(1) as $row)
			<table class="table">
				<thead>
					<tr class="info"><th colspan="4">{{ $row->name }} Role</th></tr>
					<tr>
						<th>Permission ID</th>
						<th>Name</th>
						<th>Discription</th>
						<th>Has Permission</th>
					</tr>
				</thead>
				<tbody>
					@foreach(App\Tables\Permission::all() as $row2)
					<tr>
						<td>{{ $row2->id }}</td>
						<td>{{ $row2->name }}</td>
						<td>{{ $row2->desc }}</td>
						<td>
							@if($row2->assignTo($row->id) > 0)
							<button type="button" class="btn btn-success btn-circle" data-permission_id="{{$row2->id}}" data-role_id="{{$row->id}}"><i class="fa fa-check"></i></button>
							@else
							<button type="button" class="btn btn-danger btn-circle" data-permission_id="{{$row2->id}}" data-role_id="{{$row->id}}"><i class="fa fa-times"></i></button>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endforeach
			<div class="text-center">{{ $role->links() }}</div>
		</div>
	</div>
</div>
<script>
$(function(){
	$('.btn-circle').click(function(){
		var thisElem = $(this);
		var sendData = {"permission_id": thisElem.attr('data-permission_id'),
						"role_id":  thisElem.attr('data-role_id'),
						"sign": 1,
						"_token": "{{csrf_token()}}"
						};
		if($(this).find('.fa-check').length > 0){
			sendData.sign = 0;
		}else if($(this).find('.fa-times').length > 0){
			sendData.sign = 1;
		}else{
			return false;
		}
		$.ajax({
			url: "{{url('/ajax/panel/auth/role/permission/change')}}",
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
						thisElem.removeClass('btn-danger').addClass('btn-success');
						thisElem.find('i').removeClass('fa-times').addClass('fa-check');
					}else{
						thisElem.removeClass('btn-success').addClass('btn-danger');
						thisElem.find('i').removeClass('fa-check').addClass('fa-times');
					}
				}
				googleBar.stop();
		});
		return false;
	});
});
</script>
@stop