<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Module;
use App\Models\Service as ModelController;

use Auth;
use DB;
use Log;

class ServiceController extends Controller
{
    private $sNameModule;
    private $iIdModule;
    private $rowModule;

    public function __construct()
    {
        $this->iIdModule = 2;
        $this->rowModule = Module::find($this->iIdModule);
        $this->sNameModule = $this->rowModule->name;
    }

    public function getIndex()
    {
        return view("admin.{$this->sNameModule}.index", 
            [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->title,
            ]
        );
    }

    public function postRows(Request $request)
    {
        $columns = array(
            'id',
            'name',
            'description',
            'id',
        );

        $totalData = ModelController::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $oServices = ModelController::offset($start)
                                    ->limit($limit)
                                    ->orderBy($order, $dir)
                                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $oServices = ModelController::where('id', 'LIKE', "%{$search}%")
                                    ->orWhere('name', 'LIKE', "%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order, $dir)
                                    ->get();

            $totalFiltered = ModelController::where('id', 'LIKE', "%{$search}%")
                                    ->orWhere('name', 'LIKE', "%{$search}%")
                                    ->count();
        }

        $data = array();

        if(!empty($oServices))
        {
            foreach($oServices as $oService)
            {
                $edit = null;
                $delete = null;

                if(Auth::user()->hasPermIntern($this->iIdModule, 'edit'))
                {
                    $edit = url("admin/{$this->sNameModule}/edit/{$oService->id}");
                }

                if(Auth::user()->hasPermIntern($this->iIdModule, 'delete'))
                {
                    $delete = url("admin/{$this->sNameModule}/delete/{$oService->id}");
                }
             
                $nestedData['id'] = $oService->id;
                $nestedData['name'] = $oService->name;
                $nestedData['description'] = $oService->description;

                $nestedData['active'] = view('admin.ViewsTools.status', ['active' => $oService->active])->render();
                $nestedData['options'] = view('admin.ViewsTools.options', ['edit' => $edit, 'delete' => $delete])->render();

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
        return view("admin.{$this->sNameModule}.add", [
            'iIdModule' => $this->iIdModule,
            'sNameModule' => $this->sNameModule,
            'sNameTitle' => $this->rowModule->title
        ]);
    }

    public function postAdd(Request $request)
    {
        $oValidator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if($oValidator->fails())
        {
            $errors = $oValidator->errors();
            Log::errors($errors);
            return ['error' => 'error', 'mensaje' => $erros->fist()];
        }

        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );

        $datos = $request->input();

        try
        {
            DB::beginTransaction();

            $newService = new ModelController;
            $newService->name = $datos['name'];
            $newService->description = $datos['description'];

            $newService->save();

            DB::commit();

            return $respuesta;
            
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error en la base de datos!';
            Log::error($e->getMessage());
            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error no controlado!';
            Log::error($e->getMessage());
            return $respuesta;
        }
    }

    public function getEdit($id)
    {
        try
        {
            $oService = ModelController::where('id', $id)->firstOrFail();

            return view("admin.{$this->sNameModule}.edit", 
            [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->title,
                'oService' => $oService,
            ]);

        }
        catch(Exception $e)
        {
            abort(404, 'El Registro no existe en la base de datos!');
        }
    }

    public function postEdit(Request $request)
    {
        $oValidator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'active' => 'required',
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
            DB::beginTransaction();

            $oService = ModelController::where('id', $datos['id'])->firstOrFail();
            $oService->name = $datos['name'];
            $oService->description = $datos['description'];
            $oService->active = $datos['active'];

            $oService->save();

            DB::commit();

            return $respuesta;
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error en la base de datos!';
            Log::error($e->getMessage());
            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'Error no controlado!';
            Log::error($e->getMessage());
            return $respuesta;
        }
    }

    public function getDelete($id)
    {
        $respuesta = array(
            'error' => 'success',
            'mensaje' => '',
        );

        try
        {
            DB::beginTransaction();

            $oService = ModelController::where('id', $id)->firstOrFail();
            $oService->active = 0;
            $oService->save();

            DB::commit();

            return $respuesta;
        }
        catch(Exception $e)
        {
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'No fue posible borrar el registro';
            Log::error($e->getMessage());
            return $respuesta;
        }
    }
}
