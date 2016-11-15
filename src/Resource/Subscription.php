<?php

namespace Zabaala\Moip\Resource;

use Zabaala\Moip\Contracts\ResourceManager;
use stdClass;

class Subscription extends MoipResource implements ResourceManager
{
    /**
     * @const string
     */
    const PATH = 'assinaturas/v1/subscriptions';

    /**
     * Status suspended of Subscription.
     *
     * @const string
     */
    const STATUS_SUSPENDED = 'suspend';

    /**
     * Status activated of Subscription.
     *
     * @const string
     */
    const STATUS_ACTIVATED = 'activate';

    /**
     * Status canceled of Subscription.
     *
     * @const string
     */
    const STATUS_CANCELED = 'cancel';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Set subscription code.
     *
     * @param $code
     */
    public function setCode($code) {
        $this->data->code = $code;
    }

    /**
     * Set subscription amount.
     *
     * @param $amount
     */
    public function setAmount($amount) {
        $this->data->amount = $amount;
    }

    /**
     * Set Payment Method.
     *
     * @param string $method
     */
    public function setPaymentMethod($method = Payment::METHOD_CREDIT_CARD) {
        $this->data->payment_method = $method;
    }

    /**
     * Set Plan Code.
     *
     * @param $planCode
     */
    public function setPlanCode($planCode) {
        $plan = new stdClass();
        $plan->code = $planCode;

        $this->data->plan = $plan;
    }

    /**
     * Set Subscriber / Customer code.
     *
     * @param $code
     */
    public function setSubscriberCode($code) {
        $customer = new stdClass();
        $customer->code = $code;

        $this->data->customer = $customer;
    }

    /**
     * Set date to the next invoice.
     *
     * @param $day
     * @param $month
     * @param $year
     */
    public function setNextInvoiceDate($day, $month, $year) {
        $date = new stdClass();
        $date->day = $day;
        $date->month = $month;
        $date->year = $year;

        $this->data->next_invoice_date = $date;
    }

    /**
     * Get all Subscriptions.
     *
     * @return stdClass
     */
    public function all()
    {
        return $this->getByPath(sprintf('/%s/', self::PATH), true);
    }

    /**
     * Find a Subscription.
     *
     * @param string $code
     *
     * @return stdClass
     */
    public function get($code)
    {
        return $this->getByPath(sprintf('/%s/%s', self::PATH, $code));
    }

    /**
     * Sort Subscription creation.
     *
     * @return stdClass
     */
    public function create() {
        return $this->postResource(sprintf('/%s?new_customer=false', self::PATH));
    }

    /**
     * Update a Subscription.
     *
     * @return stdClass
     */
    public function update() {
        return $this->putResource(sprintf('/%s/%s?new_customer=false', self::PATH, $this->data->code));
    }

    /**
     * Activate a Subscription.
     */
    public function activate() {
        return $this->setStatus(self::STATUS_ACTIVATED);
    }

    /**
     * Suspend a Subscription.
     * If suspended, the subscription will be charged at the end of the current range,
     * to reactivate it, the next will be charged according to the signing of the contract date.
     */
    public function suspend() {
        return $this->setStatus(self::STATUS_SUSPENDED);
    }

    /**
     * Cancel a Subscription.
     * When a Subscription is canceled, it can't be reactivated.
     */
    public function cancel() {
        return $this->setStatus(self::STATUS_CANCELED);
    }

    /**
     * Define situation of Subscription.
     *
     * @param $status
     * @return stdClass
     */
    protected function setStatus($status) {
        return $this->putResource(sprintf('/%s/%s/%s', self::PATH, $this->data->code, $status));
    }

    /**
     * Mount the Subscription structure.
     *
     * @param stdClass $response
     * @return Subscription
     */
    public function populate(stdClass $response) {

        $subscription = clone $this;
        $subscription->data = new stdClass();

        $subscription->data->code = $this->getIfSet('code', $response);
        $subscription->data->amount = $this->getIfSet('amount', $response);
        $subscription->data->payment_method = $this->getIfSet('payment_method', $response);

        // Creation Date
        $creation_date = $this->getIfSet('creation_date', $response);
        $subscription->data->creation_date = new stdClass();
        $subscription->data->creation_date->minute = $this->getIfSet('minute', $creation_date);
        $subscription->data->creation_date->seconde = $this->getIfSet('seconde', $creation_date);
        $subscription->data->creation_date->hour = $this->getIfSet('hour', $creation_date);
        $subscription->data->creation_date->year = $this->getIfSet('year', $creation_date);
        $subscription->data->creation_date->month = $this->getIfSet('month', $creation_date);
        $subscription->data->creation_date->day = $this->getIfSet('day', $creation_date);

        // Plan
        $plan = $this->getIfSet('plan', $response);
        $subscription->data->plan = new stdClass();
        $subscription->data->plan->name = $this->getIfSet('name', $plan);
        $subscription->data->plan->code = $this->getIfSet('code', $plan);

        $subscription->data->status = $this->getIfSet('status', $response);

        // Expiration Date
        $expiration_date = $this->getIfSet('expiration_date', $response);
        $subscription->data->expiration_date = new stdClass();
        $subscription->data->expiration_date->year = $this->getIfSet('year', $expiration_date);
        $subscription->data->expiration_date->month = $this->getIfSet('month', $expiration_date);
        $subscription->data->expiration_date->day = $this->getIfSet('day', $expiration_date);

        // Next Invoice Date
        $next_invoice = $this->getIfSet('next_invoice', $response);
        $subscription->data->next_invoice = new stdClass();
        $subscription->data->next_invoice->year = $this->getIfSet('year', $next_invoice);
        $subscription->data->next_invoice->month = $this->getIfSet('month', $next_invoice);
        $subscription->data->next_invoice->day = $this->getIfSet('day', $next_invoice);

        // Customer
        $customer = $this->getIfSet('customer', $response);
        $subscription->data->customer = new stdClass();
        $subscription->data->customer->code = $this->getIfSet('code', $customer);
        $subscription->data->customer->fullname = $this->getIfSet('fullname', $customer);
        $subscription->data->customer->email = $this->getIfSet('email', $customer);

        // Trial - Start Date
        $trial = $this->getIfSet('trial', $response);
        $subscription->data->trial = new stdClass();

        $start = $this->getIfSet('start', $trial);
        $subscription->data->trial->start = new stdClass();
        $subscription->data->trial->start->day = $this->getIfSet('day', $start);
        $subscription->data->trial->start->month = $this->getIfSet('month', $start);
        $subscription->data->trial->start->year = $this->getIfSet('year', $start);

        // Trial - End Date
        $end = $this->getIfSet('end', $trial);
        $subscription->data->trial->end = new stdClass();
        $subscription->data->trial->end->day = $this->getIfSet('day', $end);
        $subscription->data->trial->end->month = $this->getIfSet('month', $end);
        $subscription->data->trial->end->year = $this->getIfSet('year', $end);

        return $subscription;
    }

}
