<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


abstract class ApiPayloadError implements ApiPayload
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $status;
    
    private int $statusCode;
    
    private string $errorKey;
    
    private string $errorDescription;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    protected function __construct(string $errorKey, string $errorDescription, int $statusCode, string $status)
    {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->errorKey = $errorKey;
        $this->errorDescription = $errorDescription;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    final public function getCode() : int
    {
        return $this->statusCode;
    }
    
    
    final public function getData() : array
    {
        return [
            'success' => false,
            'status' => $this->status,
            'statusCode' => $this->statusCode,
            'error' => $this->errorKey,
            'error_description' => $this->errorDescription,
        ];
    }
    
    
    
}
