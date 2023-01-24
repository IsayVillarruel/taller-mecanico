
@extends('admin.base')

@section('title', 'Dash Board')

@section("title_module","DashBoard")

@section("breadcrumb")
	
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="#">Panel Principal</a></li>
		<li class="breadcrumb-item active">Dashboard v1</li>
	</ol>

@endsection


@section("primary_content")

	<div class="container-fluid">
		<div class="col">
			<div class="card">
				<div class="card-header border-0">
	            	<div class="d-flex justify-content-between">
	                	
	                	
	            	</div>
	            </div>
	            <div class="card-body">
	            	
	            	
	            </div>
	            <div class="card-footer">
	            	
	            </div>
				
			</div>
			
		</div>		
	</div>

@endsection


@section("styles") 
	
	
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/fullcalendar/main.min.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/fullcalendar-daygrid/main.min.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/fullcalendar-timegrid/main.min.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/fullcalendar-bootstrap/main.min.css") }}">
  	

@endsection

@section("scripts")
	
	
	<script src="{{ URL::asset("assets/admin/js/plugins/moment/moment.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/fullcalendar/main.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/fullcalendar/locales/es.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/fullcalendar-daygrid/main.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/fullcalendar-timegrid/main.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/fullcalendar-interaction/main.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/fullcalendar-bootstrap/main.min.js") }}"></script>

	<script>
		
		



	    $(document).ready(function(){

	    	

	    });



	</script>
	



@endsection




