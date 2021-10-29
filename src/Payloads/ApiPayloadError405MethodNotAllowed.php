<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The method received in the request-line is known by the origin server but not supported by the target resource.
 * 
 * The origin server MUST generate an Allow header field in a 405 response containing a list of the target resource's currently supported methods.
 * 
 * A 405 response is cacheable by default; i.e., unless otherwise indicated by the method definition or explicit cache controls.
 */
final class ApiPayloadError405MethodNotAllowed extends ApiPayloadError
{
    
    public static function create(string $method) : self
    {
        return new self('method_not_allowed', "Http method \"$method\" not allowed.", 405, 'method_not_allowed');
    }
    
}
