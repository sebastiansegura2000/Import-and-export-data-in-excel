<?php
namespace App\Imports;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Hash;

class UsersImport implements  WithHeadingRow, ToCollection
{

    // ToModel,

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new User([
    //         'name'     => $row['name'],
    //         'email'    => $row['email'],
    //         'password' => Hash::make($row['password']),
    //     ]);
    // }


    /**
    * @param Collection $collection
    */

    // public function collection(Collection $rows)
    // {
    //     foreach ($rows as $row)
    //     {
    //         User::create([
    //             'name' => $row['name'],
    //             'email'    => $row['email'],
    //             'password' => Hash::make($row['password']),
    //         ]);
    //     }
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $user = User::where('email', $row['email'])->first();

            if($user)
            {
                // Usuario existente, actualiza los datos
                $user->update([
                    'name' => $row['name'],
                    'password' => Hash::make($row['password']),
                ]);
            }
            else
            {
                // Usuario no existe, crea uno nuevo
                User::create([
                    'email' => $row['email'],
                    'name' => $row['name'],
                    'password' => Hash::make($row['password']),
                ]);
            }
        }
    }


}
