# Backend Challenge

## Sobre o desafio

RESTful PHP para obteção de dados do site https://seminovos.com.br.

## Como Instalar

Dentro do diretório do projeto digite os seguintes comandos:
```
$ composer install
$ php artisan serve
```
Por padrão o HTTP-server irá escutar a porta 8000 caso ela nao estiver sendo usada.
<p>Exemplo de uso da rota 'search' do tipo POST para testes: http://127.0.0.1:8000/api/search

Após acessar a rota 'search', serão listados os registros de veículos. Cada registro conterá um link encaminhando para a rota 'detalhes' do tipo GET, no qual irá detalhar as informações do veículo escolhido.

###### Obs.: Utilise o Postman ou programa similar para realizar as requisições HTTP.

Chaves/Valores
```
veiculo   =>  carro, moto ou caminhao
marca     =>  string
modelo    =>  string
precoMin  =>  inteiro
precoMax  =>  inteiro
anoMin    =>  inteiro (4 digitos)
anoMax    =>  inteiro (4 digitos)
estado    =>  estado-novo ou estado-usado
origem    =>  origem-revenda ou origem-particular
placa     =>  string
```

## Ambiente de Desenvolvimento

- Laravel Framework 6.5.1
- PHP 7.3.9
- Composer version 1.8.6
- Postman 6.7.1
