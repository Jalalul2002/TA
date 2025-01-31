<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use App\Models\DataPerencanaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index() {
        $user = Auth::user();
        $prodi = $user->prodi;
        
        $totalInventaris = Assetlab::where('type', 'inventaris')->where('location', $prodi)->count();
        $totalBhp = Assetlab::where('type', 'bhp')->where('location', $prodi)->count();
        $totalPerencanaan = DataPerencanaan::where('prodi', $prodi)->count();
        $recentAssets = Assetlab::where('location', $prodi)->latest()->limit(10)->get();
        return view('dashboard', compact(
            'totalBhp',
            'totalInventaris',
            'totalPerencanaan',
            'recentAssets'
        ));
    }
}
