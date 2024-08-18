<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use Illuminate\Http\Request;

class DenunciaController extends Controller
{
    public function store(Request $request)
    {
        Denuncia::create([
            'user_agent' => $request->header('User-Agent'),
        ]);
    }

}

