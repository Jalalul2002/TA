<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataStaffController extends Controller
{
    public function index()
    {

        $users = User::where('role', 'staff')->get();
        return view('admin.staff', compact('users'));
    }

    public function show($id)
    {
        $user = User::where('role', 'staff')->findOrFail($id);
        return view('users.show', compact('user'));
    }
}
