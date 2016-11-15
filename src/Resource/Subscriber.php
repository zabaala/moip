<?php

namespace Zabaala\Moip\Resource;

use Zabaala\Moip\Contracts\ResourceManager;
use stdClass;

class Subscriber extends MoipResource implements ResourceManager
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

    /**
    * Mount the buyer structure of the Subscriber.
    *
    * @param \stdClass $response
    *
    * @return \Zabaala\Moip\Resource\Subscriber information.
    */
    public function populate(stdClass $response)
    {
        $subscriber = clone $this;
        $subscriber->data = new stdClass();
        $subscriber->data->code = $this->getIfSet('code', $response);
        $subscriber->data->email = $this->getIfSet('email', $response);
        $subscriber->data->fullname = $this->getIfSet('fullname', $response);
        $subscriber->data->cpf = $this->getIfSet('cpf', $response);
        $subscriber->data->phone_area_code = $this->getIfSet('phone_area_code', $response);
        $subscriber->data->phone_number = $this->getIfSet('phone_number', $response);
        $subscriber->data->birthdate_day = $this->getIfSet('birthdate_day', $response);
        $subscriber->data->birthdate_month = $this->getIfSet('birthdate_month', $response);
        $subscriber->data->birthdate_year = $this->getIfSet('birthdate_year', $response);

        // Address
        $address = $this->getIfSet('address', $response);
        $subscriber->data->address = new stdClass();
        $subscriber->data->address->street = $this->getIfSet('street', $address);
        $subscriber->data->address->city = $this->getIfSet('city', $address);
        $subscriber->data->address->state = $this->getIfSet('state', $address);
        $subscriber->data->address->country = $this->getIfSet('country', $address);
        $subscriber->data->address->zipcode = $this->getIfSet('zipcode', $address);

        // Billing info
        $billing_info = $this->getIfSet('billing_info', $response);
        $credit_card = $this->getIfSet('credit_card', $billing_info);

        $subscriber->data->billing_info = new stdClass();
        $subscriber->data->billing_info->credit_card = new stdClass();
        $subscriber->data->billing_info->credit_card->holder_name = $this->getIfSet('holder_name', $credit_card);
        $subscriber->data->billing_info->credit_card->number = $this->getIfSet('number', $credit_card);
        $subscriber->data->billing_info->credit_card->expiration_month = $this->getIfSet('expiration_month', $credit_card);
        $subscriber->data->billing_info->credit_card->expiration_year = $this->getIfSet('expiration_year', $credit_card);

        // FIXME: implementar boleto.

        return $subscriber;
    }

}
