<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Request;

use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;
use LogicException;
use Symfony\Component\HttpFoundation\RequestStack;
use function preg_match_all;


final class ApiQueryFilters
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private array $filters = [];
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        if ($request === null) {
            throw new LogicException('There is no current Http Request.');
        }
        
        $requestFilters = $request->query->get('filters') ?? '';
        $filters = [];
        
        if (preg_match_all('#(?:(?<filter>[-a-z0-9]+):(?<value>[^,]+))#i', $requestFilters, $filters, PREG_SET_ORDER) === false) {
            throw ApiBadRequestError::create('invalid_filter', 'Each filter must be coma separated and follow "<filter>:<value>" syntax.');
        }
        
        foreach ($filters as ['filter' => $filter, 'value' => $value]) {
            $this->filters[$filter] = $value;
        }
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function has(string $filter) : bool
    {
        return isset($this->filters[$filter]);
    }
    
    
    public function get(string $filter) : string
    {
        return $this->filters[$filter];
    }
    
    
    
}
