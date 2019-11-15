<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogo;


class VeiculoController extends Controller
{
    public function buscar(Request $request){

        $catalogo = new Catalogo();
        
        $veiculos = $catalogo->veiculos($request);

        return $veiculos;
    }
}
