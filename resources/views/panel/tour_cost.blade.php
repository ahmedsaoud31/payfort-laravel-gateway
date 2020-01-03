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
        <form role="form" method="POST" action="{{ url ('/panel/tour/cost') }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="tour_id" value="{{ $tour->id }}">
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group{{ $errors->has('transport_costs') ? ' has-error' : '' }}">
						<label>Tour  transport costs</label>
						<select data-placeholder="Choose..." name="transport_costs[]" multiple class="form-control chosen-select">
							@foreach(\App\Tables\TourCostTransport::where('city_id', $tour->city->id)->get() as $row)
							<option value="{{$row->id}}" {{ $tour->hasTransportCost($row->id)?'selected':'' }}>{{$row->name}}</option>
							@endforeach
						</select>
						@if ($errors->has('transport_costs'))
							<span class="help-block">{{ $errors->first('transport_costs') }}</span>
						@endif
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group{{ $errors->has('visit_costs') ? ' has-error' : '' }}">
						<label>Tour  visit costs</label>
						<select data-placeholder="Choose..." name="visit_costs[]" multiple class="form-control chosen-select">
							@foreach(\App\Tables\TourCostVisit::where('city_id', $tour->city->id)->get() as $row)
							<option value="{{$row->id}}" {{ $tour->hasVisitCost($row->id)?'selected':'' }}>{{$row->name}}</option>
							@endforeach
						</select>
						@if ($errors->has('visit_costs'))
							<span class="help-block">{{ $errors->first('visit_costs') }}</span>
						@endif
					</div>
				</div>
			</div>
            <button type="submit" class="btn btn-default">Edit costs</button>
        </form>
    </div>
</div>
</div>
<script>
$(function(){
	
});
jQuery(function($){
	$(".chosen-select").chosen();
	$(".chosen-container").css({"width": "100%"});
});
</script>
@stop