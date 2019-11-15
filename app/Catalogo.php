<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
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

        if($request->ano1 && $request->ano2){
            $url .= '/ano-'.$request->ano1.'-'.$request->ano2;
        }else if($request->ano1 && !$request->ano2) {
            $url .= '/ano-'.$request->ano1.'-';
        }else if(!$request->ano1 && $request->ano2) {
            $url .= '/ano--'.$request->ano2;
        }

        if($request->preco1 && $request->preco2){
            $url .= '/preco-'.$request->preco1.'-'.$request->preco2;
        }else if($request->preco1 && !$request->preco2) {
            $url .= '/preco-'.$request->preco1.'-';
        }else if(!$request->preco1 && $request->preco2) {
            $url .= '/preco--'.$request->preco2;
        }

        if($request->estado){
            $url .= '/estado-'.$request->estado;
        }

        if($request->origem){
            $url .= '/origem-'.$request->origem;
        }

        if($request->placa){
            $url .= '/'.$request->placa;
        }

        $url .= "?registrosPagina=50";

        $veiculos = file_get_contents($url);

        return $url;
    }
}
