<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\JobEntry;
use App\Models\Module;
use App\Models\Repair;

class DetailsController extends Controller
{
    private $sNameModule;
    private $iIdModule;
    private $rowModule;

    public function __construct()
    {
        $this->iIdModule = 4;
        $this->rowModule = Module::find($this->iIdModule);
        $this->sNameModule = $this->rowModule->name;
    }

    public function getDetails($id)
    {
        try
        {
            $oJobEntry = JobEntry::where('id', $id)->firstOrFail();
            
            return view("admin.{$this->sNameModule}.details", [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->title,
                'oJob' => $oJobEntry,
            ]);
        }
        catch(Exception $e)
        {
            return abort(404, 'El registro no se encuentra en la base de datos!');
        }
    }

    public function postDetails(Request $request, $id)
    {
        $columns = array(
            'id',
            'service_id',
            'price',
            'user_id',
            'created_at',
            'id'
        );

        $totalData = Repair::where('job_id', $id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $oRepairs = Repair::offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->where('job_id', $id)->get();
        }
        else
        {
            $search = $request->input('search.value');

            $oRepairs = Repair::where('id', 'LIKE', "%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->where('job_id', $id)->get();

            $totalFiltered = Repair::where('id', 'LIKE', "%{$search}%")
                                    ->where('job_id', $id)->get()
                                    ->count();
        }

        $data = array();

        if(!empty($oRepairs))
        {
            foreach($oRepairs as $oRepair)
            {

                $nestedData['id'] = $oRepair->id;
                $nestedData['service_id'] = $oRepair->Service->name;
                $nestedData['price'] = "$ ".$oRepair->price;
                $nestedData['user_id'] = $oRepair->User->name;
                $date = date("M d Y H:i:s", strtotime($oRepair->created_at));
                $nestedData['created_at'] = $date;

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
}
