<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Request;

use InvalidArgumentException;
use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;
use function ceil;


final class ApiPagination
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $itemsCountTotal;
    
    public function getItemsCountTotal() : int
    {
        return $this->itemsCountTotal;
    }
    
    
    private int $itemsPerPage;
    
    public function getItemsPerPage() : int
    {
        return $this->itemsPerPage;
    }
    
    
    private int $page;
    
    public function getPage() : int
    {
        return $this->page;
    }
    
    
    private int $pageCount;
    
    public function getPageCount() : int
    {
        return $this->pageCount;
    }
    
    
    private int $pageItemOffset;
    
    public function getPageItemOffset() : int
    {
        return $this->pageItemOffset;
    }
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(int $page, int $itemPerPage, int $itemsCountTotal)
    {
        if ($page < 1) {
            throw new InvalidArgumentException('Page number must be a strictly positive number (greater than zero), got "'.$page.'".');
        }
        if ($itemPerPage < 1) {
            throw new InvalidArgumentException('Items per page count must be a strictly positive number (greater than zero), got "'.$itemPerPage.'".');
        }
        if ($itemsCountTotal < 0) {
            throw new InvalidArgumentException('Total items count must be a positive or zero number, got "'.$itemsCountTotal.'".');
        }
    
        $this->itemsPerPage = $itemPerPage;
        $this->itemsCountTotal = $itemsCountTotal;
        $this->page = $page;
        $this->pageCount = (int)ceil($itemsCountTotal / $this->itemsPerPage);
        $this->pageItemOffset = ($page - 1) * $itemPerPage;
        
        if ($page > $this->pageCount) {
            throw ApiBadRequestError::create('invalid_page', 'You requested the page '.$page.', but '.$this->pageCount.' is the last one.');
        }
    }
    
    
    public static function create(int $currentPage, int $itemPerPage, int $totalItemsCount) : self
    {
        return new self($currentPage, $itemPerPage, $totalItemsCount);
    }
    
    
    
}
