<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


final class ApiPayloadError405MethodNotAllowed extends ApiPayloadError
{
    
    public static function create(string $method) : self
    {
        return new self('method_not_allowed', "Http method \"$method\" not allowed.", 405, 'method_not_allowed');
    }
    
}
