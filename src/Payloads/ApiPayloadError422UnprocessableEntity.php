<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The server understands the content type of the request entity (hence a 415 Unsupported Media Type status code is
 * inappropriate), and the syntax of the request entity is correct (thus a 400 Bad Request status code is inappropriate)
 * but was unable to process the contained instructions.
 * 
 * For example, this error condition may occur if an XML request body contains well-formed (i.e., syntactically
 * correct), but semantically erroneous, XML instructions.
 */
final class ApiPayloadError422UnprocessableEntity extends ApiPayloadError
{
    
    public static function create(string $errorMessage) : self
    {
        return new self('unprocessable_entity', $errorMessage, 422, 'Unprocessable entity');
    }
    
}
