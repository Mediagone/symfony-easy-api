<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResult;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultCollection;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultNull;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultSingle;
use Mediagone\Symfony\EasyApi\Request\ApiPagination;


/*
 * The request has succeeded.
 * 
 * The payload sent in a 200 response depends on the request method. For the methods defined by this specification, the
 * intended meaning of the payload can be summarized as:
 *    GET a representation of the target resource
 *    HEAD the same representation as GET, but without the representation data
 *    POST a representation of the status of, or results obtained from, the action;
 *    PUT DELETE a representation of the status of the action;
 *    OPTIONS a representation of the communications options;
 *    TRACE a representation of the request message as received by the end server.
 */
final class ApiPayload200Success implements ApiPayload
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
    
    
    public static function createWithArrayResult(array $results, ?ApiPagination $paginator = null) : self
    {
        return new self(ApiResultCollection::create($results, $paginator));
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function getCode() : int
    {
        return 200;
    }
    
    
    public function getData() : array
    {
        return [
            'success' => true,
            'status' => 'ok',
            'statusCode' => 200,
            'data' => $this->result,
        ];
    }
    
    
    
}
