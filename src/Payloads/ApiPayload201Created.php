<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResult;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultCollection;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultNull;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultSingle;
use Mediagone\Symfony\EasyApi\Request\ApiPagination;


/**
 * The request has been fulfilled and has resulted in one or more new resources being created.
 * 
 * The primary resource created by the request is identified by either a Location header field in the response or, if no
 * Location field is received, by the effective request URI.
 * 
 * The 201 response payload typically describes and links to the resource(s) created. See Section 7.2 of RFC7231 for a
 * discussion of the meaning and purpose of validator header fields, such as ETag and Last-Modified, in a 201 response.
 */
final class ApiPayload201Created implements ApiPayload
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private ApiResult $result;
    
    private array $headers;
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(ApiResult $result, array $headers = [])
    {
        $this->result = $result;
        $this->headers = $headers;
    }
    
    
    public static function create(array $headers = []) : self
    {
        return new self(ApiResultNull::create(), $headers);
    }
    
    
    public static function createWithSingleResult($result, array $headers = []) : self
    {
        return new self(ApiResultSingle::create($result), $headers);
    }
    
    
    public static function createWithArrayResult(array $results, ?ApiPagination $paginator = null, array $headers = []) : self
    {
        return new self(ApiResultCollection::create($results, $paginator), $headers);
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
            'statusCode' => 201,
            'payload' => $this->result,
        ];
    }
    
    
    public function getHeaders() : array
    {
        return $this->headers;
    }
    
    
    
}
