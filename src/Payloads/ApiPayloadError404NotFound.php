<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


final class ApiPayloadError404NotFound extends ApiPayloadError
{
    
    public static function create(string $errorMessage) : self
    {
        return new self('not_found', $errorMessage, 404, 'not_found');
    }
    
}
