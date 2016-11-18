<?php

namespace Zabaala\Moip\Resource;

use stdClass;

class Subscriber extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'assinaturas/v1/customers';

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
    }

    /**
     * Create a new Subscriber.
     *
     * @return stdClass
     */
    public function create()
    {
        return $this->postResource(sprintf('/%s', self::PATH));
    }

    /**
     * Update a Subscriber.
     *
     * @return stdClass
     */
    public function update() {
        return $this->putResource(sprintf('/%s/%s', self::PATH, $this->data->code));
    }

    /**
     * Find a Subscriber.
     *
     * @param string $id
     *
     * @return stdClass
     */
    public function get($id)
    {
        return $this->getByPath(sprintf('/%s/%s', self::PATH, $id));
    }

    /**
     * Get all Subscribers.
     *
     * @return stdClass
     */
    public function all()
    {
        return $this->getByPath(sprintf('/%s/', self::PATH), true);
    }

    /**
     * Set Code of the Subscriber.
     *
     * @param $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->data->code = $code;

        return $this;
    }

    /**
     * Set e-mail of the Subscriber.
     *
     * @param string $email Email Subscriber.
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->data->email = $email;

        return $this;
    }

    /**
     * Set fullname of the Subscriber.
     *
     * @param string $fullname Customer's full name.
     *
     * @return $this
     */
    public function setFullName($fullname)
    {
        $this->data->fullname = $fullname;

        return $this;
    }

    /**
     * Set fullname of the Subscriber.
     *
     * @param $cpf
     *
     * @return $this
     */
    public function setCpf($cpf)
    {
        $this->data->cpf = $cpf;

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
    public function setPhone($areaCode, $number)
    {
        $this->data->phone_area_code = $areaCode;
        $this->data->phone_number = $number;

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
    public function setBirthDate($day, $month, $year)
    {
        $this->data->birthdate_day = $day;
        $this->data->birthdate_month = $month;
        $this->data->birthdate_year = $year;

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
    public function setAddress(
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

        $this->data->address = $address;

        return $this;
    }


}
