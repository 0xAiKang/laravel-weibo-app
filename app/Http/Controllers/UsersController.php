<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    //
    public function create()
    {
        // 表示返回 users/create 页面
        return view("users.create");
    }
}
