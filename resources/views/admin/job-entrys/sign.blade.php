@extends('admin.base')

<?php $name_interno = "Firmar Nota"; ?>

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
                <form action="{{ URL::to("admin/generate-note/".$oJob->id) }}" method="post" id="form-control" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h4 class="text-primary">Datos Generales</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Nombre del Cliente</p>
                                    <p>{{ $oJob->customer_name }}</p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Teléfono del Cliente</p>
                                    <p>{{ $oJob->customer_number }}</p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Email del Cliente</p>
                                    <p>{{ $oJob->customer_email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Marca</p>
                                    <p>{{ $oJob->Car->brand }}</p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Versión</p>
                                    <p>{{ $oJob->Car->version }}</p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Modelo</p>
                                    <p>{{ $oJob->car_model }}</p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Placas</p>
                                    <p>{{ $oJob->car_plates }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md">
                                <div class="form-group">
                                    <p class="font-weight-bold">Fallas</p>
                                    <p>{{ $oJob->failures }}</p>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4 class="text-primary">Check In</h4>    
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[herramientas]" id="herramientas"  {{ (in_array('Herramientas y Gato', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="herramientas">Herramientas y Gato</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[llanta_refaccion]" id="llanta_refaccion" {{ (in_array('Llanta de refacción', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="llanta_refaccion">Llanta de refacción</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[radio]" id="radio" {{ (in_array('Radio', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="radio">Radio</label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[tapetes]" id="tapetes" {{ (in_array('Tapetes', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="tapetes">Tapetes</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[limpiadores]" id="limpiadores" {{ (in_array('Limpiadores', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="limpiadores">Limpiadores</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[encendedor]" id="encendedor" {{ (in_array('Encendedor', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="encendedor">Encendedor</label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[extinguidor]" id="extinguidor" {{ (in_array('Extinguidor', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="extinguidor">Extinguidor</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[antena]" id="antena" {{ (in_array('Antena', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="antena">Antena</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[tapones_rueda]" id="tapones_rueda" {{ (in_array('Tapones Rueda', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="tapones_rueda">Tapones Rueda</label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[tapon_combustible]" id="tapon_combustible" {{ (in_array('Tapon Combustible', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="tapon_combustible">Tapon Combustible</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[faros]" id="faros" {{ (in_array('Faros', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="faros">Faros</label>
                                    </div>
                                </div>
    
                                <div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" name="check_in[espejos]" id="espejos" {{ (in_array('Espejos', $aCheckIn) ? "checked":"") }} disabled>
                                        <label for="espejos">Espejos</label>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                       
                        <h4 class="text-primary">Daños</h4>
                        <div class="row">
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width:25%">Partes del Coche</th>
                                            <th style="width:75%">Imagen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($car_damage as $damage)
                                            <tr>
                                                <td>
                                                    <p>{{ $damage->part }}</p>
                                                </td>
                                                <td>
                                                    <?php
                                                        $imageName = substr($damage->image, 0, -4);
                                                    ?>
                                                    <iframe src="{{ URL::to("admin/get-image/$oJob->car_plates/$imageName") }}" style="width: 400px;
                                                            height: 400px;" scrolling='no' frameborder="0" ></iframe>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
    
                        
                        <h4 class="text-primary">Reparaciones</h4>
                        <div class="row">
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width:20%">Servicio</th>
                                            <th style="width:35%">Piezas Viejas</th>
                                            <th style="width:35%">Piezas Nuevas</th>
                                            <th style="width:10%">Precio del Servicio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($oRepairs as $repairs)
                                            <tr>
                                                <td>
                                                    <p>{{ $repairs->Service->name }}</p>
                                                </td>
                                                <td>
                                                    <?php
                                                        $changePartName = substr($repairs->change_parts, 0, -4);
                                                    ?> 
                                                    <iframe src="{{ URL::to("admin/get-parts/$oJob->car_plates/$changePartName") }}" width= 350px height=350px scrolling='yes' frameborder="0" ></iframe>
                                                </td>
                                                <td>
                                                    <?php
                                                        $newPartName = substr($repairs->new_parts, 0, -4);
                                                    ?>
                                                    <iframe src="{{ URL::to("admin/get-parts/$oJob->car_plates/$newPartName") }}" width= 350px height=350px scrolling='yes' frameborder="0" ></iframe>
                                                </td>
                                                <td>
                                                    <p>{{ "$ ".$repairs->price }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                   
                                                </td>
                                                <td>
                                                    <h5 class="font-weight-bold text-primary">Total: </h5><p class="font-weight-bold">{{ "$ ".$total }}</p>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <hr>
                        <h4 class="text-primary">Firmas</h4>
                            <div class="row d-flex justify-content-around">
                                <div class="">
                                    <div class="form-gruop">
                                        <label for="signature_workshop">Representante Taller</label>
                                        <div id="signature_workshop" style="border: 1px solid black">
                                            <canvas id="canvas_signature_workshop" required></canvas>
                                        </div>
                                        <div class="col-md col-12 text-right">
                                            <button type="button" id="clear" class="btn btn-danger" onclick="clearSignature_workshop()">Limpiar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="form-gruop">
                                        <label for="signature_customer">Cliente</label>
                                        <div id="signature_customer" style="border: 1px solid black">
                                            <canvas id="canvas_signature_customer" required></canvas>
                                        </div>
                                        <div class="col-md col-12 text-right">
                                            <button type="button" id="clear" class="btn btn-danger" onclick="clearSignature_customer()">Limpiar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="text" name="job_id" value="{{ $oJob->id }}" hidden>
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
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script> <!-- LIBRERIA PARA SIGNATURE PAD -->

<script>

        var signature_customer = document.querySelector("#canvas_signature_customer");
        var signaturePad_customer = new SignaturePad(signature_customer, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        const clearSignature_customer = () => {
            signaturePad_customer.clear()
        }

        var signature_workshop = document.querySelector("#canvas_signature_workshop");
        var signaturePad_workshop = new SignaturePad(signature_workshop, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        const clearSignature_workshop = () => {
            signaturePad_workshop.clear()
        }

        function dataURItoBlob(dataURI) {
            var byteString = atob(dataURI.split(',')[1]);
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) { ia[i] = byteString.charCodeAt(i); }
            return new Blob([ab], { type: 'image/jpeg' });
        }

    $(function(){

        $("#form-control").submit(function(event) {
                    event.preventDefault();
                    var formData = new FormData(document.getElementById("form-control"));

                    var dataURL = signature_customer.toDataURL("image/jpeg");
                    var blob = dataURItoBlob(dataURL);
                    formData.append("signature_customer", blob);

                    var dataURL = signature_workshop.toDataURL("image/jpeg");
                    var blob = dataURItoBlob(dataURL);
                    formData.append("signature_workshop", blob);

                    for(const oVal of formData){
                        console.log(oVal);
                    }
                   
                    $.ajax({
                        url: $("#form-control").attr('action'),
                        method: 'post',
                        async: true,
                        processData: false,
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        data: formData,
                        beforeSend: function() {
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

		
</script>


@endsection
