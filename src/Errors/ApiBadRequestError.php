<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Errors;

use RuntimeException;


final class ApiBadRequestError extends RuntimeException
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $key;
    
    public function getKey() : string
    {
        return $this->key;
    }
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(string $key, string $message)
    {
        $this->key = $key;
        parent::__construct($message);
    }
    
    
    public static function create(string $key, string $message) : self
    {
        return new self($key, $message);
    }
    
    
    
}
