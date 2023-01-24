<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

use App\Models\Note as NoteModel;
use App\Models\JobEntry as JobEntryModel;
use App\Models\Repair as RepairModel;
use Illuminate\Support\Facades\Storage;
use App\Models\Responsible as ResponsibleModel;

use Auth;
use DB;
use Log;
use App\Mail\SendNote;
use Illuminate\Support\Facades\Mail;

class NoteController extends Controller
{
    private $sNameModule;
    private $iIdModule;
    private $rowModule;

    public function __construct()
    {
        $this->iIdModule = 5;
        $this->rowModule = Module::find($this->iIdModule);
        $this->sNameModule = $this->rowModule->name;
    }

    public function getGenerateNote($id)
    {
        try
        {
            $oJob = JobEntryModel::where('id', $id)->firstOrFail();
            $oRepairs = RepairModel::where('job_id', $id)->get();
            $car_damage = json_decode($oJob->car_damage);
            $aCheckIn = json_decode($oJob->check_in);
            $total = 0;
            //Precio total
            foreach($oRepairs as $repair)
            {
                $total += $repair->price;
            }

            return view("admin.job-entrys.sign", [
                'iIdModule' => $this->iIdModule,
                'sNameModule' => $this->sNameModule,
                'sNameTitle' => $this->rowModule->title,
                'oJob' => $oJob,
                'car_damage' => $car_damage,
                'aCheckIn' => $aCheckIn,
                'oRepairs' => $oRepairs,
                'total' => $total
            ]);
        }
        catch(Exception $e)
        {
            abort(404, 'Datos no encontrado en la base de datos!');
        }
    }

    public function createNote(Request $request)
    {
        $respuesta = array(
            'error' => 'success',
            'mensaje' => ''
        );
        
        $datos = $request->input();
        $id = $datos['job_id'];

        $iNoteNumber = $this->getInvoiceNumber();

        try
        {

            $oJob = JobEntryModel::where('id', $id)->firstOrFail();
            $oRepairs = RepairModel::where('job_id', $id)->get();
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
            
            if($request->hasFile('signature_customer'))
            {
                $firmaCustomer = $request->signature_customer;

                $imgExtension = $request->signature_customer->extension();
                $imgName = 'firmaCliente';

                Storage::disk('sign')->putFileAs($oJob->car_plates, $firmaCustomer, $imgName.'.'.$imgExtension);

                $dataImage = Storage::disk('sign')->get($oJob->car_plates."/firmaCliente.jpg");
                $base64FirmaCustomer = base64_encode($dataImage);
            }

            if($request->hasFile('signature_workshop'))
            {
                $firmaWorkshop = $request->signature_workshop;

                $imgExtension = $request->signature_workshop->extension();
                $imgName = 'firmaTaller';

                Storage::disk('sign')->putFileAs($oJob->car_plates, $firmaWorkshop, $imgName.'.'.$imgExtension);

                $dataImage = Storage::disk('sign')->get($oJob->car_plates."/firmaTaller.jpg");
                $base64FirmaWorkshop = base64_encode($dataImage);
            }
            
            $hoy = date("F j, Y, g:i a");
            $total = 0;

            //Precio total
            foreach($oRepairs as $repair)
            {
                $total += $repair->price;
            }
            
            $pdf = \PDF::loadView('admin.snappy.nota', ['oJob' => $oJob, 'aCheckIn' => $aCheckIn, 'aCarCheckIn' => $aCarCheckIn, 'car_damage' => $car_damage, 'oRepairs' => $oRepairs, 'firmaCustomer' => $base64FirmaCustomer, 'firmaWorkshop' => $base64FirmaWorkshop, 'iNoteNumber' => $iNoteNumber, 'fecha' => $hoy, 'total' => $total]);

            $pdf->setOption('print-media-type', true);
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setOption('images', true);
            $pdf->setOption('margin-bottom', 10);
            $pdf->setOption("footer-center", "Page [page]/[topage]");

            $pdf->save(storage_path('note/'.$oJob->car_plates.'/'.$iNoteNumber.'.pdf'));

            $snappy = $pdf->download('nota.pdf');

            Mail::to($oJob->customer_email)->send(new SendNote($snappy, $iNoteNumber));

            $plates = $oJob->car_plates;

            DB::beginTransaction();

            $newNote = new noteModel();
            $newNote->note_number = $iNoteNumber;
            $newNote->note_path = $oJob->car_plates;
            $newNote->save();

            $newResposible = new ResponsibleModel();

            $newResposible->user_id = Auth::user()->id;
            $newResposible->action = 'GenerateNote';
            $newResposible->module_id = 4;
            $newResposible->data_id = $oJob->id;

            $newResposible->save();

            $oJob->delete();

            DB::commit();

            Storage::disk('checkIn')->deleteDirectory($plates);
            Storage::disk('sign')->deleteDirectory($plates);
            Storage::disk('repairs')->deleteDirectory($plates);
            
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
            'note_number',
            'created_at',
            'id'
        );

        $totalData = NoteModel::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $oNotes = NoteModel::offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $oNotes = NoteModel::where('id', 'LIKE', "%{$search}%")
                            ->orWhere('note_number', 'LIKE', "%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

            $totalFiltered = NoteModel::where('id', 'LIKE', "%{$search}%")
                                    ->where('note_number', 'LIKE', "%{$search}%")
                                    ->count();
        }

        $data = array();

        if(!empty($oNotes))
        {
            foreach($oNotes as $oNote)
            {
                $consult = null;
                $send = null;
                $download = null;

                if(Auth::user()->hasPermIntern($this->iIdModule, 'consult'))
                {
                    $consult = url("admin/{$this->sNameModule}/{$oNote->note_path}/consult/{$oNote->note_number}");
                }

                if(Auth::user()->hasPermIntern($this->iIdModule, 'download'))
                {
                    $download = url("admin/{$this->sNameModule}/{$oNote->note_path}/download/{$oNote->note_number}");
                }

                if(Auth::user()->hasPermIntern($this->iIdModule, 'send'))
                {
                    $send = url("admin/{$this->sNameModule}/{$oNote->note_path}/send/{$oNote->note_number}");
                }

                $nestedData['id'] = $oNote->id;
                $nestedData['note_number'] = $oNote->note_number;
                $date = date("M d Y H:i:s", strtotime($oNote->created_at));
                $nestedData['created_at'] = $date;

                $nestedData['options'] = view("admin.ViewsTools.options-note", ['consult' => $consult, 'send' => $send, 'download' => $download])->render();

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

    public function consultPdf($path, $id)
    {
        if(Storage::disk('note')->exists($path."/".$id.'.pdf'));
        {
            return response()->download(Storage_path("note/".$path."/".$id.'.pdf'), null, [], null);
        }

        $respuesta = array(
            'error' => 'error',
            'mensaje' => 'No existe el archivo seleccionado!',
        );

        return $respuesta;
    }

    public function downloadPdf($path, $id)
    {
        if(Storage::disk('note')->exists($path."/".$id.'.pdf'));
        {
            return Storage::disk('note')->download($path."/".$id.'.pdf');
        }

        $respuesta = array(
            'error' => 'error',
            'mensaje' => 'No existe el archivo seleccionado!',
        );

        return $respuesta;
    }

    public function getSend($path, $id)
    {
        return view("admin.{$this->sNameModule}.send", [
            'iIdModule' => $this->iIdModule,
            'sNameModule' => $this->sNameModule,
            'sNameTitle' => $this->rowModule->title,
            'path' => $path,
            'noteNumber' => $id,
        ]); 
    }

    public function postSend(Request $request, $path, $id)
    {
        $respuesta = array(
            'error' => 'success',
            'mensaje' => '',
        );

        $datos = $request->input();

        try
        {
            $pdf = Storage::disk('note')->get($path."/".$id.'.pdf');

            Mail::to($datos['email'])->send(new SendNote($pdf, $id));
        
            return $respuesta;

        }
        catch(Exception $e)
        {
            $respuesta['error'] = 'error';
            $respuesta['mensaje'] = 'No se pudo enviar el correo!';
            \Log::error($e->getMessage());
            return $respuesta;
        }
        
    }

    private function getInvoiceNumber()
    {
        try
        {
            //GENERAR INVOCE NUMBER
            $sYear = date("y"); 
            $sMonth = date("m");
            $sNumber = '01';
            
            if(NoteModel::count() !== 0)
            {
                $oNoteNumber = NoteModel::latest('id')->select('note_number')->firstOrFail(); //Traemos el ultimo registro de la base de datos
                $sLastMonth = substr($oNoteNumber->note_number, -4, -2);   //Obtenemos el mes, para despues compararlo
                $sNumber = intval(substr($oNoteNumber->note_number, -2));  //Obtenemos el ultimo valor del invoice
                $sNumber++;    //Sumamos 1 al valor del invoice

                if($sLastMonth != $sMonth)  //Si el mes actual, con el mes de la ultima devolucion es diferente se empieza el conteo 01 de los ultimos dos digitos
                {
                    $sNumber = 1;
                }

                if($sNumber < 10)       //En dado caso que el numero sea 1 digito, agregaremos un 0 al inicio
                {
                    $sNumber = '0'.$sNumber;
                }
            }

            $sInvoiceNumber = $sYear.$sMonth.$sNumber; //Juntamos todos lo digitos para obtener nuestro invoice numbe
            return $sInvoiceNumber;
        }
        catch(Exception $e)
        {
            return abort(404, 'El registro no existe en la base de datos!');
        }
        
    }
}
