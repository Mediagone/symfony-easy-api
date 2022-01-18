<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * Any attempt to brew coffee with a teapot should result in the error code "418 I'm a teapot".
 * The resulting entity body MAY be short and stout.
 */
final class ApiPayloadError418IAmATeapot extends ApiPayloadError
{
    
    public static function create(string $type) : self
    {
        return new self('i_am_a_teapot', "I'm a teapot", 418, 'i_am_a_teapot');
    }
    
}
