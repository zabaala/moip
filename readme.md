[![Latest Stable Version](https://poser.pugx.org/zabaala/moip/v/stable)](https://packagist.org/packages/zabaala/moip)
[![Total Downloads](https://poser.pugx.org/zabaala/moip/downloads)](https://packagist.org/packages/zabaala/moip)
[![Latest Unstable Version](https://poser.pugx.org/zabaala/moip/v/unstable)](https://packagist.org/packages/zabaala/moip)
[![License](https://poser.pugx.org/zabaala/moip/license)](https://packagist.org/packages/zabaala/moip)

# Moip Assinaturas

Permita que sua aplicação Laravel se integre com o servico de pagamento recorrente do Moip.

> Pacote criado a partir do SDK V2 criado pela Moiplabs.

### Instalando o pacote

Para incluir a última versão estável do pacote, execute o seguinte comando:

```bash
composer require zabaala/moip
```

Você pode optar também por utilizar versões não estáveis do pacote, o que te permitirá utilizar recursos que ainda estão em desenvolvimento ou em teste.

Para isso, execute:

```bash
composer require zabaala/moip:dev-develop
```

Depois de incluir a dependência `zabaala/moip` no seu projeto, você vai precisar adicionar o Service Provider e um Aliase do pacote no seu arquivo `config/app.php`. No final, você deve ter algo semelhante ao código a baixo:

```php
    'providers' => [
        ...
        Zabaala\Moip\MoipServiceProvider::class,
        ...
    ],

    'aliases' => [
        ...
        'Moip' => Zabaala\Moip\MoipFacade::class,
        ...
    ],
```

### Configurando o Pacote

O pacote `zabaala/moip` possue um arquivo de configuração que precisa ser publicado para a pasta de configuração raiz do seu projeto Laravel. Para isto, execute o comando:
```php
php artisan vendor:publish --tag=moip
```

Ao final do processo, você encontrará um arquivo chamado `moip.php` dentro da pasta `config/` do seu projeto.

Neste momento você vai precisar configurar o seu pacote. Antes de continuar com a configuração, é importante que você já tenha uma conta criada com o Moip. Caso você ainda não possua uma conta Moip tenha, acesse http://cadastro.moip.com.br e efetue seu cadastro. Após você efetuar seu cadastro e tiver a certeza de que sua conta está ativa, autentique-se no ambiente do Moip.

O Moip possue dois End Points:
1. Production: Ambiente final de integração ou Ambiente de Produção. Aqui suas operações serão feitas com dados reais e serão processadas pelo Moip.
2. Sandbox: Ambiente de teste de integração. Aqui suas operações são salvas no moip, mas não são submetidas às operadoras de cartões de crédito.

Por padrão, o pacote utiliza o End Point `sandbox` como padrão em suas configurações.

Uma outra informação importante também, é que o pacote estimula a paramatrização saudável da sua aplicação. Por isso, embora exista um arquivo `moip.php` na sua pasta configurações, recomendo fortemente que você parametrize essas configurações no seu arquivo `.env`. Então, para isso, basta incluir as seguintes linhas no final do seu arquivo `.env`:

```
MOIP_CLIENT=Client Name
MOIP_ENDPOINT=sandbox
MOIP_API_TOKEN=[your-api-token]
MOIP_API_KEY=[your-api-key]
```

Defina os valores corretos para cada uma das variaveis de ambiente relacionadas acima.

## Utilizando o pacote

Com esse pacote você poderá:
* **Planos (Plan):** (listar, consultar, criar, ativar, desativar);
* **Cupons (Coupon):** (listar, consultar, criar, ativar, desativar);
* **Assinantes (Subscriber):** (listar, consultar, criar, atualizar);
* **Informações de Cobrança (BillingInfo):** (Atualizar dados de cobrança);
* **Assinaturas (Subscription):** (Criar, listar, consultar detalhes, suspender, reativar, cancelar, alterar);
* **Faturas (Invoice):** (Listar, consultar);
* **Pagamentos (Payment):** (Listar, consultar);
* **Números de Cartões de Crédito para testes**.

### Planos (Plans)

Listando os planos existentes:

```php
$moip  = Moip::plans()->all();
```

Consultando um plano:

```php
$moip  = Moip::plans()->find($code);
```

Criando um plano:

```php
$plans = Moip::plans();
$plans->setCode('PLAN-123-7789');
$plans->setName("PLANO 01");
$plans->setDescription("Plano básico de assinatura");
$plans->setAmount("3000"); // Corresponde à R$ 30,00
$plans->setInterval('MONTH', 1); // Recorrência da cobrança
$plans->setPaymentMethod(\Zabaala\Moip\Resource\Payment::METHOD_CREDIT_CARD);

try {
    $plans->create();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

Alterando um plano:

```php
$plans = Moip::plans();
$plans->setCode('PLAN-UNIQ-CODE');
$plans->setName("PLANO 01");
$plans->setDescription("Plano básico de assinatura");
$plans->setAmount("3000"); // Corresponde à R$ 30,00
$plans->setInterval('MONTH', 1); // Recorrência da cobrança
$plans->setPaymentMethod(\Zabaala\Moip\Resource\Payment::METHOD_CREDIT_CARD);

try {
    $plans->update();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

Ativando um plano:

```php
$moip  = Moip::plans();
$plans->setCode('PLAN-UNIQ-CODE');

try {
    $plans->activate();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

Desativando um plano um plano:

```php
$plans  = Moip::plans();
$plans->setCode('PLAN-UNIQ-CODE');

try {
    $plans->inactivate();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

### Cupons (Coupons)

Listando todos os cupons de desconto:

```php
$coupons = Moip::coupons()->all();
```

Criando um novo cupom de desconto:

```php
$coupons = Moip::coupons();
$coupons->setCode('ABCD-1234');
$coupons->setName('Coupon de teste de integração');
$coupons->setDescription('Utilizado para testar a integração com o Moip');
$coupons->setDiscount('20', 'percent');
$coupons->setStatus('active');
$coupons->setDuration('once'); // once|repeating|forever
$coupons->setMaxRedemptions('100');
$coupons->setExpirationDate('31', '12', '2016');

try {
    $coupons->create();
} catch (\Exception $e) {
    throw new \Exception($e);
}
```

Ativando um plano:

```php
$coupons = Moip::coupons();
$coupons->setCode('ABCD-1234');
$coupons->activate();
```

Desativando um plano:

```php
$coupons = Moip::coupons();
$coupons->setCode('ABCD-1234');
$coupons->inactivate();
```

### Assinantes (Subscribers)

Listando todos os assinantes:

```php
$subscriber  = Moip::subscribers();
$subscriber->all();
```

Consultando um assinante:

```php
$moip  = Moip::subscribers();
$subscriber->get('CLIENT-CODE');
```

Criando um novo assinante:

```php
$subscriber  = Moip::subscribers();
$subscriber->setCode('CLIENT-CODE');
$subscriber->setEmail('emaildocliente@domain.com');
$subscriber->setFullName("FULANO DE TAL");
$subscriber->setCpf("00000000000"); // Sem mascara.
$subscriber->setPhone(/* code area */ '11', /* phone number */ '999999999'); // Sem mascara.
$subscriber->setBirthDate(/* Day */ '31', /* Month */ '12', /* Year */ '1990');
$subscriber->setAddress(
    'AVENIDA CORONEL LINHARES', // street.
    '4565',                     // number.
    'AP 304',                   // complement. Can be null.
    'CENTRO',                   // District.
    'SAO PAULO',                // City.
    'SP',                       // State.
    '01000000'                  // Zipcode.
);

try {
    $subscriber->create();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

Atualizando um assinante:

```php
$subscriber  = Moip::subscribers();
$subscriber->setCode('CLIENT-CODE');
$subscriber->setEmail('emaildocliente@domain.com');
$subscriber->setFullName("FULANO DE TAL");
$subscriber->setCpf("00000000000"); // Sem mascara.
$subscriber->setPhone(/* code area */ '11', /* phone number */ '999999999'); // Sem mascara.
$subscriber->setBirthDate(/* Day */ '31', /* Month */ '12', /* Year */ '1990');
$subscriber->setAddress(
    'AVENIDA CORONEL LINHARES', // street.
    '4565',                     // number.
    'AP 304',                   // complement. Can be null.
    'CENTRO',                   // District.
    'SAO PAULO',                // City.
    'SP',                       // State.
    '01000000'                  // Zipcode.
);

try {
    $subscriber->update();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

### Informações de cobrança (BillingInfo)

Atualizando o cartão de crédito de um determinado assinante:

```php
$billingInfo  = Moip::billingInfos();
$billingInfo->setSubscriberCode('CLIENT-CODE');
$billingInfo->setCreditCard(
    '0000000000000000', // credit card number.
    '08',               // expiration day.
    '20',               // expiration year with two digits.
    'FULANO C DE TAL'   // holder name.
);

try {
    $billingInfo->update();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

### Assinaturas (Subscriptions)

Listando todas as assinaturas:

```php
$subscription  = Moip::subscriptions();
$subscription->get('SUBSCRIPTION-CODE');
```

Consultando uma assinatura:

```php
$moip  = Moip::subscriptions();
$subscription->get('SUBSCRIPTION-CODE');
```

Criando uma assinatura:

```php
$subscription  = Moip::subscriptions();
$subscription->setCode(uniqid());
$subscription->setAmount('3000');
$subscription->setPaymentMethod(\MoipAssinaturas\Resource\Payment::METHOD_CREDIT_CARD);
$subscription->setPlanCode('PLAN-CODE');
$subscription->setSubscriberCode('02');

try {
    $subscription->create();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

Alterando uma assinatura:

```php
$subscription  = Moip::subscriptions();
$subscription->setCode('SUBSCRIPTION-CODE');
$subscription->setPlanCode('PLAN-CODE');
$subscription->setNextInvoiceDate(/* day */ '05', /* month */ '03', /* year with 4 digits */ '2016');

try {
    $subscription->update();
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
```

### Faturas (Invoice)

Listando todas as faturas:

```php
$invoice = Moip::invoices();
$invoice->setSubscriptionCode('SUBSCRIPTION-CODE');
$invoice->all();
```
Consultando uma assinatura:

```php
$invoice = Moip::invoices();
$invoice->get('INVOICE-CODE');
```

### Pagamentos (Payments)

Listando todos os pagamentos:

```php
$payments = Moip::payments();
$payments->setInvoiceCode('INVOICE-CODE');
$payments->all();
```

Consultando um pagamento:

```php
$payments = Moip::payments();
$payments->get('PAYMENT-ID');
```

### Cartões de Créditos para teste

Abaixo seguem alguns números de cartões de crédito que podem ser utilizados para teste:

American Express*: 376411112222331
Mastercard: 5555666677778884
Diners: 30111122223331
Visa: 4073020000000002
Hipercard: 3841009009123456089

*O cartão American Express possui CVV de 4 dígitos, ao contrário de todas as bandeiras que possuem CVV com apenas 3.

### Licensa
MIT License.