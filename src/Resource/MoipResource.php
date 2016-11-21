<?php

namespace Zabaala\Moip\Resource;

use Illuminate\Support\Collection;
use JsonSerializable;
use Zabaala\Moip\Http\HTTPConnection;
use Zabaala\Moip\Http\HTTPRequest;
use Zabaala\Moip\Moip;
use stdClass;

class MoipResource implements JsonSerializable
{
    /**
     * Version of API.
     *
     * @const string
     */
    const VERSION = 'v2';

    /**
     * @var \Zabaala\Moip\Moip
     */
    protected $moip;

    /**
     * @var \stdClass
     */
    protected $data;

    /**
     * Create a new instance.
     *
     * @param \Zabaala\Moip\Moip $moip
     */
    public function __construct(Moip $moip)
    {
        $this->moip = $moip;
        $this->data = new stdClass();
        $this->initialize();
    }

    /**
     * Create a new connecttion.
     *
     * @return \Zabaala\Moip\Http\HTTPConnection
     */
    protected function createConnection()
    {
        return $this->moip->createConnection(new HTTPConnection());
    }

    /**
     * Get a key of an object if it exists.
     *
     * @param string         $key
     * @param \stdClass|null $data
     *
     * @return mixed
     */
    protected function getIfSet($key, stdClass $data = null)
    {
        if (empty($data)) {
            $data = $this->data;
        }

        if (isset($data->$key)) {
            return $data->$key;
        }

        return;
    }

    protected function getIfSetDateFmt($key, $fmt, stdClass $data = null)
    {
        $val = $this->getIfSet($key, $data);
        if (!empty($val)) {
            $dt = \DateTime::createFromFormat($fmt, $val);

            return $dt ? $dt : null;
        }

        return;
    }

    /**
     * Get a key, representing a date (Y-m-d), of an object if it exists.
     *
     * @param string        $key
     * @param stdClass|null $data
     *
     * @return \DateTime|null
     */
    protected function getIfSetDate($key, stdClass $data = null)
    {
        return $this->getIfSetDateFmt($key, 'Y-m-d', $data);
    }

    /**
     * Get a key representing a datetime (\Datetime::ATOM), of an object if it exists.
     *
     * @param string        $key
     * @param stdClass|null $data
     *
     * @return \DateTime|null
     */
    protected function getIfSetDateTime($key, stdClass $data = null)
    {
        return $this->getIfSetDateFmt($key, \DateTime::ATOM, $data);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * Transform resource in a Illuminate\Support\Collection.
     *
     * @param $array
     * @return Collection
     */
    protected function collect($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->collect($value);
                $array[$key] = $value;
            }
        }

        return Collection::make($array);

    }

    /**
     * Find by path.
     *
     * @param string $path
     * @param bool $all If true, get all data.
     * @return stdClass
     * @throws \Exception
     */
    public function getByPath($path, $all = false)
    {
        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');

        $httpResponse = $httpConnection->execute($path, HTTPRequest::GET);

        if ($httpResponse->getStatusCode() != 200 && $httpResponse->getStatusCode() != 201) {
            throw new \Exception($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        $responseContent = json_decode($httpResponse->getContent(), false);

        if($all) {
            return $responseContent;
        }

        return $this->collect($responseContent);
    }

    /**
     * Get all data by path.
     *
     * @param $path
     * @return stdClass
     */
    public function getAllByPath($path) {
        return $this->getByPath($path, true);
    }

    /**
     * Make a Moip Resource.
     *
     * @param string $path
     * @param string $method
     * @return stdClass
     * @throws \Exception
     */
    protected function makeResource($path, $method = HTTPRequest::POST)
    {
        $body = json_encode($this, JSON_UNESCAPED_SLASHES);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute($path, $method);

        $response = new MoipResponse($this, $httpResponse);

        if ($httpResponse->getStatusCode() != 200 && $httpResponse->getStatusCode() != 201) {
            $error = $response->byCode($httpResponse->getStatusCode());
            throw new \Exception($error->last(), $httpResponse->getStatusCode());
        }

        return !is_null($httpResponse->getContent()) && !empty($httpResponse->getContent())
            ? $this->collect(json_decode($httpResponse->getContent(), false))
            : null;
    }

    /**
     * Create a new Moip Resource.
     *
     * @param $path
     * @return stdClass
     */
    public function createResource($path) {
        return $this->makeResource($path);
    }

    /**
     * Update a Moip Resource.
     *
     * @param $path
     * @return stdClass
     */
    public function updateResource($path) {
        return $this->makeResource($path, HTTPRequest::PUT);
    }
}
