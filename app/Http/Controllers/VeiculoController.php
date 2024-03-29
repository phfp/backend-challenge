<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogo;


class VeiculoController extends Controller
{

    //Função para listar os veículos
    public function listarVeiculos(Request $request){

        $catalogo = new Catalogo();
        
        $veiculos = $catalogo->veiculos($request);

        return $veiculos;
    }

    //Função para visualizar as informações de um determinado veículo
    public function buscarDetalhesVeiculo(Request $request){

        $catalogo = new Catalogo();
        
        $veiculo = $catalogo->veiculo($request);

        return $veiculo;
    }
    
}
