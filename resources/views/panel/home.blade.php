@extends('layouts.dashboard')
@section('page_heading','Dashboard')
@section('section')        
<!-- /.row -->
<div class="col-sm-12">
	<div class="row">
		<div class="col-lg-6 col-md-6">
			<div class="panel panel-yellow">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-shopping-cart fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">{{ \App\Tables\Payment::where('user_id', auth()->user()->id)->where('status', 'pending')->count() }}</div>
							<div>Pending Payments</div>
						</div>
					</div>
				</div>
				<a href="{{ url('/') }}/dashboard/payments">
					<div class="panel-footer">
						<span class="pull-left">View Details</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-6 col-md-6">
			<div class="panel panel-green">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-shopping-cart fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">{{ \App\Tables\Payment::where('user_id', auth()->user()->id)->where('status', 'success')->count() }}</div>
							<div>Success Payments</div>
						</div>
					</div>
				</div>
				<a href="{{ url('/') }}/dashboard/payments">
					<div class="panel-footer">
						<span class="pull-left">View Details</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			
		</div>
	</div>
</div>
<!-- /.col-lg-4 -->
@stop
