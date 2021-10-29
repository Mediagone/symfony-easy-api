<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The origin server did not find a current representation for the target resource or is not willing to disclose
 * that one exists.
 * 
 * A 404 status code does not indicate whether this lack of representation is temporary or permanent; the 410 Gone
 * status code is preferred over 404 if the origin server knows, presumably through some configurable means, that
 * the condition is likely to be permanent.
 * 
 * A 404 response is cacheable by default; i.e., unless otherwise indicated by the method definition or explicit
 * cache controls.
 */
final class ApiPayloadError404NotFound extends ApiPayloadError
{
    
    public static function create(string $errorMessage) : self
    {
        return new self('not_found', $errorMessage, 404, 'not_found');
    }
    
}
