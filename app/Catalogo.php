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

        $conteudo = file_get_contents($url);

        $numPaginas = explode('de um total de <b class="cor-laranja">',$conteudo);
        $numPaginas2 = explode("</b>",$numPaginas[1]);

        $veiculos = null;
        $pg = 1;

        for($i=1; $i<=$numPaginas2[0]; $i++){

            $conteudo = file_get_contents($url.'&page='.$i);
            $quantidadeItensPorPagina = count(explode('<span itemprop="description">',$conteudo))-1;

            for($j=0; $j<$quantidadeItensPorPagina; $j++){
                
                $conteudoFiltro = explode('<span itemprop="description">',$conteudo);            
                $conteudoFiltro2 = explode("</span>",$conteudoFiltro[$j+1]);
                $veiculos[$pg] = $conteudoFiltro2[0];
                $conteudoFiltro = explode('"sku">',$conteudo);            
                $conteudoFiltro2 = explode("</span>",$conteudoFiltro[$j+1]);
                $veiculos[$pg] .= ' - '.$_SERVER['HTTP_HOST'].'/api/detalhes'.'/'.$conteudoFiltro2[0];
                $pg++;
            }
        }

        return $veiculos;
    }

    public function veiculo($request){

        $url = 'https://seminovos.com.br/';

        $url .= $request->sku;

        $conteudo = file_get_contents($url);

        return $conteudo;
    }
}
