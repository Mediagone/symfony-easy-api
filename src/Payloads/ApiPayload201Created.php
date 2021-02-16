<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Mediagone\Symfony\EasyApi\Payloads\Results\ApiPagination;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResult;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultCollection;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultNull;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultSingle;


final class ApiPayload201Created implements ApiPayload
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private ApiResult $result;
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(ApiResult $result)
    {
        $this->result = $result;
    }
    
    
    public static function create() : self
    {
        return new self(ApiResultNull::create());
    }
    
    
    public static function createWithSingleResult($result) : self
    {
        return new self(ApiResultSingle::create($result));
    }
    
    
    public static function createWithArrayResult(array $results) : self
    {
        return new self(ApiResultCollection::create($results));
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function getCode() : int
    {
        return 201;
    }
    
    
    public function getData() : array
    {
        return [
            'success' => true,
            'status' => 'created',
            'status_code' => 201,
            'data' => $this->result,
        ];
    }
    
    
    
}
