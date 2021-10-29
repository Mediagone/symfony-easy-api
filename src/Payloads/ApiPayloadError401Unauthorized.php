<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The request has not been completed because it lacks valid authentication credentials for the target resource.
 * 
 * The server generating a 401 response MUST send a WWW-Authenticate header field1 containing at least one challenge
 * applicable to the target resource.
 * 
 * If the request included authentication credentials, then the 401 response indicates that authorization has been
 * refused for those credentials. The user agent MAY repeat the request with a new or replaced Authorization 
 * header field.
 * If the 401 response contains the same challenge as the prior response, and the user agent has already attempted
 * authentication at least once, then the user agent SHOULD present the enclosed representation to the user, since
 * it usually contains relevant diagnostic information.
 */
final class ApiPayloadError401Unauthorized extends ApiPayloadError
{
    
    public static function create(string $errorMessage) : self
    {
        return new self('unauthorized', $errorMessage, 401, 'unauthorized');
    }
    
}
