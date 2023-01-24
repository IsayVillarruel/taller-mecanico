@if($consult!=null)
	<a href="{{ $consult }}" class="btn btn-sm btn-primary"><i class="fas fa-file-pdf"></i></a>
@endif
@if($download != null)
    <a href="{{ $download }}" class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a>
@endif
@if($send!=null)
	<a href="{{ $send }}" class="btn btn-sm btn-primary"><i class="fas fa-envelope"></i></a>
@endif
