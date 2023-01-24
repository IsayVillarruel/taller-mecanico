@switch($status)
	@case(1)
		<button class="btn btn-sm btn-info">{{ $name }}</button>
		@break
	@case(2)
		<button class="btn btn-sm btn-warning">{{ $name }}</button>
		@break
	@case(3)
		<button class="btn btn-sm btn-danger">{{ $name }}</button>
		@break
    @case(4)
		<button class="btn btn-sm btn-success">{{ $name }}</button>
		@break
@endswitch