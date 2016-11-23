<?php

namespace Zabaala\Moip\Resource;

use stdClass;

class Subscription extends MoipResource
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
        return $this->createResource(sprintf('/%s', self::PATH));
    }

    /**
     * Update a Subscription.
     *
     * @return stdClass
     */
    public function update() {
        return $this->updateResource(sprintf('/%s/%s', self::PATH, $this->data->code));
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
        return $this->updateResource(sprintf('/%s/%s/%s', self::PATH, $this->data->code, $status));
    }

}
