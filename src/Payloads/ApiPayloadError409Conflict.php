<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


final class ApiPayloadError409Conflict extends ApiPayloadError
{
    
    public static function create(string $errorKey, string $errorMessage) : self
    {
        return new self($errorKey, $errorMessage, 409, 'conflict');
    }
    
}
