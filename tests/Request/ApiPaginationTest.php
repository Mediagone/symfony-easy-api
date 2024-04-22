<?php declare(strict_types=1);

namespace Tests\Mediagone\Symfony\EasyApi\Request;

use InvalidArgumentException;
use Mediagone\Symfony\EasyApi\EasyApi;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload200Success;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload201Created;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload202Accepted;
use Mediagone\Symfony\EasyApi\Request\ApiPagination;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use function array_slice;
use function ceil;
use function count;
use function json_decode;


/**
 * @covers Mediagone\Symfony\EasyApi\Request\ApiPagination
 */
final class ApiPaginationTest extends TestCase
{
    //========================================================================================================
    // 
    //========================================================================================================
    
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
    
    
    
    public function test_can_return_response() : void
    {
        $pagination = ApiPagination::create(2, 10, 33);
        
        self::assertSame(2, $pagination->getPage());
        self::assertSame(10, $pagination->getItemsPerPage());
        self::assertSame(33, $pagination->getItemsCountTotal());
        
        self::assertSame(4, $pagination->getPageCount());
        self::assertSame(10, $pagination->getPageItemOffset());
    }
    
    public function responseArrayResultProvider(): iterable
    {
        $objs = [
            ['name' => 'Result 1'],
            ['name' => 'Result 2'],
            ['name' => 'Result 3'],
        ];
        $objsSubset = array_slice($objs, 0, 2);
        $pagination = ApiPagination::create(1, count($objsSubset), count($objs));
        
        yield [$objs, $objsSubset, ApiPayload200Success::createWithArrayResult($objsSubset, $pagination), 200, 'ok'];
        yield [$objs, $objsSubset, ApiPayload201Created::createWithArrayResult($objsSubset, $pagination), 201, 'created'];
        yield [$objs, $objsSubset, ApiPayload202Accepted::createWithArrayResult($objsSubset, $pagination), 202, 'accepted'];
    }
    
    /**
     * @dataProvider responseArrayResultProvider
     */
    public function test_can_return_paginated_response($objs, $objsSubset, $responseObject, $responseCode, $responseMessage) : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() use($responseObject) {
            return $responseObject;
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame($responseCode, $response->getStatusCode());
        self::assertJson($response->getContent());
        
        $data = json_decode($response->getContent());
        self::assertObjectHasProperty('success', $data);
        self::assertTrue($data->success);
        self::assertObjectHasProperty('status', $data);
        self::assertSame($responseMessage, $data->status);
        self::assertObjectHasProperty('statusCode', $data);
        self::assertSame($responseCode, $data->statusCode);
        self::assertObjectHasProperty('payload', $data);
        self::assertIsObject($data->payload);
        
        self::assertObjectHasProperty('results', $data->payload);
        self::assertIsArray($data->payload->results);
        self::assertObjectHasProperty('resultsCount', $data->payload);
        self::assertSame(count($objsSubset), $data->payload->resultsCount, 'result count');
        self::assertObjectHasProperty('resultsCountTotal', $data->payload);
        self::assertSame(count($objs), $data->payload->resultsCountTotal);
        self::assertObjectHasProperty('page', $data->payload);
        self::assertSame(1, $data->payload->page);
        self::assertObjectHasProperty('pageCount', $data->payload);
        self::assertSame((int)ceil(count($objs) / count($objsSubset)), $data->payload->pageCount);
        foreach ($objsSubset as $key => $item) {
            self::assertObjectHasProperty('name', $data->payload->results[$key]);
            self::assertSame($item['name'], $data->payload->results[$key]->name);
        }
    }
    
    
    
}
