<?php

namespace Zabaala\Moip\Resource;

use stdClass;

class Payment extends MoipResource
{
    /**
     * Path for all invoices.
     *
     * @const string
     */
    const PATH_ALL = 'assinaturas/v1/invoices/{{invoice_code}}/payments';

    /**
     * Path to a specified invoice.
     *
     * @const string
     */
    const PATH = 'assinaturas/v1/payments';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_BOLETO = 'BOLETO';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_ONLINE_DEBIT = 'ONLINE_DEBIT';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_WALLET = 'WALLET';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_ONLINE_BANK_DEBIT = 'ONLINE_BANK_DEBIT';

    /**
     * Subscription code.
     *
     * @var string
     */
    protected $invoice_code;

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Set Subscription code.
     *
     * @param $code
     */
    public function setInvoiceCode($code) {
        $this->invoice_code = $code;
    }

    /**
     * Find all Invoices.
     *
     * @return stdClass
     */
    public function all()
    {
        return $this->getByPath(sprintf('/%s', str_replace('{{invoice_code}}', $this->invoice_code, self::PATH_ALL)), true);
    }

    /**
     * Find a Invoice.
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
     * @param stdClass $response
     * @return stdClass
     */
    public function populate(stdClass $response) {
        $this->data = $response;
        return $this->data;
    }

}
