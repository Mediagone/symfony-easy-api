<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The origin server is refusing to service the request because the payload is in a format not supported by this
 * method on the target resource.
 * 
 * The format problem might be due to the request's indicated Content-Type or Content-Encoding, or as a result of
 * inspecting the data directly.
 */
final class ApiPayloadError415UnsupportedMediaType extends ApiPayloadError
{
    
    public static function create(string $type, array $headers = []) : self
    {
        return new self('unsupported_media_type', "Unsupported media type ($type)", 415, 'unsupported_media_type', $headers);
    }
    
}
