<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Throwable;


/**
 * The server encountered an unexpected condition that prevented it from fulfilling the request.
 */
final class ApiPayloadError500ServerError extends ApiPayloadError
{
    
    public static function create(Throwable $error) : self
    {
        return new self('server_error', "Unexpected server error: {$error->getMessage()}", 500, 'server_error');
    }
    
}
