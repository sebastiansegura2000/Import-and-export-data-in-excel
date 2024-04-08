<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Jobs\ImportExcelDataJob;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    /**
    * @return \Illuminate\Support\Collection
    */

    // public function index()
    // {
    //     $users = User::get();

    //     return view('users', compact('users'));
    // }

    // function to export the users table to a excel

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    //function to import data in the users table

    public function import()
    {
        Excel::import(new UsersImport,request()->file('file'));
        return back();
    }



    public function xd()
    {
        $users = User::get();

        return view('index', compact('users'));
    }

    //function to import data in the users table with conditions

    // public function importExcelData(Request $request)
    // {

    //     $request->validate([
    //         'import_file'=>[
    //             'required','file'
    //         ],
    //     ]);

    //     Excel::import(new UsersImport, $request->file('import_file'));

    //     return redirect()->back()->with('status','Imported Succes');

    // }


    public function importExcelData(Request $request)
    {
        $request->validate([
            'import_file'=>[
                'required','file'
            ],
        ]);

        // Get the email of the currently authenticated user
        // $userEmail = auth()->user()->email;

        // Check if there is an authenticated user
        if (auth()->check()) {
            // Get the email of the currently authenticated user
            $userEmail = auth()->user()->email;
        } else {
            // If there is no authenticated user, set the email to nulls
            $userEmail = null;
        }

        // Save the file temporarily
        $filePath = $request->file('import_file')->store('temp');

        // Add the import as a job to the queue
        // Dispatch the job with the user's email as an additional argument
        ImportExcelDataJob::dispatch($filePath, $userEmail);

        return redirect()->back()->with('status', 'Imported Succes');
    }


    //function to export the users table in a excel or csv or xls

    public function exportDataInExcel(Request $request)
    {
        if($request->type == 'xlsx'){

            $fileExt = 'xlsx';
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        }
        elseif($request->type == 'csv'){

            $fileExt = 'csv';
            $exportFormat = \Maatwebsite\Excel\Excel::CSV;
        }
        elseif($request->type == 'xls'){

            $fileExt = 'xls';
            $exportFormat = \Maatwebsite\Excel\Excel::XLS;
        }
        else{

            $fileExt = 'xlsx';
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        }


        $filename = "users-".date('d-m-Y').".".$fileExt;
        return Excel::download(new UsersExport, $filename, $exportFormat);
    }


    //   //***APi***\\

    public function indexAPI()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function exportAPI()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function importExcelDataAPI(Request $request)
    {
        $request->validate([
            'import_file' => ['required', 'file'],
        ]);

        // Obtener el correo electrónico del usuario autenticado actualmente
        $userEmail = auth()->check() ? auth()->user()->email : null;

        // Guardar el archivo temporalmente
        $filePath = $request->file('import_file')->store('temp');

        // Agregar la importación como un trabajo a la cola
        ImportExcelDataJob::dispatch($filePath, $userEmail);

        return response()->json(['message' => 'Importación exitosa']);
    }



}
