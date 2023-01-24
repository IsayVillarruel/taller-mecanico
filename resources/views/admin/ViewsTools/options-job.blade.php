@if($note != null)
    <a href="{{ $note }}" class="btn btn-sm btn-primary"><i class="fas fa-file-invoice"></i></a>
@endif
@if($repair != null)
    <a href="{{ $repair }}" class="btn btn-sm btn-primary"><i class="fas fa-car-mechanic"></i></a>
@endif
@if($details != null)
    <a href="{{ $details }}" class="btn btn-sm btn-primary"><i class="fas fa-info-circle"></i></a>
@endif
@if($edit!=null)
    <a href="{{ $edit }}" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
@endif
@if($delete!=null)
    <a href="{{ $delete }}" class="btn btn-sm btn-primary btn-primary-delete-module-select"><i class="fas fa-trash"></i> </a>
@endif
