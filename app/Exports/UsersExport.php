<?php
namespace App\Exports;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    //function to colect the data of the database

    public function collection()
    {
        return User::select("id", "name", "email")->get();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */

    //function to take the headers in the excel

    public function headings(): array
    {
        return ["ID", "Name", "Email"];
    }
}
