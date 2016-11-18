<?php

namespace Zabaala\Moip\Resource;

use stdClass;

class Plan extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'assinaturas/v1/plans';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Set the Plan code.
     *
     * @param null $code
     */
    public function setCode($code) {

        if ($code === null) {
            $this->data->code = uniqid();
        }

        $this->data->code = $code;
    }

    /**
     * Set the plan name. Optional.
     *
     * @param $name
     */
    public function setName($name) {
        $this->data->name = $name;
    }

    /**
     * Set the plan description.
     *
     * @param null $description
     */
    public function setDescription($description) {
        $this->data->description = $description;
    }

    /**
     * Set amount of the plan.
     *
     * @param $amount
     */
    public function setAmount($amount) {
        $this->data->amount = $amount;
    }

    /**
     * Set the plan setup fee.
     *
     * @param $setup_fee
     */
    public function setSetupFee($setup_fee) {
        $this->data->setup_fee = $setup_fee;
    }

    /**
     * Sets the recurrence of the plan charging.
     *
     * @param string $unit. Unit can be: DAY, MONTH or YEAR.
     * @param int $length
     */
    public function setInterval($unit = 'MONTH', $length = 1) {
        $this->data->interval = new stdClass();
        $this->data->interval->unit = $unit;
        $this->data->interval->length = $length;
    }

    /**
     * Defines how many times the plan must happen.
     *
     * @param $billingCycles
     */
    public function setBillingCycles($billingCycles) {
        $this->data->billing_cycles = $billingCycles;
    }

    /**
     * Set max quantity of the subscribes on the plan.
     * If no value was passed, there is no limit.
     *
     * @param null $maxQuantity
     */
    public function setMaxQuantity($maxQuantity) {
        $this->data->max_qty = $maxQuantity;
    }

    public function setTrial($days = 0, $enabled = false, $hold_setup_fee = true) {
        $this->data->trial = new stdClass();
        $this->data->trial->days = $days;
        $this->data->trial->enabled = $enabled;
        $this->data->trial->hold_setup_fee = $hold_setup_fee;
    }

    /**
     * Payment methods accepted by plan.
     * Default value: CREDIT_CARD.
     *
     * @param string $method
     */
    public function setPaymentMethod($method = Payment::METHOD_CREDIT_CARD) {
        $this->data->payment_method = $method;
    }

    /**
     * Create a new Plan.
     *
     * @return stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s', self::PATH));
    }

    /**
     * Update a Plan.
     *
     * @return stdClass
     */
    public function update() {
        return $this->updateResource(sprintf('/%s/%s', self::PATH, $this->data->code));
    }

    /**
     * Inactivate a Plan.
     *
     * @return stdClass
     */
    public function inactivate() {
        return $this->toggleStatus('inactivate');
    }

    /**
     * Activate a Plan.
     *
     * @return stdClass
     */
    public function activate() {
        return $this->toggleStatus('activate');
    }

    /**
     * Set Plan status.
     *
     * @param $status
     */
    public function setStatus($status) {
        $this->data->status = $status;
    }

    /**
     * Activate / Deactivate a Plan.
     * @see http://dev.moip.com.br/assinaturas-api/?php#ativar-plano-put
     *
     * @param $action string Possible values: activate | inactivate.
     * @return stdClass
     */
    protected function toggleStatus($action)
    {
        return $this->updateResource(sprintf('/%s/%s/%s', self::PATH, $this->data->code, $action));
    }


    /**
     * Find a Plan.
     *
     * @param string $id
     *
     * @return stdClass
     */
    public function find($id)
    {
        return $this->getByPath(sprintf('/%s/%s', self::PATH, $id));
    }

    /**
     * Get All Plans.
     *
     * @return stdClass
     */
    public function all()
    {
        return parent::getAllByPath(sprintf('/%s/', self::PATH));
    }

}