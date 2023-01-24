
@extends('admin.base')

@section('title', $sNameTitle)

@section("title_module",$sNameTitle)

@section("breadcrumb")
	
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route("adminDashBoard") }}">Home</a></li>
		<li class="breadcrumb-item active">{{ $sNameTitle }}</li>
	</ol>

@endsection


@section("primary_content")

	<div class="container-fluid">
		<div class="col">
			<div class="card">
				<div class="card-header">
	            	<div class="d-flex justify-content-between">
	                	<h3 class="card-title">Lista de registros</h3>
	                	@if(Auth::User()->hasPermIntern($iModuleId,"new"))
	                		<a class="btn btn-primary" href="{{ URL::to("admin/".$sNameModule."/add") }}"><i class="fas fa-plus-square"></i> Nuevo</a>
	                	@endif
	            	</div>
	            </div>
	            <div class="card-body" >
	            	<table id="rows-module" class="table table-bordered table-striped">
	            		<thead>
	            			<tr>
	            				<th>ID</th>
	            				<th>Nombre</th>
	            				<th>Correo</th>
                                <th>Tipo de Usuario</th>
	            				<th></th>
	            				
	            			</tr>
	            		</thead>
	            		<tbody>
	            			
	            		</tbody>
	            		<tfoot>
	            			<tr>
	            				<th>ID</th>
	            				<th>Nombre</th>
	            				<th>Correo</th>
                                <th>Tipo de Usuario</th>
	            				<th></th>
	            			</tr>
	            		</tfoot>
	            	</table>
	            	
	            </div>
	            <div class="card-footer">
	            	
	            </div>
				
			</div>
			
		</div>		
	</div>

@endsection

@section("styles")
	
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/datatables-responsive/css/responsive.bootstrap4.min.css") }}">

@endsection

@section("scripts")



	
	<script src="{{ URL::asset("assets/admin/js/plugins/datatables/jquery.dataTables.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/datatables-responsive/js/dataTables.responsive.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js") }}"></script>

	<script>
		$(function(){

			$('#rows-module').DataTable({
				"processing": true,
	            "serverSide": true,
	            "ajax":{
	                     "url": "{{ route($sNameModule.'Rows') }}",
	                     "dataType": "json",
	                     "type": "POST",
	                     "data":{ _token: "{{csrf_token()}}"}
	                   },
	            "columns": [
	                { "data": "id" },
	                { "data": "name" },
	                { "data": "email" },
	                { "data": "type_user_id" },
                    
	                { "data": "options", className: "text-right"  }
	            ],	 
		    	"paging": true,
		    	"lengthChange": true,
		    	"searching": true,
		    	"ordering": true,
		    	"info": true,
		    	"autoWidth": false,
		    	"responsive": true,
		    	"language": {
		    		"thousands":",",
			    	"lengthMenu": "Mostrar _MENU_ registros por pagina",
			    	"zeroRecords": "No Fue posible obtener registros",
			    	"info": "Mostrando pagina _PAGE_ de _PAGES_",
			    	"infoEmpty": "No records available",
			    	"infoFiltered": "(Filtrados de _MAX_ total de registros)",
			    	"loadingRecords": "Cargando...",
			    	"search":"Buscar:",
			    	"paginate": {
				        "first":      "Primera",
				        "last":       "Ultima",
				        "next":       "Siguiente",
				        "previous":   "Anterior"
				    },
				    "aria": {
				        "sortAscending":  ": activate to sort column ascending",
				        "sortDescending": ": activate to sort column descending"
				    }
			    },
		    }).on("draw",function(){
		    	urlDeleteButtonModule();
		    });

		})

	</script>
	

@endsection




