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

    //function to create a user with a model function

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

    //function to create a user with a collection function

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

    //function to create or update a user through Excel.

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $user = User::where('email', $row['email'])->first();

            if($user)
            {
                // if a user exist, update a user
                $user->update([
                    'name' => $row['name'],
                    'password' => Hash::make($row['password']),
                ]);
            }
            else
            {
                // if a user doesn't exist, create a new user
                User::create([
                    'email' => $row['email'],
                    'name' => $row['name'],
                    'password' => Hash::make($row['password']),
                ]);
            }
        }
    }


}
