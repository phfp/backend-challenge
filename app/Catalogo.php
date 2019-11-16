<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    //Obtendo os resultados da busca

    public function veiculos($request){

        $url = 'https://seminovos.com.br/';


        //Montagem da URL com base nos filtros recebidos pelo método POST
        //Filtro veículo
        if($request->veiculo){
            $url .= $request->veiculo;
        }else if(!$request->veiculo){
            $url .= "carro";
        }

        //Filtro 'marca'
        if($request->marca){
            $url .= '/'.$request->marca;
        }

        //Filtro 'modelo'
        if($request->modelo){
            $url .= '/'.$request->modelo;
        }

        //Filtro 'anos'
        if($request->ano1 && $request->ano2){
            $url .= '/ano-'.$request->ano1.'-'.$request->ano2;
        } else if($request->ano1 && !$request->ano2){
            $url .= '/ano-'.$request->ano1.'-';
        } else if(!$request->ano1 && $request->ano2){
            $url .= '/ano--'.$request->ano2;
        }

        //Filtro 'precos'
        if($request->preco1 && $request->preco2){
            $url .= '/preco-'.$request->preco1.'-'.$request->preco2;
        } else if($request->preco1 && !$request->preco2){
            $url .= '/preco-'.$request->preco1.'-';
        } else if(!$request->preco1 && $request->preco2){
            $url .= '/preco--'.$request->preco2;
        }

        //Filtro 'estado'
        if($request->estado){
            $url .= '/estado-'.$request->estado;
        }

        //Filtro 'origem'
        if($request->origem){
            $url .= '/origem-'.$request->origem;
        }

        //Filtro 'placa'
        if($request->placa){
            $url = 'https://seminovos.com.br/veiculo-placa?consultaPlaca=true&placaId='.$request->placa;
        }

        //Definicao da quantidade de registros por página
        $url .= "?registrosPagina=50";

        //Variável para o conteúdo da página da URL gerada
        $conteudo = file_get_contents($url);

        //Obtendo o número de páginas geradas na busca
        $numPaginas = explode('de um total de <b class="cor-laranja">',$conteudo);
        $numPaginas2 = explode("</b>",$numPaginas[1]);

        //Variável que recebe a descrição de cada veículo
        $veiculos = null;

        //Variável que guarda o número da página atualmente manipulda
        $pg = 1;

        //Laco que percorre por todas as páginas geradas na pesquisa de veículos
        for($i=1; $i<=$numPaginas2[0]; $i++){

            //Filtro do conteúdo             
            $conteudo = file_get_contents($url.'&page='.$i);
            $quantidadeItensPorPagina = count(explode('<span itemprop="description">',$conteudo))-1;

            //Laço que percorre por cada ítem em uma página
            for($j=0; $j<$quantidadeItensPorPagina; $j++){
                
                //Montagem e armazenamento da descricao de cada item
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

    //Exibindo informações de um veículo
    public function veiculo($request){

        $url = 'https://seminovos.com.br/';
        
        //Montagem da URL para busca utilizando o código do veículo
        $url .= $request->sku;

        $conteudo = file_get_contents($url);

        //Array para guardar as informações
        $informacoes = array(
            'descricao'         => '',
            'ano'               => '',
            'quilometragem'     => '',
            'cambio'            => '',
            'portas'            => '',
            'combustivel'       => '',
            'cor'               => '',
            'placa'             => '',
            'troca'             => '',
        );

        //Filtro e armazenamento das informações
        $conteudoFiltro = explode('<h1 class="mb-0" itemprop="name">',$conteudo);            
        $conteudoFiltro2 = explode("</h1>",$conteudoFiltro[1]);        
        $informacoes['descricao'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('itemprop="modelDate" content="',$conteudo);            
        $conteudoFiltro2 = explode('">',$conteudoFiltro[1]);        
        $informacoes['ano'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('itemprop="mileageFromOdometer">',$conteudo);            
        $conteudoFiltro2 = explode("</span>",$conteudoFiltro[1]);        
        $informacoes['quilometragem'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('title="Tipo de transmissão">',$conteudo);            
        $conteudoFiltro2 = explode("</span>",$conteudoFiltro[1]);        
        $informacoes['cambio'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('<span title="Portas">',$conteudo);            
        $conteudoFiltro2 = explode("</span>",$conteudoFiltro[1]);        
        $informacoes['portas'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('itemprop="fuelType">',$conteudo);            
        $conteudoFiltro2 = explode("</span>",$conteudoFiltro[1]);        
        $informacoes['combustivel'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('itemprop="color">',$conteudo);            
        $conteudoFiltro2 = explode("</span>",$conteudoFiltro[1]);        
        $informacoes['cor'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('title="Final da placa">',$conteudo);            
        $conteudoFiltro2 = explode("</span>",$conteudoFiltro[1]);        
        $informacoes['placa'] = $conteudoFiltro2[0];

        $conteudoFiltro = explode('<span title="Aceita troca?">',$conteudo);            
        $conteudoFiltro2 = explode("</span>",$conteudoFiltro[1]);        
        $informacoes['troca'] = $conteudoFiltro2[0];

        return $informacoes;
    }
}
