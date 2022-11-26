<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;


/**
 * The server cannot or will not process the request due to something that is perceived to be a client error
 * (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
 */
final class ApiPayloadError400BadRequest extends ApiPayloadError
{
    
    public static function create(string $errorKey, string $errorMessage, array $headers = []) : self
    {
        return new self("bad_request.$errorKey", $errorMessage, 400, 'bad_request', $headers);
    }
    
    public static function createFromException(ApiBadRequestError $error, array $headers = []) : self
    {
        return self::create($error->getKey(), $error->getMessage(), $headers);
    }
    
}
