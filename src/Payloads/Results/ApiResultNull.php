<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads\Results;


final class ApiResultNull implements ApiResult
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
    // Constructors
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return null;
    }
    
    
    
}
