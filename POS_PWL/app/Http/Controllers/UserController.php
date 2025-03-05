<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];

        // UserModel::where('username', 'customer-1')->update($data); 

        // $user = UserModel::findOr(20, ['username', 'nama'], function() {
        //     abort(404);
        // });

        //$user = UserModel::where('username','managers')->findOrFail(1);

        // $user = UserModel::where('level_id', 2)->count();
        // dump($user);

        $user = UserModel::firstOrCreate(
            [
                'username'=> 'manager33',
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ],
        );
        $user -> save();
        
        return view('user', ['data' => $user]);
    }
}
