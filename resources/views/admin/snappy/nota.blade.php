<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title>No. {{$iNoteNumber}}</title>

		<!-- Favicon -->
		<link rel="icon" href="./images/favicon.png" type="image/x-icon" />

		<!-- Invoice styling -->
		<style>
			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				color: #777;
			}

			body h1 {
				font-weight: 300;
				margin-bottom: 0px;
				padding-bottom: 0px;
				color: #000;
			}

			body h3 {
				font-weight: 300;
				margin-top: 10px;
				margin-bottom: 20px;
				font-style: italic;
				color: #555;
			}

			body a {
				color: #06f;
			}

			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}
		</style>
	</head>

	<body>

		<div class="invoice-box">
            <p>                    
                Refaccionaria <br/>
                Cuauhtémoc.<br>
            </p>
			<table>
				<tr class="top">
					<td colspan="4">
						<table>
							<tr>
								<td class="title">
									<img src="{{ public_path('/img/brandCar.png') }}" alt="Company logo" style="width: 100%; max-width: 300px" />
								</td>
								<td>
                                    No. : {{ $iNoteNumber }} <br/>
                                    Fecha de emisión: {{ $fecha }} <br/>
                                    Dirigida a: {{ $oJob->customer_name }}<br/>
                                    Numero de Teléfono: {{ $oJob->customer_number }}<br/>
                                    E-Mail: {{ $oJob->customer_email }}<br/>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="4">
						<table>
							<tr>
								<td>                    
                                    Cuauhtémoc 685,<br/>
                                    Col. Analco<br/>
                                    C.P. 44450<br/>
                                    Guadalajara, Jalisco<br/>
                                    Tel: 33 36 19 16 70<br/>
                                    E-Mail: refaccionaria_cuau@gmail.com
                                </td>
								<td>
                                    Marca: {{ $oJob->Car->brand }}<br/>
                                    Versión: {{ $oJob->Car->version }} <br/>
                                    Placas: {{ $oJob->car_plates }}<br/>
                                </td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>Check In</td>
                    <td></td>
                    <td></td>
                    <td></td>
				</tr>
                @foreach($aCheckIn as $check)
                    <tr class="details">
                        @if(in_array($check, $aCarCheckIn))
                        <td>{{$check}}</td>
                        <td></td>
                        <td></td>
                        <td>Si</td>
                        @else
                        <td>{{$check}}</td>
                        <td></td>
                        <td></td>
                        <td>No</td>
                        @endif
                    </tr>
                @endforeach
				<tr class="heading">
					<td>Daños</td>
                    <td></td>
                    <td></td>
					<td></td>
				</tr>
                @foreach ($car_damage as $damage)
                    <tr class="item">
                        <td>{{$damage->part}}</td>
                        <td></td>
                        <td></td>
                        <td><img style="height: 160px;" src="data:image/jpg;base64, {{ $damage->image }}" alt=""> </td>
                    </tr>
                @endforeach

                <tr class="heading">
					<td>Reparaciones</td>
					<td>Descripción</td>
					<td>Piezas Viejas</td>
					<td>Piezas Nuevass</td>
				</tr>
                @foreach ($oRepairs as $repairs)
                    <tr class="item">
                        <td>{{$repairs->Service->name}}</td>
                        <td>{{$repairs->Service->description}}</td>
                        <td><img style="height: 160px;" src="data:image/jpg;base64, {{ $repairs->change_parts }}" alt=""> </td>
                        <td><img style="height: 160px;" src="data:image/jpg;base64, {{ $repairs->new_parts }}" alt=""> </td>
                    </tr>
                @endforeach
                <tr class="heading">
                	<td></td>
                	<td></td>
                	<td></td>
                	<td>Total: </td>
                </tr>
                <tr class="item">
                	<td></td>
                	<td></td>
                	<td></td>
                	<td>{{ "$ ".$total }}</td>
                </tr>
			</table>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <tr class="heading">
                                <td>Firma Representante Taller</td>
                                <td>Firma Cliente</td>
                            </tr>
                            <tr>
                                <td><img style="height: 160px;" src="data:image/jpg;base64, {{ $firmaWorkshop }}" alt=""> </td>
                                <td><img style="height: 160px;" src="data:image/jpg;base64, {{ $firmaCustomer }}" alt=""> </td>
                            </tr>
                        </tr>
                    </table>
                </td>
            </tr>
            
		</div>
	</body>
