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
use function property_exists;


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
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() use($responseObject) {
            return $responseObject;
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame($responseCode, $response->getStatusCode());
        self::assertJson($response->getContent());
    
        $data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
        self::assertTrue(property_exists($data, 'success'));
        self::assertTrue($data->success);
        self::assertTrue(property_exists($data, 'status'));
        self::assertSame($responseMessage, $data->status);
        self::assertTrue(property_exists($data, 'statusCode'));
        self::assertSame($responseCode, $data->statusCode);
        self::assertTrue(property_exists($data, 'payload'));
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
        
        $data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
        self::assertTrue(property_exists($data, 'success'));
        self::assertTrue($data->success);
        self::assertTrue(property_exists($data, 'status'));
        self::assertSame($responseMessage, $data->status);
        self::assertTrue(property_exists($data, 'statusCode'));
        self::assertSame($responseCode, $data->statusCode);
        self::assertTrue(property_exists($data, 'payload'));
        self::assertIsObject($data->payload);
        self::assertTrue(property_exists($data->payload, 'result'));
        self::assertIsObject($data->payload->result);
        self::assertTrue(property_exists($data->payload->result, 'foo'));
        self::assertSame('bar', $data->payload->result->foo);
    }
    
    
    public function responseArrayResultProvider(): iterable
    {
        $objs = [
            ['name' => 'Result 1', 'value' => 1],
            ['name' => 'Result 2', 'value' => 2],
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
        
        $data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
        self::assertTrue(property_exists($data, 'success'));
        self::assertTrue($data->success);
        self::assertTrue(property_exists($data, 'status'));
        self::assertSame($responseMessage, $data->status);
        self::assertTrue(property_exists($data, 'statusCode'));
        self::assertSame($responseCode, $data->statusCode);
        self::assertTrue(property_exists($data, 'payload'));
        self::assertIsObject($data->payload);
        
        self::assertTrue(property_exists($data->payload, 'results'));
        self::assertIsArray($data->payload->results);
        self::assertTrue(property_exists($data->payload, 'resultsCount'));
        self::assertSame(count($objs), $data->payload->resultsCount);
        self::assertTrue(property_exists($data->payload, 'resultsCountTotal'));
        self::assertSame(count($objs), $data->payload->resultsCountTotal);
        self::assertTrue(property_exists($data->payload, 'page'));
        self::assertSame(1, $data->payload->page);
        self::assertTrue(property_exists($data->payload, 'pageCount'));
        self::assertSame(1, $data->payload->pageCount);
        foreach ($objs as $key => $item) {
            self::assertTrue(property_exists($data->payload->results[$key], 'name'));
            self::assertSame($item['name'], $data->payload->results[$key]->name);
            self::assertTrue(property_exists($data->payload->results[$key], 'value'));
            self::assertSame($item['value'], $data->payload->results[$key]->value);
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
        $content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
        self::assertFalse($content->success);
        self::assertSame('bad_request', $content->status);
        self::assertSame(400, $content->statusCode);
        self::assertSame('bad_request.some_request_error', $content->error);
        self::assertSame('The callback must return an ApiPayload instance.', $content->errorDescription);
        self::assertSame(0, $content->errorCode);
    }
    
    
    public function test_can_handle_error() : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() {
            throw new LogicException('Oops, something happened.');
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(500, $response->getStatusCode());
        $content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
        self::assertFalse($content->success);
        self::assertSame('server_error', $content->status);
        self::assertSame(500, $content->statusCode);
        self::assertSame('server_error', $content->error);
        self::assertSame('Unexpected server error: Oops, something happened.', $content->errorDescription);
        self::assertSame(0, $content->errorCode);
    }
    
    
    public function test_can_handle_error_with_code() : void
    {
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() {
            throw new LogicException('Oops, something happened.', 123);
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(500, $response->getStatusCode());
        $content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
        self::assertFalse($content->success);
        self::assertSame('server_error', $content->status);
        self::assertSame(500, $content->statusCode);
        self::assertSame('server_error', $content->error);
        self::assertSame('Unexpected server error: Oops, something happened.', $content->errorDescription);
        self::assertSame(123, $content->errorCode);
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
