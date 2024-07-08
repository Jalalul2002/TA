<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class DataStaffController extends Controller
{
    public function index()
    {
        $users = User::where('usertype', '!=', 'admin')->get();
        return view('admin.staff', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.staff')->with('success', 'User deleted successfully');
    }
}
