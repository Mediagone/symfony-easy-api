<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads\Results;

use Mediagone\Symfony\EasyApi\Request\ApiPagination;
use function count;


final class ApiResultCollection implements ApiResult
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private array $results;

    private int $resultsCount;
    
    private int $resultsCountTotal;
    
    private int $page;
    
    private int $pageCount;
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(array $results, ?ApiPagination $paginator)
    {
        $this->results = $results;
        $this->resultsCount = count($results);
        
        if ($paginator !== null) {
            $this->page = $paginator->getPage();
            $this->pageCount = $paginator->getPageCount();
            $this->resultsCountTotal = $paginator->getItemsCountTotal();
        }
        else {
            $this->page = 1;
            $this->pageCount = 1;
            $this->resultsCountTotal = count($results);
        }
    }
    
    
    public static function create(array $results, ?ApiPagination $paginator) : self
    {
        return new self($results, $paginator);
    }
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return [
            'results' => $this->results,
            'resultsCount' => $this->resultsCount,
            'resultsCountTotal' => $this->resultsCountTotal,
            'page' => $this->page,
            'pageCount' => $this->pageCount,
        ];
    }
    
    
    
}
