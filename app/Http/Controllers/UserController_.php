<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::all();

        return view('admin.user.index',compact('users'))->with('i',1);
    }

    public function profile(){
        return view('admin.profile');
    }


}
