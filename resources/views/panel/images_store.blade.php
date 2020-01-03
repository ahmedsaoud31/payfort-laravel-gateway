@extends ('layouts.dashboard')
@section('page_heading', $title)

@section('section')
<style>
.kv-zoom-cache .file-preview-initial{ display: none;}
textarea{
	min-height: 200px;
}
</style>
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
		@foreach($images = \App\Tables\ImageStore::orderBy('id', 'DESC')->paginate(40) as $image)
		
		@endforeach
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-12">
					<div class="kv-main">
						<div class="form-group">
							<input id="images" name="image" type="file" multiple class="file" data-overwrite-initial="false" data-min-file-count="2">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
		{{$images->links()}}
		</div>
	</div>
</div>
<script type="text/javascript" src="{{ asset('libs/fileinput/js/fileinput.js') }}"></script>
<script>
	$("#images").fileinput({
		uploadUrl: '{{ url("/ajax/panel/images/store/upload") }}',
		allowedFileExtensions : ['jpg', 'png','gif', 'jpeg'],
		overwriteInitial: false,
		maxFileSize: 10000,
		maxFilesNum: 10,
		append: true,
		initialPreviewAsData: true,
		initialPreview: [
			@foreach($images as $row)
			"{{ url('/images/store') }}/small/{{ $row->name }}",
			@endforeach
		],
		initialPreviewConfig: [
			@foreach($images as $row)
			{caption: "{{ $row->name }}", width: "90px", url: "{{ url('/ajax/panel/images/store/delete') }}", key: "{{ $row->id }}", extra:{ id: "{{ $row->id }}", _token: "{{csrf_token()}}"}},
			@endforeach
		],
		uploadExtraData: {_token:"{{csrf_token()}}"},
		//maxFileCount: 1,
		allowedFileTypes: ['image'],
		slugCallback: function(filename) {
			return filename.replace('(', '_').replace(']', '_');
		}
	}).on('filesorted', function(e, params) {
		//console.log('File sorted params', params);
	}).on('fileuploaded', function(e, params) {
		//console.log('File uploaded params', params);
	});
</script>
@stop