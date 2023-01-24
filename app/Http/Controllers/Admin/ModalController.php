<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModalController extends Controller
{


    public function getImage($carPlate, $sNameImage)
    {
        try 
        {
            if(Storage::disk('checkIn')->exists($carPlate."/".$sNameImage.".jpg"))
            {
                return response()->download(Storage_path('checkIn/'.$carPlate."/".$sNameImage.".jpg"), null, [], null);
            }
            
            return "NO HAY IMAGEN O ARCHIVO ADJUNTADO";
        }
        catch(Exception $e)
        {
            return abort(404, "El registro no existe en la base de datos");
        }
    }

    public function getPart($carPlate, $sNameImage)
    {  
        try
        {
            if(Storage::disk('repairs')->exists($carPlate."/".$sNameImage.".jpg"))
            {
                return response()->download(Storage_path('repairs/'.$carPlate."/".$sNameImage.".jpg"), null, [], null);
            }
            
            return "NO HAY IMAGEN O ARCHIVO ADJUNTADO";
        }
        catch(Exception $e)
        {
            return abort(404, 'El registro no existe en la base de datos!');
        }
    }

}
