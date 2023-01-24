<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Car as ModelController;
use App\Models\Module;

use Auth;
use DB;
use Log;

class CarController extends Controller
{
    private $sNameModule;
    private $iIdModule;
    private $rowModule;

    public function __construct()
    {
        $this->iIdModule = 3;
        $this->rowModule = Module::find($this->iIdModule);
        $this->sNameModule = $this->rowModule->name;
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
            'brand',
            'version',
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
            $oCars = ModelController::offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $oCars = ModelController::where('version', 'LIKE', "%{$search}%")
                            ->orWhere('brand', 'LIKE', "%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();
            
            $totalFiltered = ModelController::where('version', 'LIKE', "%{$search}%")
                                    ->orWhere('brand', 'LIKE', "%{$search}%")
                                    ->count();
        }

        $data = array();

        if(!empty($oCars))
        {
            foreach($oCars as $oCar)
            {
                $edit = null;
                $delete = null;

                if(Auth::user()->hasPermIntern($this->iIdModule, 'edit'))
                {
                    $edit = url("admin/{$this->sNameModule}/edit/{$oCar->id}");
                }

                if(Auth::user()->hasPermIntern($this->iIdModule, 'delete'))
                {
                    $delete = url("admin/{$this->sNameModule}/delete/{$oCar->id}");
                }

                $nestedData['id'] = $oCar->id;
                $nestedData['brand'] = $oCar->brand;
                $nestedData['version'] = $oCar->version;

                $nestedData['active'] = view('admin.ViewsTools.status', ['active' => $oCar->active])->render();

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
            'sNameTitle' => $this->rowModule->title,
        ]);
    }

    public function postAdd(Request $request)
    {

        $oValidator = Validator::make($request->all(), [
            'brand' => 'required|min:3',
            'version' => 'required|min:2'
        ]);

        if($oValidator->fails())
        {
            $errors = $oValidator->errors();
            Log::error($oValidator->errors());
            return ['error' => 'error', 'mensaje' => $error->first()];
        }

        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );

        $datos = $request->input();

        try
        {
            DB::beginTransaction();

            $newCar = new ModelController;
            $newCar->brand = $datos['brand'];
            $newCar->version = $datos['version'];
            $newCar->save();

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
            $oCar = ModelController::where('id', $id)->firstOrFail();

            return view("admin.{$this->sNameModule}.edit", [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->title,
                'oCar' => $oCar
            ]);
        }
        catch(Exception $e)
        {
            abort(404, 'El registro no existe en la base de datos!');
        }
    }

    public function postEdit(Request $request)
    {
        $oValidator = Validator::make($request->all(), [
            'brand' => 'required|min:3',
            'version' => 'required|min:2',
            'active' => 'required',
        ]);

        if($oValidator->fails())
        {
            $errors = $oValidator->errors();
            Log::error($oValidator->errors());
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

            $oCar = ModelController::where('id', $datos['id'])->firstOrFail();
            $oCar->brand = $datos['brand'];
            $oCar->version = $datos['version'];
            $oCar->active = $datos['active'];
            $oCar->save();

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
            'mensaje' => ''
        );

        try
        {
            DB::beginTransaction();

            $oCar = ModelController::where('id', $id)->firstOrFail();
            $oCar->active = 0;
            $oCar->save();

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
