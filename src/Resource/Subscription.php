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
     * Standard country .
     *
     * @const string
     */
    const ADDRESS_COUNTRY = 'BRA';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->customer = new stdClass();
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
     * Set code of coupon.
     * 
     * @param $code
     */
    public function setCouponCode($code) {
        $coupon = new stdClass();
        $coupon->code = $code;
        $this->data->coupon = $coupon;
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
     * Sort Subscription creation.
     *
     * @return stdClass
     */
    public function createWithNewSubscriber() {
        return $this->createResource(sprintf('/%s?new_customer=true', self::PATH));
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

    /**
     * Set Code of the Subscriber.
     *
     * @param $code
     *
     * @return $this
     */
    public function setSubscriberCode($code)
    {
        $this->data->customer->code = $code;

        return $this;
    }

    /**
     * Set e-mail of the Subscriber.
     *
     * @param string $email Email Subscriber.
     *
     * @return $this
     */
    public function setSubscriberEmail($email)
    {
        $this->data->customer->email = $email;

        return $this;
    }

    /**
     * Set fullname of the Subscriber.
     *
     * @param string $fullname Customer's full name.
     *
     * @return $this
     */
    public function setSubscriberFullName($fullname)
    {
        $this->data->customer->fullname = $fullname;

        return $this;
    }

    /**
     * Set fullname of the Subscriber.
     *
     * @param $cpf
     *
     * @return $this
     */
    public function setSubscriberCpf($cpf)
    {
        $this->data->customer->cpf = $cpf;

        return $this;
    }

    /**
     * Set phone of the Subscriber.
     *
     * @param int $areaCode DDD telephone.
     * @param int $number Telephone number.
     *
     * @return $this
     */
    public function setSubscriberPhone($areaCode, $number)
    {
        $this->data->customer->phone_area_code = $areaCode;
        $this->data->customer->phone_number = $number;

        return $this;
    }

    /**
     * Set birth date of the Subscriber.
     *
     * @param $day
     * @param $month
     * @param $year
     *
     * @return $this
     */
    public function setSubscriberBirthDate($day, $month, $year)
    {
        $this->data->customer->birthdate_day = $day;
        $this->data->customer->birthdate_month = $month;
        $this->data->customer->birthdate_year = $year;

        return $this;
    }

    /**
     * Add a new address to the Subscriber.
     *
     * @param string $street
     * @param string $number
     * @param string $district
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $complement
     * @param string $country
     *
     * @return $this
     */
    public function setSubscriberAddress(
        $street,
        $number,
        $complement = null,
        $district,
        $city,
        $state,
        $zip,
        $country = self::ADDRESS_COUNTRY
    )
    {
        $address = new stdClass();
        $address->street = $street;
        $address->number = $number;
        $address->complement = $complement;
        $address->district = $district;
        $address->city = $city;
        $address->state = $state;
        $address->country = $country;
        $address->zipcode = $zip;

        $this->data->customer->address = $address;

        return $this;
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
    public function setSubscriberCreditCard(
        $number,
        $expirationMonth,
        $expirationYear,
        $holder_name
    )
    {
        $credit_card = new stdClass();
        $credit_card->number = $number;
        $credit_card->holder_name = $holder_name;
        $credit_card->expiration_month = $expirationMonth;
        $credit_card->expiration_year = $expirationYear;

        $this->data->customer->billing_info = new stdClass();
        $this->data->customer->billing_info->credit_card = $credit_card;

        return $this;
    }

}
