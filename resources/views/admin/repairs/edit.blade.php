@extends('admin.base')

<?php $name_interno = "Editando Servicio"; ?>

@section('title', $sNameTitle)

@section("title_module",$sNameTitle)

@section("breadcrumb")
	
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route("adminDashBoard") }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ URL::to("admin/job-entrys/index") }}">Entradas de Trabajo</a></li>
        <li class="breadcrumb-item"><a href="{{ URL::to("admin/job-entrys/workshop/{$oRepair->job_id}") }}">{{ $oJob->Car->brand." ".$oJob->Car->version." - ".$oJob->car_plates }}</a></li>
		<li class="breadcrumb-item active">{{ $name_interno }}</li>
	</ol>

@endsection

@section("primary_content")
    <!-- INICIO DEL FORMULARIO PARA HACER COMENTARIO --->
    <div class="container-fluid">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">{{ $name_interno }}</h3>

                    </div>
                </div>
                <form action="{{ URL::to("admin/job-entrys/{$oRepair->job_id}/{$sNameModule}/edit/{$oRepair->id}") }}" method="post" id="form-control" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <label>Automóviles</label>
                                <select class="form-control" name="service_id" value="" require>
                                    <option disabled selected>-- Selecciona una opción --</option>
                                    @foreach ($oServices as $service)
                                        <option value="{{ $service->id }}" {{ ($service->id == $oRepair->service_id) ? "selected":"" }} required>{{ $service->name.' - '.$service->description }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="price">Precio del servicio</label>
                                    <input name="price" type="number" step="0.01" class="form-control" value="{{ $oRepair->price }}" required>        
                                </div>  
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="attached_file">Piezas Viejas</label>
                                    <input type="file" name="change_parts" id="change_parts" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="attached_file">Nuevas Piezas</label>
                                    <input type="file" name="new_parts" id="new_parts" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">

                            <div class="col-6 offset-6 text-right">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <button type="button" class="btn btn-primary btn-cancel-button-redirect" data-url="{{ URL::to("admin/job-entrys/".$oRepair->job_id."") }}">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- FIN DEL FORMULARIO PARA HACER COMENTARIO --->

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
        $("#form-control").submit(function(event)
          {
            event.preventDefault();

            var formData = new FormData(document.getElementById("form-control"));


            $.ajax({
              url:$("#form-control").attr('action'),
              method:'post',
              async :true,
              processData:false,
              mimeType:"multipart/form-data",
              contentType: false,
              cache: false,
              data:formData,
              beforeSend:function(){
                addLoader();
              }
            }).done(function(data){
                removeLoader();
                
                data=JSON.parse(data)
              if(data.error=="success")
              {
                alertify.set('notifier','position', 'top-right');
                alertify.success("Registro Guardado de manera Exitosa");

                setTimeout(function(){
                    document.location="{{ URL::to("admin/job-entrys/workshop/{$oRepair->job_id}") }}";
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