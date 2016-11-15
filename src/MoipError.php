<?php

namespace Zabaala\Moip;

use stdClass;

class MoipError
{
    /**
     * Indicate if the Moip HTTP Request has error.
     *
     * @var bool
     */
    protected $hasError = false;

    /**
     * List of all errors.
     *
     * @var mixed
     */
    protected $errors;

    /**
     * Make a single MoipError.
     *
     * @param $code
     * @param $description
     */
    public function push($code, $description) {

        $this->hasError = true;

        $err = new stdClass();
        $err->code = $code;
        $err->description = $description;

        $this->errors[] = $err;

    }

    /**
     * Push multiples MoipErrors.
     *
     * @param $errors
     */
    public function pushMultiple($errors) {

        $errors = json_decode($errors);
        $errors = $errors->errors;

        if( !count($errors) ){
            return ;
        }

        $this->hasError = true;
        $this->errors   = $errors;

    }

    /**
     * Check if has error.
     *
     * @return bool
     */
    public function has() {
        return $this->hasError;
    }

    /**
     * Get last error description
     *
     * @param bool $withCode
     * @return string
     */
    public function last($withCode = false) {
        return $this->getByPosition('last', $withCode);
    }

    /**
     * Get first error description
     *
     * @param bool $withCode
     * @return string
     */
    public function first($withCode = false) {
        return $this->getByPosition('first', $withCode);
    }

    /**
     * Get all errors.
     *
     * @return mixed
     */
    public function all() {
        return $this->errors;
    }

    /**
     * Get error description by position.
     *
     * @param string $position
     * @param bool $withCode
     * @return string
     */
    protected function getByPosition($position = 'first', $withCode = false) {

        if($this->has()) {
            switch($position) {
                case 'first':
                    $error = $this->errors[0];
                    break;

                case 'last':
                case 'end':
                    $error = end($this->errors);
                    break;

                default:
                    $error = $this->errors[0];
                    break;
            }

            return ($withCode ? $error->code . ' - ' : '') . $error->description;
        }

        return '';

    }

    /**
     * Becomes MoipError to string getting the first error.
     *
     * @return string
     */
    public function __toString() {
        return $this->first();
    }

}