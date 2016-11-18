<?php

namespace Zabaala\Moip\Resource;


use Illuminate\Support\Collection;
use stdClass;
use Zabaala\Moip\Contracts\ResourceManager;

class Coupon extends MoipResource implements ResourceManager
{
    /**
     * @const string
     */
    const PATH = 'assinaturas/v1/coupons';

    /**
     * Initialize a new instance.
     */
    protected function initialize()
    {
        $this->data = new \stdClass();
    }

    /**
     * Find a Coupon.
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
     * Get All Coupons.
     *
     * @return stdClass
     */
    public function all()
    {
        return parent::getAllByPath(sprintf('/%s/', self::PATH));
    }

    /**
     * Create a new Coupon.
     *
     * @return stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s', self::PATH));
    }

    /**
     * Update a Coupon.
     *
     * @return stdClass
     */
    public function update() {
        return $this->updateResource(sprintf('/%s/%s', self::PATH, $this->data->code));
    }

    /**
     * Set code of coupon.
     *
     * @param $value
     */
    public function setCode($value)
    {
        $this->data->code = $value;
    }

    /**
     * Set name of coupon.
     *
     * @param $value
     */
    public function setName($value)
    {
        $this->data->name = $value;
    }

    /**
     * Set description of coupon.
     *
     * @param $value
     */
    public function setDescription($value)
    {
        $this->data->description = $value;
    }

    /**
     * Set discount of coupon.
     *
     * @param $value
     * @param $type
     */
    public function setDiscount($value, $type)
    {
        $this->data->discount = new \stdClass();
        $this->data->discount->value = $value;
        $this->data->discount->type = $type;
    }

    /**
     * Set status of coupon.
     *
     * @param $value
     */
    public function setStatus($value)
    {
        $this->data->status = $value;
    }

    /**
     * Set duration of coupon.
     *
     * @param $type
     * @param $occurrences
     */
    public function setDuration($type, $occurrences = '')
    {
        $this->data->duration = new \stdClass();
        $this->data->duration->type = $type;
        $this->data->duration->occurrences = $occurrences;
    }

    /**
     * Set max redemptions.
     *
     * @param $value
     */
    public function setMaxRedemptions($value)
    {
        $this->data->max_redemptions = $value;
    }

    /**
     * Set Expiration date of coupon.
     * @param $day
     * @param $month
     * @param $year
     */
    public function setExpirationDate($day, $month, $year)
    {
        $this->data->expiration_date = new \stdClass();
        $this->data->expiration_date->day = $day;
        $this->data->expiration_date->month = $month;
        $this->data->expiration_date->year = $year;
    }

    /**
     * Activate a coupon.
     *
     * @return stdClass
     */
    public function activate()
    {
        return $this->toggleStatus('active');
    }

    /**
     * @return stdClass
     */
    public function inactivate()
    {
        return $this->toggleStatus('inactive');
    }

    /**
     * Toggle coupon status.
     * @see http://dev.moip.com.br/assinaturas-api/?php#ativar-e-inativar-coupons-put
     *
     * @param string $action Possible values: activate | inactivate.
     * @return stdClass
     */
    protected function toggleStatus($action)
    {
        return $this->updateResource(sprintf('/%s/%s/%s', self::PATH, $this->data->code, $action));
    }

}