<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use App\Models\DataPerencanaan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $prodi = $user->prodi;

        $totalInventaris = Assetlab::where('type', 'inventaris')->where('location', $prodi)->count();
        $totalBhp = Assetlab::where('type', 'bhp')->where('location', $prodi)->count();
        $totalPerencanaan = DataPerencanaan::where('prodi', $prodi)->count();
        $recentAssets = Assetlab::where('location', $prodi)->latest()->limit(10)->get();
        $recentTransactions = Transaction::latest()->limit(5)->get();
        return view('dashboard', compact(
            'totalBhp',
            'totalInventaris',
            'totalPerencanaan',
            'recentAssets',
            'recentTransactions'
        ));
    }

    public function user(Request $request)
    {
        $query = User::where('usertype', '!=', 'admin')->where('prodi', Auth::user()->prodi);
        if ($request->has('search')) {
            $query->search($request->search);
        }
        $users = $query->paginate(10);
        return view('staff.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('staff')->with('success', 'User deleted successfully');
    }
}
