<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Jobs\ImportExcelDataJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExportedDataEmail;

/**
 * @OA\Info(
 *      title="Nombre de tu API",
 *      version="1.0.0",
 *      description="Descripción de tu API",
 * )
 */

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



    /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="getUsersList",
     *      tags={"Users"},
     *      summary="Get list of users",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      security={
     *          {"sanctum": {}}
     *      }
     * )
     */

    //Function to get the all data of the table users API

    public function indexAPI()
    {
        $users = User::all();

        return response()->json($users);
    }


    /**
     * @OA\Get(
     *      path="/api/users/exportAPI",
     *      operationId="exportUsers",
     *      tags={"Users"},
     *      summary="Export users data to Excel",
     *      description="Allows exporting users data to Excel format",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      security={
     *          {"sanctum": {}}
     *      }
     * )
     */

    // function to export the users table to a excel API

    public function exportAPI()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * @OA\Post(
     *      path="/api/users/importExcelDataAPI",
     *      operationId="importUsers",
     *      tags={"Users"},
     *      summary="Import users data from Excel",
     *      description="Allows importing users data from an Excel file",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Excel file to import",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"import_file"},
     *                  @OA\Property(
     *                      property="import_file",
     *                      description="Excel file to import",
     *                      type="file",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Importación exitosa"
     *              )
     *          )
     *      ),
     *      security={
     *          {"sanctum": {}}
     *      }
     * )
     */

    //  function to import data in the users table with conditions API

    public function importExcelDataAPI(Request $request)
    {
        $request->validate([
            'import_file' => ['required', 'file'],
        ]);

        // Get the email of the currently authenticated user
        $userEmail = auth()->check() ? auth()->user()->email : null;

        // Save the file temporarily
        $filePath = $request->file('import_file')->store('temp');

        // Add the import as a job to the queue
        ImportExcelDataJob::dispatch($filePath, $userEmail);

        return response()->json(['message' => 'Importación exitosa']);
    }



}
