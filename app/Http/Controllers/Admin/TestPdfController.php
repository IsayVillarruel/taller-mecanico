<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\JobEntry;
use App\Models\Repair;



class TestPdfController extends Controller
{
    public static function getPdf($id, $firmaCustomer, $firmaWorkshop)
    {
        $oJob = JobEntry::where('id', $id)->firstOrFail();
        $oRepairs = Repair::where('job_id', $id)->get();
        $car_damage = json_decode($oJob->car_damage);
        $aCarCheckIn = json_decode($oJob->check_in);

        foreach($oRepairs as $repairs)
        {
            $nameChange = $repairs->change_parts;
            $nameNew = $repairs->new_parts;

            if(Storage::disk('repairs')->exists($oJob->car_plates."/".$nameChange))
            {
                $dataImage = Storage::disk('repairs')->get($oJob->car_plates."/".$nameChange);
                $repairs->change_parts = base64_encode($dataImage);
            }

            if(Storage::disk('repairs')->exists($oJob->car_plates."/".$nameNew))
            {
                $dataImage = Storage::disk('repairs')->get($oJob->car_plates."/".$nameNew);
                $repairs->new_parts = base64_encode($dataImage);
            }
        }


        $aCheckIn = array('Herramientas y Gato', 'Llanta de refacción', 'Radio', 'Tapetes', 'Limpiadores', 'Encendedor', 'Extinguidor', 'Antena', 'Tapones Rueda', 'Tapon Combustible', 'Faros', 'Espejos');

        foreach ($car_damage as $damage)
        {
            $nameImage = $damage->image;
            if(Storage::disk('checkIn')->exists($oJob->car_plates."/".$nameImage))
            {
                $dataImage = Storage::disk('checkIn')->get($oJob->car_plates."/".$nameImage);
                $damage->image = base64_encode($dataImage);
            }
        }

        $pdf = \PDF::loadView('admin.snappy.nota', ['oJob' => $oJob, 'aCheckIn' => $aCheckIn, 'aCarCheckIn' => $aCarCheckIn, 'car_damage' => $car_damage, 'oRepairs' => $oRepairs]);

        $pdf->setOption('print-media-type', true);
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('images', true);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption("footer-center", "Page [page]/[topage]");

        //return $pdf->inline();
        $name = "Orden-{$oJob->car_plates}{$oJob->customer_name}.pdf";

        $path = Storage::disk('note')->putFileAs($oJob->car_plates, $pdf, $name);

        die();
        return array('error' => 'success', 'mensaje' => '');
    }
}