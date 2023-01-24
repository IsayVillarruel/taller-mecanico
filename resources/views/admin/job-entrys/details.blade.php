
@extends('admin.base')

<?php $name_interno = "Datos de la Entrada de Trabajo"; ?>

@section('title', $sNameTitle)

@section("title_module",$sNameTitle)

@section("breadcrumb")
	
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route("adminDashBoard") }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ URL::to("admin/job-entrys/index") }}">Entradas de Trabajo</a></li>
		<li class="breadcrumb-item active">{{ $oJob->Car->brand." ".$oJob->Car->version." - ".$oJob->car_plates }}</li>
	</ol>

@endsection

@section("primary_content")

    <!-- Visualizacion de la info del documento -->
    <div class="container-fluid">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">{{ $name_interno }}</h3>
                    </div>
                </div>
               
                @csrf
                <div class="card-body">
                
                    <div class="row">
                        <div class="col-sm-12 col-md">
                            <div class="form-group">
                                <label for="customer_name">Nombre del Cliente</label>
                                <p class="font-weight-bold">{{ $oJob->customer_name }}</p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md">
                            <div class="form-group">
                                <label for="customer_number">Telefono del Cliente</label>
                                <p class="font-weight-bold">{{ $oJob->customer_number }}</p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md">
                            <div class="form-group">
                                <label for="customer_email">Email del Cliente</label>
                                <p class="font-weight-bold">{{ $oJob->customer_email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md">
                            <div class="form-group">
                                <label for="customer_name">Marca</label>
                                <p class="font-weight-bold">{{ $oJob->Car->brand }}</p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md">
                            <div class="form-group">
                                <label for="customer_name">Versión</label>
                                <p class="font-weight-bold">{{ $oJob->Car->version }}</p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md">
                            <div class="form-group">
                                <label for="customer_number">Modelo</label>
                                <p class="font-weight-bold">{{ $oJob->car_model }}</p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md">
                            <div class="form-group">
                                <label for="customer_email">Placas</label>
                                <p class="font-weight-bold">{{ $oJob->car_plates }}</p>
                            </div>
                        </div>
                    </div>
            </div> 
        </div>
    </div>  <!-- FIN DE LA VISUALIZACION DE LA ENTRADA DE TRABAJO --->

    <div class="container-fluid">
		<div class="col">
			<div class="card">
				<div class="card-header">
	            	<div class="d-flex justify-content-between">
	                	<h3 class="card-title">Reparaciones </h3>
                        <div>
                        </div>
	            	</div>
	            </div>
	            <div class="card-body" >
	            	<table id="rows-module" class="table table-bordered table-striped">
	            		<thead>
	            			<tr>
	            				<th>Nombre de Servicio</th>
	            				<th>Precio</th>
                                <th>Responsable</th>
	            				<th>Fecha</th>
	            			</tr>
	            		</thead>
	            		<tbody>
	            			
	            		</tbody>
	            		<tfoot>
	            			<tr>
	            				<th>Nombre de Servicio</th>
	            				<th>Precio</th>
                                <th>Responsable</th>
	            				<th>Fecha</th>
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
                        "url": "{{ route('detailsRows', ['id' => $oJob->id]) }}",
	                     "dataType": "json",
	                     "type": "POST",
	                     "data":{ _token: "{{csrf_token()}}"}
	                   },
	            "columns": [
                    { "data": "service_id" },
                    { "data": "price" },
                    { "data": "user_id" },
	                { "data": "created_at" },
	            ],	 
		    	"paging": true,
		    	"lengthChange": true,
		    	"searching": false,
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



