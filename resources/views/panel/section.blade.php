@extends('layouts.dashboard')
@section('page_heading','Sections')

@section('section')
<div class="col-sm-12">
<div class="row">
	<div class="col-sm-12">
		@section ('table_panel_title','All')
		@section ('table_panel_body')
		@include('panel.tables.section', array('class'=>''))
		@endsection
		@include('widgets.panel', array('header'=>true, 'as'=>'table'))
	</div>
</div>
</div>
@stop