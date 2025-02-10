<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use App\Models\DataPerencanaan;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $totalInventaris = Assetlab::where('type', 'inventaris')->count();
        $totalBhp = Assetlab::where('type', 'bhp')->count();
        $totalPerencanaan = DataPerencanaan::count();
        $recentAssets = Assetlab::latest()->limit(10)->get();
        $Users = User::latest()->get();


        return view('dashboard', compact(
            'totalBhp',
            'totalInventaris',
            'totalPerencanaan',
            'recentAssets',
            'Users'
        ));
    }
}
