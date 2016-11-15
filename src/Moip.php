<?php

namespace Zabaala\Moip;

use Zabaala\Moip\Http\HTTPConnection;
use Zabaala\Moip\Resource\BillingInfo;
use Zabaala\Moip\Resource\Invoice;
use Zabaala\Moip\Resource\Payment;
use Zabaala\Moip\Resource\Plan;
use Zabaala\Moip\Resource\Subscriber;
use Zabaala\Moip\Resource\Subscription;

class Moip
{
    /**
     * endpoint of production.
     *
     * @const string
     */
    const ENDPOINT_PRODUCTION = 'api.moip.com.br';
    /**
     * endpoint of sandbox.
     *
     * @const string
     */
    const ENDPOINT_SANDBOX = 'sandbox.moip.com.br';

    /**
     * Client name.
     *
     * @const string
     *
     * @deprecated
     **/
    const CLIENT = 'Moip SDK';

    /**
     * Authentication that will be added to the header of request.
     *
     * @var \Zabaala\Moip\MoipAuthentication
     */
    private $moipAuthentication;

    /**
     * Endpoint of request.
     *
     * @var string
     */
    private $endpoint;

    /**
     * Create a new aurhentication with the endpoint.
     *
     * @param \Zabaala\Moip\MoipAuthentication $moipAuthentication
     * @param $endpoint string
     */
    public function __construct(MoipAuthentication $moipAuthentication, $endpoint = 'sandbox')
    {
        $this->moipAuthentication = $moipAuthentication;

        $this->endpoint = \Config::get('moip.url.' . $endpoint);
    }

    /**
     * Create a new api connection instance.
     *
     * @param HTTPConnection $http_connection
     *
     * @return HTTPConnection
     */
    public function createConnection(HTTPConnection $http_connection)
    {
        $http_connection->initialize($this->endpoint, true);
        $http_connection->addHeader('Accept', 'application/json');
        $http_connection->setAuthenticator($this->moipAuthentication);

        return $http_connection;
    }

    /**
     * Create new Plans instance.
     *
     * @return Plan
     */
    public function plans() {
        return new Plan($this);
    }

    /**
     * Create new Billing Info instance.
     *
     * @return Plan
     */
    public function billingInfos() {
        return new BillingInfo($this);
    }

    /**
     * Create new Subscriber instance.
     *
     * @return Plan
     */
    public function subscribers() {
        return new Subscriber($this);
    }

    /**
     * Create a new Subscription instance.
     *
     * @return Subscription
     */
    public function subscriptions() {
        return new Subscription($this);
    }

    /**
     * Create a new Invoice instance.
     *
     * @return Invoice
     */
    public function invoices() {
        return new Invoice($this);
    }

    /**
     * Create a new Payment instance.
     *
     * @return \Zabaala\Moip\Resource\Payment
     */
    public function payments()
    {
        return new Payment($this);
    }

    /**
     * Get the endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
