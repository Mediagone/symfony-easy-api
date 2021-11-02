<?php declare(strict_types=1);

namespace Tests\Mediagone\Symfony\EasyApi;

use LogicException;
use Mediagone\Symfony\EasyApi\EasyApi;
use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload200Success;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use function json_encode;


/**
 * @covers \Mediagone\Symfony\EasyApi\EasyApi
 */
final class EasyApiTest extends TestCase
{
    //========================================================================================================
    // 
    //========================================================================================================
    
    public function test_can_return_response() : void
    {
        $successResponse = ApiPayload200Success::create();
        
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() use($successResponse) {
            return $successResponse;
        });
        
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJson(json_encode($successResponse->getData(), JSON_THROW_ON_ERROR), $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
    
    
    public function test_generator_must_return_a_payload() : void
    {
        $successResponse = ApiPayload200Success::create();
        
        $easyApi = new EasyApi();
        $response = $easyApi->response(function() use($successResponse) {
            return 'invalid payload';
        });
        
        self::assertSame(500, $response->getStatusCode());
    }
    
    
    public function test_can_handle_request_error() : void
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
    
    
}
