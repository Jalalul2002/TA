<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Perencanaan;
use Illuminate\Http\Request;

class PerencanaanController extends Controller
{
    public function indexInv()
    {
        $perencanaans = '';
        return view('perencanaan', compact('perencanaans'));
    }
}
