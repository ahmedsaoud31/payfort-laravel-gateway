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
        <form role="form" method="POST" action="{{ url ('/panel/tour/cost/visit') }}/{{ $tour_cost_visit->id }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input type="hidden" name="id" value="{{ $tour_cost_visit->id }}">
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<label>Name</label>
						<input class="form-control" name="name" value="{{ $tour_cost_visit->name }}" placeholder="Enter price name">
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
							<option value="{{ $city->id }}" {{ ($tour_cost_visit->city_id == $city->id)?'selected':'' }}>{{ $city->name }}</option>
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
						<div class="col-lg-4">
							<div class="form-group{{ $errors->has('adult_price') ? ' has-error' : '' }}">
								<label>Adult price <small>(12 and more)</small></label>
								<input class="form-control" name="adult_price" value="{{ $tour_cost_visit->adult_price }}">
								@if ($errors->has('adult_price'))
									<span class="help-block">{{ $errors->first('adult_price') }}</span>
								@endif
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group{{ $errors->has('child_price') ? ' has-error' : '' }}">
								<label>Child price <small>(2-11)</small></label>
								<input class="form-control" name="child_price" value="{{ $tour_cost_visit->child_price }}">
								@if ($errors->has('child_price'))
									<span class="help-block">{{ $errors->first('child_price') }}</span>
								@endif
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group{{ $errors->has('infant_price') ? ' has-error' : '' }}">
								<label>Infant price <small>(0-2)</small></label>
								<input class="form-control" name="infant_price" value="{{ $tour_cost_visit->infant_price }}">
								@if ($errors->has('infant_price'))
									<span class="help-block">{{ $errors->first('infant_price') }}</span>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
            <button type="submit" class="btn btn-default">Edit this price</button>
        </form>
    </div>
</div>
</div>
@stop