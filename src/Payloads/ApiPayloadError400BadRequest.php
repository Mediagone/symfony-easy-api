<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;


final class ApiPayloadError400BadRequest extends ApiPayloadError
{
    
    public static function create(ApiBadRequestError $error) : self
    {
        return new self("bad_request.{$error->getKey()}", $error->getMessage(), 400, 'bad_request');
    }
    
}
