<?php

namespace Zabaala\Moip\Http;

/**
 * Interface para definição de um autenticador HTTP.
 */
interface HTTPAuthenticator
{
    /**
     * Autentica uma requisição HTTP.
     *
     * @param \Zabaala\Moip\Http\HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest);
}
