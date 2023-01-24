
@extends('admin.base')

<?php $name_interno = "Edición de Registro"; ?>

@section('title', $sNameTitle)

@section("title_module",$sNameTitle)

@section("breadcrumb")
	
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route("adminDashBoard") }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ URL::to("admin/".$sNameModule."/index") }}">Usuarios</a></li>
		<li class="breadcrumb-item active">Editando - {{ $oEditUser->name }}</li>
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
	            <form action="{{ URL::to("admin/".$sNameModule."/edit/".$oEditUser->id) }}" method="post" id="form-control">
	            	@csrf
	            	<div class="card-body" >
	            	
	            		<div class="row">
	            			<div class="col-sm-12 col-md">
	            				<div class="form-group">
				                	<label for="email">E-mail</label>
				                	<input type="email" class="form-control" id="email" name="email" value="{{ $oEditUser->email }}" required>
				                </div>
	            			</div>
	            			<div class="col-sm-12 col-md">
	            				<div class="form-group">
				                	<label for="name">Nombre</label>
				                	<input type="text" class="form-control" id="name" name="name" value="{{ $oEditUser->name }}" required>
				                </div>
	            			</div>
	            			<div class="col-sm-12 col-md">
	            				<div class="form-group">
				                	<label for="name">Password</label>
				                	<input type="password" class="form-control" id="password" name="password" value="" minlength="5">
				                </div>
	            			</div>

	            			<div class="col-sm-12 col-md">
                                <label>Tipo de usuario</label>
                                <select class="form-control" name="user_type" value="" require>
                                    <option disabled selected>-- Selecciona una opción --</option>
                                    @foreach ($oUserType as $type)
                                        <option value="{{ $type->id }}" {{ ($type->id == $oEditUser->user_type_id) ? "selected":"" }}>{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
	            		</div>
	            		
	            		<div class="row">
	            			<div class="col">
	            				<h4>Permisos</h4>
	            			</div>
	            		</div>
	            		<div class="row">
	            			@php
	            				$ModulosApps=App\Models\Module::where("deleted",0)->get();
	            				


	            			@endphp
	            			@foreach($ModulosApps as $itemPerm)
	            				<div class="col-sm-12 col-md-2">
	            					<div class="form-check crm-perms">
			                          <input class="form-check-input input-text-perms-checks" id="label-check-{{$itemPerm->id}}" type="checkbox" data-classChilds="modulo-id-{{ $itemPerm->id }}" name="modulos[]" value="{{ $itemPerm->id }}" 
										{{ ($oEditUser->hasPerm($itemPerm->id)) ? "checked":"" }}
			                          />
			                          <label class="form-check-label" for="label-check-{{$itemPerm->id}}">{{ $itemPerm->title }}</label>
			                          <div class="internet_pems">
				                          
				                          @if(trim($itemPerm->json_perms)!="")
					                          @php
					                          
					                          	$permisExtras=get_object_vars(json_decode($itemPerm->json_perms));
					                          	
					                          @endphp

					                          @foreach($permisExtras as $key=>$itemPermsExtras)
					                          		<input class="form-check-input modulo-id-{{ $itemPerm->id }}" id="label-check-extras-{{ $itemPerm->id }}-{{ $key }}" type="checkbox" name="modulos_perm_extras[{{$itemPerm->id}}][]" value="{{ $key }}" 
					                          		{{ ($oEditUser->hasPermIntern($itemPerm->id,$key)) ? "checked":"" }}
													
													/>

						                          <label class="form-check-label" for="label-check-extras-{{$key}}">{{ $itemPermsExtras }}</label>
						                          <br>
					                          @endforeach
				                          @endif
			                          </div>
			                        </div>
	            				</div>	

	            			@endforeach
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




