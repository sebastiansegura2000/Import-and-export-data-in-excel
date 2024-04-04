<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;


class UserController extends Controller
{

    /**
    * @return \Illuminate\Support\Collection
    */

    public function index()
    {
        $users = User::get();

        return view('users', compact('users'));
    }



    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }



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


    public function importExcelData(Request $request)
    {

        $request->validate([
            'import_file'=>[
                'required','file'
            ],
        ]);

        Excel::import(new UsersImport, $request->file('import_file'));

        return redirect()->back()->with('status','Imported Succes');

    }

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




}
