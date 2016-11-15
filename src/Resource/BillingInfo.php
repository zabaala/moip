<?php

namespace Zabaala\Moip\Resource;

use stdClass;

class BillingInfo extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'assinaturas/v1/customers/{{subscriber_code}}/billing_infos';

    /**
     * Subscriber code.
     * @var string
     */
    protected $subscriber_code;


    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Set subscriber code.
     *
     * @param $code
     */
    public function setSubscriberCode($code) {
        $this->subscriber_code = $code;
    }

    /**
     * Create a new Subscriber.
     *
     * @return stdClass
     */
    public function update()
    {
        return $this->putResource(sprintf('/%s', str_replace('{{subscriber_code}}', $this->subscriber_code, self::PATH)));
    }


    /**
     * Set credit card of the Subscriber.
     *
     * @param int $number Card number.
     * @param int $expirationMonth Card expiration month.
     * @param int $expirationYear Year card expiration.
     * @param $holder_name
     *
     * @return $this
     */
    public function setCreditCard(
        $number,
        $expirationMonth,
        $expirationYear,
        $holder_name
    )
    {
        $this->data->credit_card = new stdClass();
        $this->data->credit_card->number = $number;
        $this->data->credit_card->holder_name = $holder_name;
        $this->data->credit_card->expiration_month = $expirationMonth;
        $this->data->credit_card->expiration_year = $expirationYear;

        return $this;
    }

    /**
    * Mount the buyer structure of the Subscriber.
    *
    * @param \stdClass $response
    *
    * @return $this
    */
    protected function populate(stdClass $response)
    {
        $billingInfo = clone $this;
        $billingInfo->data = new stdClass();

        $billingInfo->data->holder_name = $this->getIfSet('holder_name', $response);
        $billingInfo->data->number = $this->getIfSet('number', $response);
        $billingInfo->data->expiration_month = $this->getIfSet('expiration_month', $response);
        $billingInfo->data->expiration_year = $this->getIfSet('expiration_year', $response);

        // FIXME: implementar boleto.

        return $billingInfo;
    }

}
