<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrigadasController extends Controller
{
     public function index()
    {
        return view('brigadas.index');
    }
}
