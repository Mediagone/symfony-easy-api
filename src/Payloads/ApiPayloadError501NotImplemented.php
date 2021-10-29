<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The server does not support the functionality required to fulfill the request.
 * 
 * This is the appropriate response when the server does not recognize the request method and is not capable of
 * supporting it for any resource.
 * 
 * A 501 response is cacheable by default; i.e., unless otherwise indicated by the method definition or explicit
 * cache controls1.
 */
final class ApiPayloadError501NotImplemented extends ApiPayloadError
{
    
    public static function create() : self
    {
        return new self('not_implemented', "The requested functionality is not implemented.", 501, 'not_implemented');
    }
    
}
