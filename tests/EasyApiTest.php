<?php declare(strict_types=1);

namespace Tests\Mediagone\Symfony\EasyApi;

use LogicException;
use Mediagone\Symfony\EasyApi\EasyApi;
use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload200Success;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload201Created;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload202Accepted;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use function count;
use function json_decode;


/**
 * @covers \Mediagone\Symfony\EasyApi\EasyApi
 */
final class EasyApiTest extends TestCase
{
    //========================================================================================================
    // 
    //========================================================================================================
    
    public function responseProvider(): iterable
    {
        yield [ApiPayload200Success::create(), 200, 'ok'];
        yield [ApiPayload201Created::create(), 201, 'created'];
        yield [ApiPayload202Accepted::create(), 202, 'accepted'];
    }
    
    /**
     * @dataProvider responseProvider
     */
    public function test_can_return_a_payload($responseObject, $responseCode, $responseMessage) : void
    {
        // $responseObject = ApiPayload200Success::create();
    
        /*ApiResultCollection::fromArray([1,2,'aa'])*/
        //$req = Request::create('http://localhost:8000/current-url?param=value');
        //$paginator = ApiPagination::from(22, 10, 2);
        
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() use($responseObject) {
            return $responseObject;
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame($responseCode, $response->getStatusCode());
        self::assertJson($response->getContent());
    
        $data = json_decode($response->getContent());
        self::assertObjectHasAttribute('success', $data);
        self::assertTrue($data->success);
        self::assertObjectHasAttribute('status', $data);
        self::assertSame($responseMessage, $data->status);
        self::assertObjectHasAttribute('statusCode', $data);
        self::assertSame($responseCode, $data->statusCode);
        self::assertObjectHasAttribute('payload', $data);
        self::assertNull($data->payload);
    }
    
    
    public function responseSingleResultProvider(): iterable
    {
        $obj = ['foo' => 'bar'];
        
        yield [$obj, ApiPayload200Success::createWithSingleResult($obj), 200, 'ok'];
        yield [$obj, ApiPayload201Created::createWithSingleResult($obj), 201, 'created'];
        yield [$obj, ApiPayload202Accepted::createWithSingleResult($obj), 202, 'accepted'];
    }
    
    /**
     * @dataProvider responseSingleResultProvider
     */
    public function test_can_return_a_payload_with_single_result($obj, $responseObject, $responseCode, $responseMessage) : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() use($responseObject) {
            return $responseObject;
        });
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame($responseCode, $response->getStatusCode());
        self::assertJson($response->getContent());
        
        $data = json_decode($response->getContent());
        self::assertObjectHasAttribute('success', $data);
        self::assertTrue($data->success);
        self::assertObjectHasAttribute('status', $data);
        self::assertSame($responseMessage, $data->status);
        self::assertObjectHasAttribute('statusCode', $data);
        self::assertSame($responseCode, $data->statusCode);
        self::assertObjectHasAttribute('payload', $data);
        self::assertIsObject($data->payload);
        self::assertObjectHasAttribute('result', $data->payload);
        self::assertIsObject($data->payload->result);
        self::assertObjectHasAttribute('foo', $data->payload->result);
        self::assertSame('bar', $data->payload->result->foo);
    }
    
    
    public function responseArrayResultProvider(): iterable
    {
        $objs = [
            ['name' => 'Result 1'],
            ['name' => 'Result 2'],
        ];
        
        yield [$objs, ApiPayload200Success::createWithArrayResult($objs), 200, 'ok'];
        yield [$objs, ApiPayload201Created::createWithArrayResult($objs), 201, 'created'];
        yield [$objs, ApiPayload202Accepted::createWithArrayResult($objs), 202, 'accepted'];
    }
    
    /**
     * @dataProvider responseArrayResultProvider
     */
    public function test_can_return_a_payload_with_array_result($objs, $responseObject, $responseCode, $responseMessage) : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() use($responseObject) {
            return $responseObject;
        });
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame($responseCode, $response->getStatusCode());
        self::assertJson($response->getContent());
        
        $data = json_decode($response->getContent());
        self::assertObjectHasAttribute('success', $data);
        self::assertTrue($data->success);
        self::assertObjectHasAttribute('status', $data);
        self::assertSame($responseMessage, $data->status);
        self::assertObjectHasAttribute('statusCode', $data);
        self::assertSame($responseCode, $data->statusCode);
        self::assertObjectHasAttribute('payload', $data);
        self::assertIsObject($data->payload);
        
        self::assertObjectHasAttribute('results', $data->payload);
        self::assertIsArray($data->payload->results);
        self::assertObjectHasAttribute('resultsCount', $data->payload);
        self::assertSame(count($objs), $data->payload->resultsCount);
        self::assertObjectHasAttribute('resultsCountTotal', $data->payload);
        self::assertSame(count($objs), $data->payload->resultsCountTotal);
        self::assertObjectHasAttribute('page', $data->payload);
        self::assertSame(1, $data->payload->page);
        self::assertObjectHasAttribute('pageCount', $data->payload);
        self::assertSame(1, $data->payload->pageCount);
        foreach ($objs as $key => $item) {
            self::assertObjectHasAttribute('name', $data->payload->results[$key]);
            self::assertSame($item['name'], $data->payload->results[$key]->name);
        }
    }
    
    
    public function test_generator_must_return_a_valid_payload() : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() {
            return 'invalid payload';
        });
        
        self::assertSame(500, $response->getStatusCode());
    }
    
    
    public function test_can_handle_bad_request_error() : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() {
            throw ApiBadRequestError::create('some_request_error', 'The callback must return an ApiPayload instance.');
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(400, $response->getStatusCode());
    }
    
    
    public function test_can_handle_error() : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() {
            throw new LogicException('Oops, something happened.');
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(500, $response->getStatusCode());
    }
    
    
    public function test_can_define_default_headers() : void
    {
        $easyApi = new EasyApi(['Accept-Charset' => 'utf-8']);
        
        /** @var JsonResponse $response */
        $response = $easyApi->response(function () {
            return ApiPayload200Success::create();
        });
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('utf-8', $response->headers->get('Accept-Charset'));
        
        /** @var JsonResponse $response */
        $response = $easyApi->response(function () {
            return ApiPayload201Created::create();
        });
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(201, $response->getStatusCode());
        self::assertSame('utf-8', $response->headers->get('Accept-Charset'));
    }
    
    
    
}
