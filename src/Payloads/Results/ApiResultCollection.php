<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads\Results;

use function count;


final class ApiResultCollection implements ApiResult
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private array $results;

    private int $resultsCount;
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(array $results)
    {
        $this->results = $results;
        $this->resultsCount = count($results);
    }
    
    
    public static function create(array $results) : self
    {
        return new self($results);
    }
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return [
            'results' => $this->results,
            'resultsCount' => $this->resultsCount,
        ];
    }
    
    
    
}
