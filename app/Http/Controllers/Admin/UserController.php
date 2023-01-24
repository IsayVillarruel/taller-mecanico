<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use DB;
use Exception;
use Hash;
use Auth;
use App\Models\User as UserModel;
use App\Models\UserType;
use App\Models\Module;
use stdClass;

class UserController extends Controller
{
    private $sNameModule;
    private $iIdModule;
    private $rowModule;

    public function __construct()
    {
        $this->iIdModule = 1;
        $this->rowModule = Module::find($this->iIdModule);
        $this->sNameModule = $this->rowModule->name;
    }

    public function getIndex()
    {
        
        return view("admin.{$this->sNameModule}.index",
            [
                "iModuleId" => $this->iIdModule,
                "sNameModule" => $this->sNameModule,
                "sNameTitle" => $this->rowModule->title
            ]
        );
    }


    public function postRows(Request $request)
    {
        $columns = array( 
            'id', 
            'name',
            'email',
            'active',
            'id',
        );

        $totalData = UserModel::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $oUsers = UserModel::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
        }
        else 
        {
            $search = $request->input('search.value'); 

            $oUsers =  UserModel::where('id','LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = UserModel::where('id','LIKE',"%{$search}%")
                                    ->orWhere('name', 'LIKE',"%{$search}%")
                                    ->count();
        }

        $data = array();

        if(!empty($oUsers))
        {
            foreach ($oUsers as $oUser)
            {
                $edit=null;
                $delete=null;

                if(Auth::user()->hasPermIntern($this->iIdModule,"edit"))
                {
                    $edit =  url("admin/{$this->sNameModule}/edit/{$oUser->id}");
                }

                if(Auth::user()->hasPermIntern($this->iIdModule,"delete"))
                {
                    $delete =  url("admin/{$this->sNameModule}/delete/{$oUser->id}");
                }

                $nestedData['id'] = $oUser->id;
                $nestedData['name'] = $oUser->name;
                $nestedData['email'] = $oUser->email;
                $nestedData['type_user_id'] = $oUser->UserType->type;
                
                $nestedData['options'] = view("admin.ViewsTools.options",["edit" => $edit,"delete" => $delete])->render();

                $data[] = $nestedData;
            }
        }
          
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );

        return $json_data;
    }


    public function getAdd()
    {
        $oUserType = UserType::get();

        return view("admin.{$this->sNameModule}.add",
            [
                "iModuleId"=>$this->iIdModule,
                "sNameModule"=>$this->sNameModule,
                "sNameTitle"=>$this->rowModule->title,
                "oUserType" => $oUserType
 
            ]
        );

    }


    public function postAdd(Request $request)
    {
        $respuesta = array(
            "error" => "success",
            "mensaje" => ""
        );

        $datos = $request->input();

        try
        {
            DB::beginTransaction(); 

            $oNewUser = new UserModel;
            $oNewUser->name = $datos["name"];
            $oNewUser->email = $datos["email"];
           
            $oNewUser->password = Hash::make($datos["password"]);

            $oNewUser->user_type_id = $datos['user_type'];
            
            $arrayPems = array();

            if(!empty($datos['modulos']))
            {
                foreach($datos["modulos"] as $item)
                {
                    $permsClassTMP = new stdClass;
    
                    $permsClassTMP->idModule = $item;
                    $permsClassTMP->permsInters = $datos["modulos_perm_extras"][$item];
                
                    $arrayPems[] = $permsClassTMP;
                }
            }
        
            $oNewUser->perm = json_encode($arrayPems);
            
            $oNewUser->save();

            
            DB::commit();
            return $respuesta;
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta["error"] = "error";
            $respuesta["mensaje"] = "Error de Base de datos";
            Log::error($e->getMessage());
            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $respuesta["error"]="error";
            $respuesta["mensaje"]="Error no controlado";
            Log::error($e->getMessage());
            return $respuesta;
        }

    }


    public function getEdit($id)
    {
        try
        {
            $oEditUser = UserModel::where("id",$id)->firstOrFail();
            $oUserType = UserType::get();

            return view("admin.{$this->sNameModule}.edit",
                [
                    "iModuleId"=>$this->iIdModule,
                    "sNameModule"=>$this->sNameModule,
                    "sNameTitle"=>$this->rowModule->title,
                    "oEditUser"=>$oEditUser,
                    "oUserType" => $oUserType,
                ]
            );
        }
        catch(Exception $e)
        {
            abort(404, 'El Registro no existe en la base de datos');
        }
    }


    public function postEdit(Request $request,$id)
    {
        $respuesta = array(
            "error" => "success",
            "mensaje" => ""
        );

        $datos = $request->input();
        
        try
        {    
            DB::beginTransaction(); 

            $oUser = UserModel::where("id",$id)->firstOrFail();

            $oUser->name = $datos["name"];
            $oUser->email = $datos["email"];
            $oUser->user_type_id = $datos['user_type'];
        
            if($datos["password"] != "")
            {
                $oUser->password = Hash::make($datos["password"]);
            }

            $arrayPems = array();

            if(!empty($datos['modulos']))
            {
                foreach($datos["modulos"] as $item)
                {
                    $permsClassTMP = new stdClass;
    
                    $permsClassTMP->idModule = $item;
                    $permsClassTMP->permsInters = $datos["modulos_perm_extras"][$item];
    
                    $arrayPems[] = $permsClassTMP;
                }
            }
            
            $oUser->perm = json_encode($arrayPems);
            $oUser->save();

            DB::commit();
            return $respuesta;
        }
        catch(QueryException $e)
        {
            DB::rollback();
            $respuesta["error"] = "error";
            $respuesta["mensaje"] = "Error de Base de datos";
            Log::error($e->getMessage());
            return $respuesta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $respuesta["error"] = "error";
            $respuesta["mensaje"] = "Error no controlado";
            Log::error($e->getMessage());
            return $respuesta;
        }

    }


    public function getDelete($id)
    {
        $respuesta = array(
            "error" => "success",
            "mensaje" => ""
        );

        try
        {
            DB::beginTransaction(); 
                $oUser = UserModel::where("id",$id)->firstOrFail();

                $oUser->delete();
            DB::commit();

            return $respuesta;
        }
        catch(Exception $e)
        {
            $respuesta = array(
                "error" => "error",
                "mensaje" => "No fue posible borrar el registro"
            );

            return $respuesta;
        }
        

        

        
    }
}
