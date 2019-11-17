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
        if($request->anoMin && $request->anoMax){
            $url .= '/ano-'.$request->anoMin.'-'.$request->anoMax;
        } else if($request->anoMin && !$request->anoMax){
            $url .= '/ano-'.$request->anoMin.'-';
        } else if(!$request->anoMin && $request->anoMax){
            $url .= '/ano--'.$request->anoMax;
        }

        //Filtro 'precos'
        if($request->precoMin && $request->precoMax){
            $url .= '/preco-'.$request->precoMin.'-'.$request->precoMax;
        } else if($request->precoMin && !$request->precoMax){
            $url .= '/preco-'.$request->precoMin.'-';
        } else if(!$request->precoMin && $request->precoMax){
            $url .= '/preco--'.$request->precoMax;
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
        $numPaginasAux = explode("</b>",$numPaginas[1]);

        //Variável que recebe a descrição de cada veículo
        $veiculos = null;

        //Variável que guarda o número da página atualmente manipulda
        $paginaAtual = 1;

        //Laco que percorre por todas as páginas geradas na pesquisa de veículos
        for($i=1; $i<=$numPaginasAux[0]; $i++){

            //Filtro do conteúdo             
            $conteudo = file_get_contents($url.'&page='.$i);
            $quantidadeItensPorPagina = count(explode('<span itemprop="description">',$conteudo))-1;

            //Laço que percorre por cada ítem em uma página
            for($j=0; $j<$quantidadeItensPorPagina; $j++){
                
                //Montagem e armazenamento da descricao de cada item
                $filtroConteudo = explode('<span itemprop="description">',$conteudo);            
                $filtroConteudoAux = explode("</span>",$filtroConteudo[$j+1]);
                $veiculos[$paginaAtual] = $filtroConteudoAux[0];
                $filtroConteudo = explode('"sku">',$conteudo);            
                $filtroConteudoAux = explode("</span>",$filtroConteudo[$j+1]);
                $veiculos[$paginaAtual] .= ' - '.$_SERVER['HTTP_HOST'].'/api/detalhes'.'/'.$filtroConteudoAux[0];
                $paginaAtual++;
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
        $informacoesVeiculo = array(
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
        $filtroConteudo = explode('<h1 class="mb-0" itemprop="name">',$conteudo);            
        $filtroConteudoAux = explode("</h1>",$filtroConteudo[1]);        
        $informacoesVeiculo['descricao'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('itemprop="modelDate" content="',$conteudo);            
        $filtroConteudoAux = explode('">',$filtroConteudo[1]);        
        $informacoesVeiculo['ano'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('itemprop="mileageFromOdometer">',$conteudo);            
        $filtroConteudoAux = explode("</span>",$filtroConteudo[1]);        
        $informacoesVeiculo['quilometragem'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('title="Tipo de transmissão">',$conteudo);            
        $filtroConteudoAux = explode("</span>",$filtroConteudo[1]);        
        $informacoesVeiculo['cambio'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('<span title="Portas">',$conteudo);            
        $filtroConteudoAux = explode("</span>",$filtroConteudo[1]);        
        $informacoesVeiculo['portas'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('itemprop="fuelType">',$conteudo);            
        $filtroConteudoAux = explode("</span>",$filtroConteudo[1]);        
        $informacoesVeiculo['combustivel'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('itemprop="color">',$conteudo);            
        $filtroConteudoAux = explode("</span>",$filtroConteudo[1]);        
        $informacoesVeiculo['cor'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('title="Final da placa">',$conteudo);            
        $filtroConteudoAux = explode("</span>",$filtroConteudo[1]);        
        $informacoesVeiculo['placa'] = $filtroConteudoAux[0];

        $filtroConteudo = explode('<span title="Aceita troca?">',$conteudo);            
        $filtroConteudoAux = explode("</span>",$filtroConteudo[1]);        
        $informacoesVeiculo['troca'] = $filtroConteudoAux[0];

        return $informacoesVeiculo;
    }
}
