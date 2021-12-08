<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;

use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResult;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultCollection;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultNull;
use Mediagone\Symfony\EasyApi\Payloads\Results\ApiResultSingle;
use Mediagone\Symfony\EasyApi\Request\ApiPagination;


/**
 * The request has been accepted for processing, but the processing has not been completed. The request might or might
 * not eventually be acted upon, as it might be disallowed when processing actually takes place.
 * 
 * The 202 response is intentionally noncommittal. Its purpose is to allow a server to accept a request for some other
 * process (perhaps a batch-oriented process that is only run once per day) without requiring that the user agent's
 * connection to the server persist until the process is completed. The representation sent with this response ought
 * to describe the request's current status and point to (or embed) a status monitor that can provide the user with
 * an estimate of when the request will be fulfilled.
 */
final class ApiPayload202Accepted implements ApiPayload
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
        return 202;
    }
    
    
    public function getData() : array
    {
        return [
            'success' => true,
            'status' => 'accepted',
            'status_code' => 202,
            'data' => $this->result,
        ];
    }
    
    
    
}
