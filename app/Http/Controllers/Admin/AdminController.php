<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use App\Models\DataPerencanaan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $query = Assetlab::query();

        if ($filter !== 'all') {
            $query->where('location', $filter);
        }

        $jumlahInventaris = (clone $query)->where('type', 'inventaris')->count();
        $jumlahStokInventaris = (clone $query)->where('type', 'inventaris')->sum('stock');
        $jumlahBhp = (clone $query)->where('type', 'bhp')->count();
        $jumlahUsers = User::when($filter !== 'all', function ($q) use ($filter) {
            if ($filter === 'Umum') {
                return $q->whereNull('prodi');
            }
            return $q->where('prodi', $filter);
        })->count();
        $jumlahPerencanaan = DataPerencanaan::when($filter !== 'all', function ($q) use ($filter) {
            return $q->where('prodi', $filter);
        })->count();
        $recentAssets = (clone $query)->latest()->limit(10)->get();
        $recentTransactions = Transaction::latest()->limit(5)->get();
        $Users = User::when($filter !== 'all', function ($q) use ($filter) {
            if ($filter === 'Umum') {
                return $q->whereNull('prodi');
            }
            return $q->where('prodi', $filter);
        })->latest()->limit(5)->get();


        return view('admin.dashboard', compact(
            'jumlahBhp',
            'jumlahInventaris',
            'jumlahStokInventaris',
            'jumlahUsers',
            'jumlahPerencanaan',
            'recentAssets',
            'Users',
            'filter',
            'recentTransactions'
        ));
    }
}
