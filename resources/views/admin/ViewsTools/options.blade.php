
@if($edit!=null)
	<a href="{{ $edit }}" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
@endif
@if($delete!=null)
	<a href="{{ $delete }}" class="btn btn-sm btn-primary btn-primary-delete-module-select"><i class="fas fa-trash"></i> </a>
@endif
