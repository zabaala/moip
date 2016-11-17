<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CLIENT
    |--------------------------------------------------------------------------
    |
    | Defina o nome do CLIENT que aparecerá nas faturas das Assinaturas.
    |
    */
    'client' => env('MOIP_CLIENT', 'L5 Moip Assinaturas - Package'),

    /*
    |--------------------------------------------------------------------------
    | API Token
    |--------------------------------------------------------------------------
    |
    | As novas APIs de pagamento do Moip permitem que você receba pagamentos
    | em seu website ou em sua aplicação móvel de forma simples e segura.
    | Para isso, você precisa possuir uma conta criada no Moip, além de
    | possuir um Moip API Token e um Moip API KEY, para autenticar-se no
    | sistema do Moip.
    |
    */
    'api' => [
        'token' => env('MOIP_API_TOKEN'),
        'key' => env('MOIP_API_KEY')
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization Code
    |--------------------------------------------------------------------------
    |
    | O Moip Assinaturas utiliza webhooks para notificar os eventos
    | provenientes da sua conta em tempo real.
    |
    | Para garantir que as notificações têm como origem o Moip Assinatura,
    | nós precisaremos verificar o authorization_code, que é um parâmetro que
    | vem no HEADER das requisições do Moip. Essa é uma informação de alta
    | importância, para garantir a segurança da sua aplicação.
    |
    */
    'authorization' => [
        'check' => env('MOIP_AUTHORIZATION_CHECK', true),
        'code' => env('MOIP_AUTHORIZATION_CODE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Endpoint
    |--------------------------------------------------------------------------
    |
    | O Moip fornece dois endpoints, para que sua aplicação se integre
    | com o Moip. Você pode utilizar o ambiente de produção para submeter
    | dados e executar operações reais ou pode utilizar o sandbox, para
    | simular suas atividades de cobrança.
    |
    | Supported: "sandbox", "production"
    | Default: "sandbox"
    |
    */
    'endpoint' => env('MOIP_ENDPOINT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Endpoint URL
    |--------------------------------------------------------------------------
    |
    | Você pode optar por sobrescrever o endereço padrão do ENDPOINT do Moip,
    | para utilizar um endereço alternativo.
    |
    | ATENÇÃO:
    |   É altamente recomendado que essa alteração seja feita se o ambiente
    |   do Moip possuir uma nova URL válida.
    |
    | Defaults:
    |   production: api.moip.com.br
    |   sandbox: sandbox.moip.com.br
    |
    */
    'url' => [
        'sandbox' => 'sandbox.moip.com.br',
        'production' => 'api.moip.com.br',
    ],
];