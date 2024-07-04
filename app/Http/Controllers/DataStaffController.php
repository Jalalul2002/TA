<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class DataStaffController extends Controller
{
    public function index()
    {
        $users = User::where('usertype', 'staff')->get();
        return view('admin.staff', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.staff')->with('success', 'User deleted successfully');
    }
}
