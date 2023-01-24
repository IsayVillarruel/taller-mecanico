<?php

namespace App\Http\Controllers\Admin;

use App\CustomClass\Tools;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Car;
use App\Models\CarPart;
use App\Models\JobEntry as ModelController;
use App\Models\Module;
use App\Models\Responsible;
use App\Models\Status;

use Auth;
use DB;
use Log;

class JobEntryController extends Controller
{
    private $sNameModule;
    private $iIdModule;
    private $rowModule;
    private $tools;

    public function __construct()
    {
        $this->iIdModule = 4;
        $this->rowModule = Module::find($this->iIdModule);
        $this->sNameModule = $this->rowModule->name;
        $this->tools = new Tools();
    }

    public function getIndex()
    {   
        return view("admin.{$this->sNameModule}.index", [
            'iIdModule' => $this->iIdModule,
            'sNameModule' => $this->sNameModule,
            'sNameTitle' => $this->rowModule->title
        ]);
    }

    public function postRows(Request $request)
    {
        $columns = array(
            'id',
            'status_id',
            'car_plates',
            'car_brand',
            'car_version',
            'car_model',
            'id'
        );

        $totalData = ModelController::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $oJobs = ModelController::offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $oJobs = ModelController::where('car_plates', 'LIKE', "%{$search}%")
                            ->orWhere('id', 'LIKE', "%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

            $totalFiltered = ModelController::where('car_plates', 'LIKE', "%{$search}%")
                                    ->where('id', 'LIKE', "%{$search}%")
                                    ->count();
        }

        $data = array();

        if(!empty($oJobs))
        {
            foreach($oJobs as $oJob)
            {
                $edit = null;
                $delete = null;
                $details = url("admin/{$this->sNameModule}/details/{$oJob->id}");
                $note = null;
                $repair = null;

                if(Auth::user()->hasPermIntern($this->iIdModule, 'edit'))
                {
                    if($oJob->status_id < 2)
                    {
                        $edit = url("admin/{$this->sNameModule}/edit/{$oJob->id}");
                    }
                }

                if(Auth::user()->hasPermIntern($this->iIdModule, 'delete'))
                {
                    $delete = url("admin/{$this->sNameModule}/delete/{$oJob->id}");
                }

                if($oJob->status_id <= 2)
                {
                    $repair = url("admin/{$this->sNameModule}/workshop/{$oJob->id}");
                }

                if($oJob->status_id === 2)
                {
                    $note = url("admin/generate-note/{$oJob->id}");
                }

                $nestedData['id'] = $oJob->id;
                $nestedData['car_plates'] = $oJob->car_plates;
                $nestedData['car_brand'] = $oJob->Car->brand;
                $nestedData['car_version'] = $oJob->Car->version;
                $nestedData['car_model'] = $oJob->car_model;

                $nestedData['status_id'] = view("admin.ViewsTools.buttons", ['status' => $oJob->status_id, 'name' => $oJob->Status->name])->render();
                $nestedData['options'] = view("admin.ViewsTools.options-job", ['edit' => $edit, 'delete' => $delete, 'details' => $details, 'note' => $note, 'repair' => $repair])->render();

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            'draw'              => intval($request->input('draw')),
            'recordsTotal'      => intval($totalData),
            'recordsFiltered'   => intval($totalFiltered),
            'data'              => $data
        );

        return $json_data;
    }
        
    public function getAdd()
    {
        $oCar = Car::where('active', 1)->get();
        $oStatus = Status::get();
        $oParts = CarPart::get();

        return view("admin.{$this->sNameModule}.add", [
            'iIdModule' => $this->iIdModule,
            'sNameModule' => $this->sNameModule,
            'sNameTitle' => $this->rowModule->title,
            'oCar' => $oCar,
            'oStatus' => $oStatus,
            'oParts' => $oParts,
        ]);
    }

    public function postAdd(Request $request)
    {
        $oValidator = Validator::make($request->all(), [
            'customer_name' => 'required|min:3',
            'customer_number' => 'required|min:10',
            'customer_email' => 'required',
            'car_id' => 'required',
            'car_model' => 'required|min:4|max:4',
            'car_plates' => 'required|min:5|max:8',
            'failures' => 'required',
        ]);

        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );

        $datos = $request->input();

        try
        {
            DB::beginTransaction();

            $newJobEntry = new ModelController();

            $newJobEntry->customer_name = $datos['customer_name'];
            $newJobEntry->customer_number = $datos['customer_number'];
            $newJobEntry->customer_email = $datos['customer_email'];
            $newJobEntry->car_id = $datos['car_id'];
            $newJobEntry->car_model = $datos['car_model'];
            $newJobEntry->car_plates = $datos['car_plates'];
            $newJobEntry->failures = $datos['failures'];
            $newJobEntry->status_id = 1;

            $checkIn = array();
            
            if(isset($datos['check_in']))
            {
                foreach($datos['check_in'] as $key => $check)
                {
                    array_push($checkIn, $key);
                }

                $newJobEntry->check_in = json_encode($checkIn);
            }

            if(isset($datos['car_parts']))
            {
                $oFiles = $request->file('car_parts');
                $car_damage = array();

                for($i=0; $i<count($datos['car_parts']); $i++)
                {
                    $sExtensionFile = $oFiles[$i]['image']->getClientOriginalExtension();
                    $sName = $this->tools->generateString(10).".".$sExtensionFile;

                    $sPathToSave = $datos['car_plates'];

                    array_push($car_damage, ['part' => $datos['car_parts'][$i]['name'], 'image' => $sName]);

                    $result = Storage::disk('checkIn')->putFileAs($sPathToSave, $oFiles[$i]['image'], $sName);
                }    

                $newJobEntry->car_damage = json_encode($car_damage);
            }

            $newJobEntry->save();

            $newResposible = new Responsible();

            $newResposible->user_id = Auth::user()->id;
            $newResposible->action = 'create';
            $newResposible->module_id = $this->iIdModule;
            $newResposible->data_id = $newJobEntry->id;

            $newResposible->save();

            DB::commit();

            return $respuesta;
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error al guardar en la base de datos!';
            \Log::error($e->getMessage());
            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error no controlado!';
            \Log::error($e->getMessage());
            return $respuesta;
        }
    }

    public function getEdit($id)
    {
        try
        {
            $oJob = ModelController::where('id', $id)->firstOrFail();
            $oCars = Car::where('active', 1)->get();
            $oParts = CarPart::get();

            if($oJob->status_id > 1)
            {
                return redirect()->route('job-entrysIndex');
            }

            $car_damage = json_decode($oJob->car_damage);
            $aCheckIn = json_decode($oJob->check_in);

            return view("admin.{$this->sNameModule}.edit", [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->title,
                'oJob' => $oJob,
                'oCars' => $oCars,
                'oParts' => $oParts,
                'car_damage' => $car_damage,
                'aCheckIn' => $aCheckIn,
            ]);
        }
        catch(Exception $e)
        {
            abort(404, 'El Registro no existe en la base de datos');
        }
    }

    public function postEdit(Request $request, $id)
    {
        $oValidator = Validator::make($request->all(), [
            'customer_name' => 'required|min:3',
            'customer_number' => 'required|min:10',
            'customer_email' => 'required',
            'car_id' => 'required',
            'car_model' => 'required|min:4|max:4',
            'car_plates' => 'required|min:5',
            'failures' => 'required',
            'check_in' => 'required',
        ]);

        if($oValidator->fails())
        {
            $errors = $oValidator->errors();
            Log::error($errors);
            return ['error' => 'error', 'mensaje' => $errors->first()];
        }

        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );

        $datos = $request->input();
        
        try
        {
            $oJobEntry = ModelController::where('id', $id)->firstOrFail();
            $sPathToSave = $oJobEntry->car_plates;
            $oFiles = $request->file('car_parts');
            $aCarDamage = array();

            if(!isset($datos['check_in']))
            {
                $respuesta['error'] = 'error';
                $respuesta['mensaje'] = 'CheckIn vacio!';
                return $respuesta;
            }

            if(!isset($datos['car_parts']))
            {
                $oJobEntry->car_damage = null;

                if(Storage::disk('checkIn')->exists($sPathToSave))
                {
                    Storage::disk('checkIn')->deleteDirectory($sPathToSave);
                }
            }
            else if(!isset($oJobEntry->car_damage))
            {
                foreach ($datos['car_parts'] as $key => $aCarParts)
                {
                    if(!isset($oFiles[$key]))
                    {
                        $respuesta['error'] = 'error';
                        $respuesta['mensaje'] = 'Favor de ingresar todos los campos de fotos!';
                        return $respuesta;
                    }

                    $sExtensionFile = $oFiles[$key]['image']->getClientOriginalExtension();
                    $sName = $this->tools->generateString(10).".".$sExtensionFile;
                    
                    array_push($aCarDamage, ['part' => $aCarParts['name'], 'image' => $sName]);

                    Storage::disk('checkIn')->putFileAs($sPathToSave, $oFiles[$key]['image'], $sName);
                }

                $oJobEntry->car_damage = json_encode($aCarDamage);
            }
            else
            {
                $aCar_damage = json_decode($oJobEntry->car_damage);

                //Borrar Elementos
                for($i=0; $i<count($aCar_damage); $i++)
                {
                    if(!isset($datos['car_parts'][$i]['name']))
                    {
                        if(Storage::disk('checkIn')->exists($sPathToSave.'/'.$aCar_damage[$i]->image))
                        {
                            Storage::disk('checkIn')->delete($sPathToSave.'/'.$aCar_damage[$i]->image);
                            array_splice($aCar_damage, $i, 1);
                            $i--;  
                            continue;
                        }
                    }

                    if($aCar_damage[$i]->part != $datos['car_parts'][$i]['name'])
                    {
                        if(Storage::disk('checkIn')->exists($sPathToSave.'/'.$aCar_damage[$i]->image))
                        {
                            Storage::disk('checkIn')->delete($sPathToSave.'/'.$aCar_damage[$i]->image);
                            array_splice($aCar_damage, $i, 1);
                            $i--;
                        }
                    }
                }
                
                foreach($datos['car_parts'] as $key => $part)
                {
                    if(!isset($aCar_damage[$key]))
                    {
                        $sExtensionFile = $oFiles[$key]['image']->getClientOriginalExtension();
                        $sName = $this->tools->generateString(10).".".$sExtensionFile;

                        array_push($aCar_damage, ['part' => $part['name'], 'image' => $sName]);
                        Storage::disk('checkIn')->putFileAs($sPathToSave, $oFiles[$key]['image'], $sName);

                        continue;
                    }

                    if($aCar_damage[$key]->part === $part['name'])
                    {
                        if(!isset($oFiles[$key]))
                        {
                            continue;
                        }

                        $sExtensionFile = $oFiles[$key]['image']->getClientOriginalExtension();
                        $sName = JobEntryController::generateString(10).".".$sExtensionFile;

                        if(Storage::disk('checkIn')->exists($sPathToSave.'/'.$aCar_damage[$key]->image))
                        {
                            Storage::disk('checkIn')->delete($sPathToSave."/".$aCar_damage[$key]->image);
                        }
                        
                        $aCar_damage[$key]->image = $sName;
                        Storage::disk('checkIn')->putFileAs($sPathToSave, $oFiles[$key]['image'], $sName);
                    }

                    

                    if($aCar_damage[$key]->part !== $part['name']) //En caso de que se edite el nombre de algun daño antes declarado
                    {
                        if(!isset($oFiles[$key]))
                        {
                            $respuesta['error'] = 'error';
                            $respuesta['mensaje'] = 'Falta adjuntar imagen en la parte '.$part['name'];
                            return $respuesta;
                        }

                        $aCar_damage[$key]->part = $part['name'];

                        $sExtensionFile = $oFiles[$key]['image']->getClientOriginalExtension();
                        $sName = $this->tools->generateString(10).".".$sExtensionFile;

                        if(Storage::disk('checkIn')->exists($sPathToSave."/".$aCar_damage[$key]->image))
                        {
                            Storage::disk('checkIn')->delete($sPathToSave."/".$aCar_damage[$key]->image);
                        }
                        
                        $aCar_damage[$key]->image = $sName;
                        Storage::disk('checkIn')->putFileAs($sPathToSave, $oFiles[$key]['image'], $sName);
                    }
                }

                $oJobEntry->car_damage = json_encode($aCar_damage);
            }

            if($sPathToSave !== $datos['car_plates'])
            {
                if(!Storage::disk('checkIn')->exists($datos['car_plates']))
                {
                    Storage::disk('checkIn')->makeDirectory($datos['car_plates']);
                }

                if(Storage::disk('checkIn')->exists($sPathToSave))
                {
                    $files = Storage::disk('checkIn')->allFiles($sPathToSave);

                    foreach($files as $file)
                    {
                        $pos = strpos($file,'/');
                        $fileName = substr($file, $pos+1);

                        Storage::disk('checkIn')->move($file, $datos['car_plates'].'/'.$fileName);
                    }
                }

                if(Storage::disk('checkIn')->exists($sPathToSave))
                {
                    Storage::diks('checkIn')->deleteDirectory($sPathToSave);
                }
            }

            $checkIn = array();

            foreach($datos['check_in'] as $key => $check)
            {
                array_push($checkIn, $key);
            }
           
            $oJobEntry->check_in = json_encode($checkIn);
           
            $oJobEntry->car_id = $datos['car_id'];
            $oJobEntry->customer_name = $datos['customer_name'];
            $oJobEntry->customer_number = $datos['customer_number'];
            $oJobEntry->customer_email = $datos['customer_email'];
            $oJobEntry->car_plates = $datos['car_plates'];
            $oJobEntry->car_model = $datos['car_model'];

            $oJobEntry->save();

            $newResposible = new Responsible();

            $newResposible->user_id = Auth::user()->id;
            $newResposible->action = 'edit';
            $newResposible->module_id = $this->iIdModule;
            $newResposible->data_id = $oJobEntry->id;

            $newResposible->save();

            DB::commit();

            return $respuesta;
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error al guardar en la base de datos!';
            \Log::error($e->getMessage());
            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $repuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error no controlado';
            \Log::error($e->getMessage());
            return $respuesta;
        }
    }

    public function getDelete($id)
    {
        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );

        try
        {
            $oJobEntry = ModelController::where('id', $id)->firstOrFail();

            Storage::disk('checkIn')->deleteDirectory($oJobEntry->car_plates);
            Storage::disk('repairs')->deleteDirectory($oJobEntry->car_plates);

            DB::beginTransaction();

            $oJobEntry->delete();

            DB::commit();

            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error no controlado!';
            \Log::error($e->getMessage());
            return $respuesta;
        }
    }
}

