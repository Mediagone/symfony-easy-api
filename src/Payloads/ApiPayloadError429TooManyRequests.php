<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The user has sent too many requests in a given amount of time ("rate limiting").
 * 
 * The response representations SHOULD include details explaining the condition, and MAY include
 * a Retry-After header indicating how long to wait before making a new request.
 */
final class ApiPayloadError429TooManyRequests extends ApiPayloadError
{
    
    public static function create(string $errorMessage) : self
    {
        return new self('too_many_requests', $errorMessage, 429, 'Too Many Requests');
    }
    
}
