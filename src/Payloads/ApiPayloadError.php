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
    
    private int $errorCode;
    
    private string $errorDescription;
    
    private array $headers;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    protected function __construct(string $errorKey, string $errorDescription, int $statusCode, string $status, array $headers = [], int $errorCode = 0)
    {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->errorKey = $errorKey;
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
        $this->headers = $headers;
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
            'errorDescription' => $this->errorDescription,
            'errorCode' => $this->errorCode,
        ];
    }
    
    
    public function getHeaders() : array
    {
        return $this->headers;
    }
    
    
    
}
