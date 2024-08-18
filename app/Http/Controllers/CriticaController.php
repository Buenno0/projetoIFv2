<?php

namespace App\Http\Controllers;

use App\Models\Critica;
use Illuminate\Http\Request;

class CriticaController extends Controller
{
    public function index()
    {
        $criticas = Critica::where('visible', true)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('criticas.index', compact('criticas'));
    }
    

}
