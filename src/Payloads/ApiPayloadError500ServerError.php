<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Throwable;


/**
 * The server encountered an unexpected condition that prevented it from fulfilling the request.
 */
final class ApiPayloadError500ServerError extends ApiPayloadError
{
    
    public static function create(string $errorMessage, array $headers = []) : self
    {
        return new self('server_error', "Unexpected server error: $errorMessage", 500, 'server_error', $headers);
    }
    
    public static function createFromException(Throwable $error, array $headers = []) : self
    {
        return self::create($error->getMessage(), $headers);
    }
    
}
