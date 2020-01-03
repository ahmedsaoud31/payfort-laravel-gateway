@extends ('layouts.dashboard')
@section('page_heading', $title)

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
        <form role="form" method="POST" action="{{ url ('/panel/tour/cost/transport') }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<label>Price name</label>
						<input class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter price name">
						@if ($errors->has('name'))
							<span class="help-block">{{ $errors->first('name') }}</span>
						@endif
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group{{ $errors->has('city_id') ? ' has-error' : '' }}">
						<label>Choose city</label>
						<select class="form-control" name="city_id">
							@foreach(\App\Tables\City::where('country_id', 1)->get() as $city)
							<option value="{{ $city->id }}" {{ (old('city_id') == $city->id)?'selected':'' }}>{{ $city->name }}</option>
							@endforeach
						</select>
						@if ($errors->has('city_id'))
							<span class="help-block">{{ $errors->first('city_id') }}</span>
						@endif
					</div>
				</div>
            </div>
			<div class="row">
				<div class="col-lg-8">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group{{ $errors->has('limousine_price') ? ' has-error' : '' }}">
								<label>Limousine price <small>(car)</small></label>
								<input class="form-control" name="limousine_price" value="{{ old('limousine_price') }}">
								@if ($errors->has('limousine_price'))
									<span class="help-block">{{ $errors->first('limousine_price') }}</span>
								@endif
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group{{ $errors->has('hiace_price') ? ' has-error' : '' }}">
								<label>HiAce price <small>(microbus)</small></label>
								<input class="form-control" name="hiace_price" value="{{ old('hiace_price') }}">
								@if ($errors->has('hiace_price'))
									<span class="help-block">{{ $errors->first('hiace_price') }}</span>
								@endif
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group{{ $errors->has('coaster_price') ? ' has-error' : '' }}">
								<label>Coaster price <small>(minibus)</small></label>
								<input class="form-control" name="coaster_price" value="{{ old('coaster_price') }}">
								@if ($errors->has('coaster_price'))
									<span class="help-block">{{ $errors->first('coaster_price') }}</span>
								@endif
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group{{ $errors->has('coach_price') ? ' has-error' : '' }}">
								<label>Coach price <small>(bus)</small></label>
								<input class="form-control" name="coach_price" value="{{ old('coach_price') }}">
								@if ($errors->has('coach_price'))
									<span class="help-block">{{ $errors->first('coach_price') }}</span>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
            <button type="submit" class="btn btn-default">Create new transport price</button>
        </form>
    </div>
</div>
<hr>
<div class="row">
	<div class="panel-group" id="accordion">
		@foreach(\App\Tables\City::where('country_id', 1)->get() as $key=>$city)
		<div class="panel panel-default">
		  <div class="panel-heading">
			<h4 class="panel-title">
			  <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $city->id }}">{{ $city->name }}</a>
			</h4>
		  </div>
		  <div id="collapse{{ $city->id }}" class="panel-collapse collapse {{ ((!(old('city_id') > 1) && $key+1 == 1) || old('city_id') == $city->id)?'in':'' }}">
			<div class="panel-body">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>No.</th>
							<th>Transport details</th>
							<th>Limousine <small>(car)</small></th>
							<th>HiAce <small>(microbus)</small></th>
							<th>Coaster <small>(minibus)</small></th>
							<th>Coach <small>(bus)</small></th>
							<th>Tools</th>
						</tr>
					</thead>
					<tbody>
					@foreach(\App\Tables\TourCostTransport::where('city_id', $city->id)->get() as $key2=>$row)
						<tr>
							<td>{{ $key2+1 }}</td>
							<td>{{ $row->name }}</td>
							<td>{{ number_format($row->limousine_price, 2) }} LE</td>
							<td>{{ number_format($row->hiace_price, 2) }} LE</td>
							<td>{{ number_format($row->coaster_price, 2) }} LE</td>
							<td>{{ number_format($row->coach_price, 2) }} LE</td>
							<td><a href="{{ url ('/panel/tour/cost/transport') }}/{{ $row->id }}/edit">Edit</a></td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		  </div>
		</div>
		@endforeach
	</div>
</div>
</div>
@stop