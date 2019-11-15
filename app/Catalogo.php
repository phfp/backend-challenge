<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    //

    public function veiculos($request){

        $url = 'https://seminovos.com.br/';

        if($request->veiculo){
            $url .= $request->veiculo;
        }else if(!$request->veiculo){
            $url .= "carro";
        }

        if($request->marca){
            $url .= '/'.$request->marca;
        }

        if($request->modelo){
            $url .= '/'.$request->modelo;
        }

        if($request->ano){
            $url .= '/'.$request->ano;
        }

        if($request->preco){
            $url .= '/'.$request->preco;
        }

        if($request->condicao){
            $url .= '/'.$request->condicao;
        }

        if($request->placa){
            $url .= '/'.$request->placa;
        }

        $url .= "?registrosPagina=50";

        $veiculos = file_get_contents($url);

        return $veiculos;
    }
}
