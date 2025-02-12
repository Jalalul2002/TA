<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataStaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('usertype', '!=', 'admin');
        if ($request->has('search')) {
            $query->search($request->search);
        }
        $users = $query->paginate(10);
        return view('admin.staff', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.staff')->with('success', 'User deleted successfully');
    }
}
