<?php

namespace Zabaala\Moip\Resource;


use Zabaala\Moip\MoipError;

class MoipResponse {

    /**
     * A MoipResponse instance.
     *
     * @var \Zabaala\Moip\Resource\MoipResponse
     */
    protected $resource;

    /**
     * Moip HTTP Request Response.
     *
     * @var string
     */
    protected $httpResponse;

    /**
     * MoipError instance.
     *
     * @var MoipError
     */
    protected $error;

    /**
     * Constructor.
     *
     * @param MoipResource $resource
     * @param $httpResponse
     */
    public function __construct(MoipResource $resource, $httpResponse) {

        $this->resource = $resource;
        $this->httpResponse = $httpResponse;

        $this->error = new MoipError();
    }

    /**
     * Make 200 response.
     *
     * @return boolean
     */
    protected function code200() {
        return true;
    }

    /**
     * Make 201 response.
     *
     * @return boolean
     */
    protected function code201(){
        return true;
    }

    /**
     * Make 400 response.
     *
     * @return MoipError
     */
    protected function code400() {
        $this->error->pushMultiple($this->httpResponse->getContent());
        return $this->error;
    }

    /**
     * Make 404 response.
     *
     * @return MoipError
     */
    protected function code404() {
        $this->error->push('404', 'Moip says: Path not found on this server. Please your request and try again.');
        return $this->error;
    }

    /**
     * Make 405 response code implementation.
     *
     * @return MoipError
     */
    protected function code405() {
        $this->error->push('405', 'Moip says: Method/Verb not allowed.');
        return $this->error;
    }

    /**
     * Trait Moip Response by code.
     *
     * @param $code
     */
    public function byCode($code) {
        $functionName = 'code' . $code;
        return $this->$functionName();
    }

    public function getResponse() {
        return $this->httpResponse;
    }

    /**
     * @return MoipError
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Create a custom not found function exception.
     *
     * @param $name
     * @param $args
     */
    public function __call($name, $args) {
        throw new \BadFunctionCallException(sprintf("Function %s not found on the current context.", $name));
    }

}