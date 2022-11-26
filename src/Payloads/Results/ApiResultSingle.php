<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads\Results;


final class ApiResultSingle implements ApiResult
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private $result;
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct($result)
    {
        $this->result = $result;
    }
    
    
    public static function create($result) : self
    {
        return new self($result);
    }
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'result' => $this->result,
        ];
    }
    
    
    
}
