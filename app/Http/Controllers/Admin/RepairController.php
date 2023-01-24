<?php

namespace App\Http\Controllers\Admin;

use App\CustomClass\Tools;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\JobEntry;
use App\Models\Module;
use App\Models\Repair as ModelController;
use App\Models\Responsible;
use App\Models\Service;

use Auth;
use DB;
use Log;

class RepairController extends Controller
{
    private $sNameModule;
    private $iIdModule;
    private $rowModule;
    private $tools;

    public function __construct()
    {
        $this->iIdModule = 6;
        $this->rowModule = Module::find($this->iIdModule);
        $this->sNameModule = $this->rowModule->name;
        $this->tools = new Tools();
    }

    public function getIndex($id)
    {   
        try
        {
            $oJob = JobEntry::where('id', $id)->firstOrFail();

            return view("admin.{$this->sNameModule}.index", 
            [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->title,
                'id' => $id,
                'oJob' => $oJob,
            ]);
            
        }
        catch(Exception $e)
        {
            abort(404, 'El Registro no existe en la base de datos');
        }
    }

    

    public function postRows(Request $request, $id)
    {
        $columns = array(
            'id',
            'service_id',
            'price',
            'user_id',
            'created_at',
            'id'
        );
        
        $totalData = ModelController::where('job_id', $id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $oRepairs = ModelController::offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->where('job_id', $id)->get();
        }
        else
        {
            $search = $request->input('search.value');

            $oRepairs = ModelController::where('id', 'LIKE', "%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->where('job_id', $id)->get();

            $totalFiltered = ModelController::where('id', 'LIKE', "%{$search}%")
                                    ->where('job_id', $id)->get()
                                    ->count();
        }

        $data = array();

        if(!empty($oRepairs))
        {
            foreach($oRepairs as $oRepair)
            {
                $edit = null;
                $delete = null;

                if(Auth::user()->hasPermIntern($this->iIdModule, 'edit'))
                {
                    $edit = url("admin/job-entrys/{$id}/{$this->sNameModule}/edit/{$oRepair->id}");
                }

                if(Auth::user()->hasPermIntern($this->iIdModule, 'delete'))
                {
                    $delete = url("admin/job-entrys/{$id}/{$this->sNameModule}/delete/{$oRepair->id}");
                }

                $nestedData['id'] = $oRepair->id;
                $nestedData['service_id'] = $oRepair->Service->name;
                $nestedData['price'] = "$ ".$oRepair->price;
                $nestedData['user_id'] = $oRepair->User->name;
                $date = date("M d Y H:i:s", strtotime($oRepair->created_at));
                $nestedData['created_at'] = $date;

                $nestedData['options'] = view("admin.ViewsTools.options", ['edit' => $edit, 'delete' => $delete])->render();

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

    public function getAdd($id)
    {
        $oServices = Service::where('active', 1)->get();

        try
        {
            $oJob = JobEntry::where('id', $id)->firstOrFail();

            return view("admin.{$this->sNameModule}.add", [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->tittle,
                'oServices' => $oServices,
                'oJob' => $oJob
            ]);

        }
        catch(Exception $e)
        {
            return abort(404, 'El registro no fue encontrado!');
        }
    }

    public function postAdd(Request $request, $id)
    {
        $oValidator = Validator::make($request->all(), [
            'service_id' => 'required',
            'price' => 'required',
            'change_parts' => 'required|mimes:png,jpg,jpeg',
            'new_parts' => 'required|mimes:png,jpg,jpeg'
        ]);

        if($oValidator->fails())
        { 
            $errors = $oValidator->errors();
            Log::error($errors);
            return ['error' => 'error', 'mensaje' => $errors->first()];
        }

        $respuesta = array(
            "error" => "success",
            "mensaje" => ""
        );

        $datos = $request->input();

        try
        {
            $oJobEntry = JobEntry::where('id', $id)->firstOrFail();

            DB::beginTransaction();
            $newRepair = new ModelController();
            $newRepair->job_id = $id;
            $newRepair->user_id = Auth::user()->id;
            $newRepair->service_id = $datos['service_id'];
            $newRepair->price = $datos['price'];

            $sPathToSave = $oJobEntry->car_plates;

            if($request->file('change_parts'))
            {
                $oFile = $request->file('change_parts');
                $sExtensionFile = $oFile->getClientOriginalExtension();
                $sName = $this->tools->generateString(10).$sExtensionFile;
            
                $newRepair->change_parts = $sName;

                $result = Storage::disk('repairs')->putFileAs($sPathToSave, $oFile, $sName);
            }

            if($request->file('new_parts'))
            {
                $oFile = $request->file('new_parts');
                $sExtensionFile = $oFile->getclientOriginalExtension();
                $sName = $this->tools->generateString(10).$sExtensionFile;
            
                $newRepair->new_parts = $sName;

                $result = Storage::disk('repairs')->putFileAs($sPathToSave, $oFile, $sName);
            }

            $newRepair->save();

            $oJobEntry->status_id = 2;

            $oJobEntry->save();

            DB::commit();

            return $respuesta;

        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error al guardar en base de datos!';
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

    public function getEdit($id, $repairs_id)
    {
        try
        {
            $oRepair = ModelController::where('id', $repairs_id)->firstOrFail();
            $oServices = Service::where('active', 1)->get();
            $oJob = JobEntry::where('id', $id)->firstOrFail();

            return view("admin.{$this->sNameModule}.edit", [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->tittle,
                'oRepair' => $oRepair,
                'oServices' => $oServices,
                'oJob' => $oJob
            ]);
        }
        catch(Exception $e)
        {
            abort(404, 'El Registro no existe en la base de datos');
        }
    } 

    public function postEdit(Request $request, $id, $repairs_id)
    {
        $oValidator = Validator::make($request->all(), [
            'service_id' => 'required',
            'price' => 'required',
            'change_parts' => 'mimes:png,jpg,jpeg',
            'new_parts' => 'mimes:png,jpg,jpeg',
        ]);

        if($oValidator->fails())
        {
            $errors = $oValidator->errors();
            Log::error($errors);
            return ['error' => 'error', 'mensaje' => $oValidator->first()];
        }

        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );

        $datos = $request->input();

        try
        {
            DB::beginTransaction();
    
            $editRepair = ModelController::where('id', $repairs_id)->firstOrFail();
            $editRepair->service_id = $datos['service_id'];
            $editRepair->price = $datos['price'];
            
            if($request->file('change_parts'))
            {
                $oFile = $request->file('change_parts');
                $sName = $this->tools->generateString(10).".jpg";
                
                $oJobEntry = JobEntry::where('id', $id)->firstOrFail();
                
                $sPathToSave = $oJobEntry->car_plates;
                $editRepair->change_parts = $sName;

                $result = Storage::disk('repairs')->putFileAs($sPathToSave, $oFile, $sName);
            }

            if($request->file('new_parts'))
            {
                $oFile = $request->file('new_parts');
                $sName = $this->tools->generateString(10).".jpg";
                
                $oJobEntry = JobEntry::where('id', $id)->firstOrFail();
                
                $sPathToSave = $oJobEntry->car_plates;
                $editRepair->new_parts = $sName;

                $result = Storage::disk('repairs')->putFileAs($sPathToSave, $oFile, $sName);
            }

            $editRepair->save();

            $newResposible = new Responsible();

            $newResposible->user_id = Auth::user()->id;
            $newResposible->action = 'Edit';
            $newResposible->module_id = $this->iIdModule;
            $newResposible->data_id = $editRepair->id;

            $newResposible->save();
            
            DB::commit();
            
            return $respuesta;
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error al guardar en la base de datos';
            \Log::error($e->getMessage);
            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error no controlado!';
            \Log::error($e->getMessage);
            return $respuesta;
        }
    }

    public function getDelete($id, $repairs_id)
    {
        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );

        try
        {
            DB::beginTransaction();

            $oRepair = ModelController::where('id', $repairs_id)->firstOrFail();
            $newResposible = new Responsible();

            $newResposible->user_id = Auth::user()->id;
            $newResposible->action = 'Edit';
            $newResposible->module_id = $this->iIdModule;
            $newResposible->data_id = $oRepair->id;

            $newResposible->save();
           
            $oRepair->delete();

            DB::commit();

            return $respuesta;
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error al guardar en la base de datos';
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
}
