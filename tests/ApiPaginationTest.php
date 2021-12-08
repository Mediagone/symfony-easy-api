<?php declare(strict_types=1);

namespace Tests\Mediagone\Symfony\EasyApi;

use InvalidArgumentException;
use LogicException;
use Mediagone\Symfony\EasyApi\EasyApi;
use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload200Success;
use Mediagone\Symfony\EasyApi\Request\ApiPagination;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use function json_encode;


/**
 * @covers Mediagone\Symfony\EasyApi\Request\ApiPagination
 */
final class ApiPaginationTest extends TestCase
{
    //========================================================================================================
    // 
    //========================================================================================================
    
    public function test_can_return_response() : void
    {
        $pagination = ApiPagination::create(2, 10, 33);
        
        self::assertSame(2, $pagination->getPage());
        self::assertSame(10, $pagination->getItemsPerPage());
        self::assertSame(33, $pagination->getItemsCountTotal());
        
        self::assertSame(4, $pagination->getPageCount());
        self::assertSame(10, $pagination->getPageItemOffset());
    }
    
    
    public function test_throw_exception_on_invalid_page() : void
    {
        $this->expectException(InvalidArgumentException::class);
        
        ApiPagination::create(0, 10, 10);
    }
    
    
    public function test_throw_exception_on_invalid_item_per_page() : void
    {
        $this->expectException(InvalidArgumentException::class);
        
        ApiPagination::create(1, 0, 10);
    }
    
    
    public function test_throw_exception_on_invalid_item_count() : void
    {
        $this->expectException(InvalidArgumentException::class);
        
        ApiPagination::create(1, 10, -1);
    }
    
    
    
}
