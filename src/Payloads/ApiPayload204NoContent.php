<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


/**
 * The server has successfully fulfilled the request and that there is no additional content to send in the response
 * payload body.
 * 
 * The 204 response allows a server to indicate that the action has been successfully applied to the target resource,
 * while implying that the user agent does not need to traverse away from its current "document view" (if any).
 * The server assumes that the user agent will provide some indication of the success to its user, in accord with its
 * own interface, and apply any new or updated metadata in the response to its active representation.
 * 
 * For example, a 204 status code is commonly used with document editing interfaces corresponding to a "save" action,
 * such that the document being saved remains available to the user for editing. It is also frequently used with
 * interfaces that expect automated data transfers to be prevalent, such as within distributed version control systems.
 */
final class ApiPayload204NoContent implements ApiPayload
{
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct()
    {
        
    }
    
    
    public static function create() : self
    {
        return new self();
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function getCode() : int
    {
        return 204;
    }
    
    
    public function getData() : array
    {
        return [
            'success' => true,
            'status' => 'no_content',
            'status_code' => 204,
        ];
    }
    
    
    
}
