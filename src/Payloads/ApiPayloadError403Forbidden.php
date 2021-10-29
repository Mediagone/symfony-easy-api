<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The server understood the request but refuses to authorize it.
 * 
 * A server that wishes to make public why the request has been forbidden can describe that reason in the response
 * payload (if any).
 * 
 * If authentication credentials were provided in the request, the server considers them insufficient to grant access.
 * The client SHOULD NOT automatically repeat the request with the same credentials. The client MAY repeat the request
 * with new or different credentials. However, a request might be forbidden for reasons unrelated to the credentials.
 * 
 * An origin server that wishes to "hide" the current existence of a forbidden target resource MAY instead respond with
 * a status code of 404 Not Found.
 */
final class ApiPayloadError403Forbidden extends ApiPayloadError
{
    
    public static function create(string $errorMessage) : self
    {
        return new self('forbidden', $errorMessage, 403, 'forbidden');
    }
    
}
