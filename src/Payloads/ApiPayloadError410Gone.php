<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The target resource is no longer available at the origin server and that this condition is likely to be permanent.
 * If the origin server does not know, or has no facility to determine, whether or not the condition is permanent,
 * the status code 404 Not Found ought to be used instead.
 * 
 * The 410 response is primarily intended to assist the task of web maintenance by notifying the recipient that the
 * resource is intentionally unavailable and that the server owners desire that remote links to that resource be
 * removed. Such an event is common for limited-time, promotional services and for resources belonging to individuals
 * no longer associated with the origin server's site. It is not necessary to mark all permanently unavailable
 * resources as "gone" or to keep the mark for any length of time -- that is left to the discretion of the server owner.
 * 
 * A 410 response is cacheable by default; i.e., unless otherwise indicated by the method definition or explicit cache
 * controls.
 */
final class ApiPayloadError410Gone extends ApiPayloadError
{
    
    public static function create(string $errorKey, string $errorMessage) : self
    {
        return new self($errorKey, $errorMessage, 410, 'gone');
    }
    
}
