
@extends('admin.base')

<?php $name_interno = "Enviar Nota por E-Mail"; ?>

@section('title', $sNameTitle)

@section("title_module",$sNameTitle)

@section("breadcrumb")
	
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route("adminDashBoard") }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ URL::to("admin/".$sNameModule."/index") }}">Listado de Notas</a></li>
		<li class="breadcrumb-item active">{{ $name_interno }}</li>
	</ol>

@endsection


@section("primary_content")

	<div class="container-fluid">
		<div class="col">
			<div class="card">
				<div class="card-header">
	            	<div class="d-flex justify-content-between">
	                	<h3 class="card-title">{{ $name_interno }}</h3>
	                	
	            	</div>
	            </div>
	            <form action="{{ URL::to("admin/".$sNameModule."/".$path."/send/".$noteNumber) }}" method="post" id="form-control">
	            	@csrf
	            	<div class="card-body" >
	            		<div class="row">
	            			<div class="col-sm-12 col-md">
	            				<div class="form-group">
				                	<label for="email">E-Mail</label>
				                	<input type="email" class="form-control" id="email" name="email" required>
				                </div>
	            			</div>
	            		</div>
		            </div>
		            <div class="card-footer">
		            	<div class="row">
		            		<div class="col-6 offset-6 text-right">
		            			<button type="submit" class="btn btn-primary">Guardar</button>
		            			<button type="button" class="btn btn-primary btn-cancel-button-redirect" data-url="{{ URL::to("admin/".$sNameModule."/index") }}">Cancelar</button>
		            		</div>
		            	</div>
		            </div>
	            </form>
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

			activatePemsExtras();

			$("#form-control").submit(function(event)
	          {
	            event.preventDefault();
	            $.ajax({
	              url:$("#form-control").attr('action'),
	              method:'post',
	              async :true,
	              data:$("#form-control").serialize(),
	              beforeSend:function(){
	              	addLoader();
	              }
	            }).done(function(data){
	            	removeLoader();
	              if(data.error=="success")
	              {
	                alertify.set('notifier','position', 'top-right');
	                alertify.success("Registro Guardado de manera Exitosa");

	                setTimeout(function(){
	                  document.location="{{ URL::to("admin/".$sNameModule."/index") }}";
	                }, 1000);

	                
	              }
	              else if(data.error=="error")
	              {
	                alertify.set('notifier','position', 'top-right');
	                alertify.error(data.mensaje);
	              }
	              else
	              {
	                alertify.set('notifier','position', 'top-right');
	                alertify.error(data.mensaje);
	              }
	              
	             

	            }).fail(function(data){
	            	removeLoader();
	              alertify.error('No fue posible guardar el registro intente de nuevo');
	            });

	          });

			

		});
	</script>
	

@endsection




