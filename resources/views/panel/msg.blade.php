@extends('layouts.dashboard')
@section('page_heading', 'Control Panel message')
@section('section')
	<div class="flash-message">
	  @if(isset($alert_danger))
	  <p class="alert alert-danger">{{ $alert_danger }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	  @elseif(isset($alert_warning))
	  <p class="alert alert-success">{{ $alert_warning }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	  @elseif(isset($alert_success))
	  <p class="alert alert-success">{{ $alert_success }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	  @elseif(isset($alert_info))
	  <p class="alert alert-info">{{ $alert_info }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	  @endif
	</div>
@stop
