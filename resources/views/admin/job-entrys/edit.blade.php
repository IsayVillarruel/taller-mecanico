@extends('admin.base')

<?php $name_interno = "Editar entrada de trabajo"; ?>

@section('title', $sNameTitle)

@section("title_module",$sNameTitle)

@section("breadcrumb")

<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route("adminDashBoard") }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ URL::to("admin/".$sNameModule."/index") }}">Entrada de Trabajo</a></li>
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
                <form action="{{ URL::to("admin/".$sNameModule."/edit/{$oJob->id}") }}" method="post" id="form-control" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        
                        <h4 class="text-primary">Datos Generales</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="customer_name">Nombre del Cliente</label>
                                    <input type="text" value="{{ $oJob->customer_name }}" class="form-control" id="customer_name" name="customer_name" required>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="customer_number">Telefono del Cliente</label>
                                    <input type="text" value="{{ $oJob->customer_number }}" class="form-control" id="customer_number" name="customer_number" required>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="customer_email">Email del Cliente</label>
                                    <input type="text" value="{{ $oJob->customer_email }}" class="form-control" id="customer_email" name="customer_email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <label>Automóviles</label>
                                <select class="form-control" name="car_id" value="" require>
                                    <option disabled selected>-- Selecciona una opción --</option>
                                    @foreach ($oCars as $car)
                                        <option value="{{ $car->id }}" {{ ($car->id == $oJob->car_id) ? "selected":"" }}>{{ $car->brand.' - '.$car->version }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="car_model">Modelo</label>
                                    <input type="text" value="{{ $oJob->car_model }}" class="form-control" id="car_model" name="car_model" required>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="car_plates">Placas</label>
                                    <input type="text" value="{{ $oJob->car_plates }}" class="form-control" id="car_plates" name="car_plates" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <label for="failures">Fallas</label>
                                    <textarea type="textarea" class="form-control" name="failures" id="failures">{{ $oJob->failures }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4 class="text-primary">Check In</h4>
                        <div class="ml-4">
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Herramientas y Gato]" id="herramientas"  {{ (in_array('Herramientas y Gato', $aCheckIn) ? "checked":"") }}>
                                        <label for="herramientas">Herramientas y Gato</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Llanta de refacción]" id="llanta_refaccion" {{ (in_array('Llanta de refacción', $aCheckIn) ? "checked":"") }}>
                                        <label for="llanta_refaccion">Llanta de refacción</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Radio]" id="radio" {{ (in_array('Radio', $aCheckIn) ? "checked":"") }}>
                                        <label for="radio">Radio</label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Tapetes]" id="tapetes" {{ (in_array('Tapetes', $aCheckIn) ? "checked":"") }}>
                                        <label for="tapetes">Tapetes</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Limpiadores]" id="limpiadores" {{ (in_array('Limpiadores', $aCheckIn) ? "checked":"") }}>
                                        <label for="limpiadores">Limpiadores</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Encendedor]" id="encendedor" {{ (in_array('Encendedor', $aCheckIn) ? "checked":"") }}>
                                        <label for="encendedor">Encendedor</label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Extinguidor]" id="extinguidor" {{ (in_array('Extinguidor', $aCheckIn) ? "checked":"") }}>
                                        <label for="extinguidor">Extinguidor</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Antena]" id="antena" {{ (in_array('Antena', $aCheckIn) ? "checked":"") }}>
                                        <label for="antena">Antena</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Tapones Rueda]" id="tapones_rueda" {{ (in_array('Tapones Rueda', $aCheckIn) ? "checked":"") }}>
                                        <label for="tapones_rueda">Tapones Rueda</label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Tapon Combustible]" id="tapon_combustible" {{ (in_array('Tapon Combustible', $aCheckIn) ? "checked":"") }}>
                                        <label for="tapon_combustible">Tapon Combustible</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Faros]" id="faros" {{ (in_array('Faros', $aCheckIn) ? "checked":"") }}>
                                        <label for="faros">Faros</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[Espejos]" id="espejos" {{ (in_array('Espejos', $aCheckIn) ? "checked":"") }}>
                                        <label for="espejos">Espejos</label>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <hr>
                        <h4 class="text-primary">Daños</h4>
                        <div class="row">
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 55%">Parte del Coche</th>
                                            <th style="width: 40%">Foto del daño</th>
                                            <th style="width: 5%">
                                                <button type="button" class="btn btn-sm btn-primary" onclick="addDamage()"><i class="fas fa-plus-square"></i>
                                                Agregar
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="wrapper-damage-lines">
                                        @if (isset($car_damage))
                                            @foreach ($car_damage as $damage)
                                                <tr>
                                                    <td>
                                                        <select class="form-control" name="car_parts[][name]" required>
                                                            <option value="" disabled>-- Selecciona una opción --</option>
                                                            @foreach ($oParts as $key => $parts)
                                                                    <option value="{{ $parts->name }}" {{ ($parts->name == $damage->part) ? "selected":"" }}>{{ $parts->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>  
                                                    <td>
                                                        <input type="file" class="form-control" id="damage_picture" name="car_parts[][image]" accept="image/*;capture=camera" capture="environment">
                                                    </td>
                                                    <td>
                                                        <button style="" class="btn btn-danger delete-damage" onclick="deleteDamage(this)"><i class="fas fa-trash-alt"></i></button>
                                                    </td>  
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
    <!-- FIN DEL FORMULARIO PARA HACER COMENTARIO --->

@endsection

@section("styles")
	
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/datatables-responsive/css/responsive.bootstrap4.min.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/js/plugins/select2/css/select2.css") }}">
	<style>
		/* Select2 Styles */
		.select2-selection__rendered {
		    line-height: 31px !important;
		}
		.select2-container .select2-selection--single {
		    border: 1px solid #ced4da !important;
		    height: calc(2.25rem + 2px) !important;
		}
		.select2-selection__arrow {
		    height: calc(2.25rem + 2px) !important;
		}
		div.vehicle-line{
			margin: 1.5rem 0px;
			position: relative;
		}
		button.delete-vehicle{
			position:absolute; right:10px; z-index:10; height:20px; top: -10px; font-size: 15px; line-height: unset; padding: 0 0.75rem;
		}
	</style>

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

        function deleteDamage(element){
			element.parentElement.parentElement.remove();
		}

        function addDamage(){
			const oTr = document.createElement('tr');
			oTr.classList.add('damage-line');
			const sNode = `
            <td>
                <select class="form-control" name="car_parts[][name]" required>
                    <option value="" selected disabled>-- Selecciona una opción --</option>
                    @foreach ($oParts as $parts)
                        <option value="{{ $parts->name }}">{{ $parts->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="file" class="form-control" id="damage_picture" name="car_parts[][image]" accept="image/*;capture=camera" capture="environment">
            </td>
            <td>
                <button style="" class="btn btn-danger delete-damage" onclick="deleteDamage(this)"><i class="fas fa-trash-alt"></i></button>
            </td>
			`;
			oTr.innerHTML = sNode;
			document.querySelector('tbody.wrapper-damage-lines').appendChild(oTr);
		}

		
</script>


@endsection
