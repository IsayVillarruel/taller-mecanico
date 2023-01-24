
@extends('admin.base')

<?php $name_interno = "Edición de Automóviles"; ?>

@section('title', $sNameTitle)

@section("title_module",$sNameTitle)

@section("breadcrumb")
	
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route("adminDashBoard") }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ URL::to("admin/".$sNameModule."/index") }}">Automóviles</a></li>
		<li class="breadcrumb-item active">Editando - {{ $oCar->name }}</li>
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
	            <form action="{{ URL::to("admin/".$sNameModule."/edit/".$oCar->id) }}" method="post" id="form-control">
	            	@csrf
	            	<div class="card-body" >
	            		<div class="row">
	            			<div class="col-sm-12 col-md">
	            				<div class="form-group">
				                	<label for="brand">Marca</label>
				                	<input type="text" class="form-control" id="brand" name="brand" value="{{ $oCar->brand }}" required>
				                </div>
	            			</div>

	            			<div class="col-sm-12 col-md">
	            				<div class="form-group">
				                	<label for="version">Versión</label>
				                	<input type="text" class="form-control" id="version" name="version" value="{{ $oCar->version }}" required>
				                </div>
	            			</div>

                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select class="form-control" name="active" required>
                                        @if($oCar->active === 1)
                                            <option value="1" selected>Activo</option>
                                            <option value="0">Inactivo</option>
                                        @else
                                            <option value="1">Activo</option>
                                            <option value="0" selected>Inactivo</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <input type="text" name="id" value="{{ $oCar->id }}" hidden>
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




