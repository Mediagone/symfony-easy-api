<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi;

use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayloadError400BadRequest;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayloadError500ServerError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;


final class EasyApi
{
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function response(callable $payloadGenerator) : Response
    {
        try {
            $payload = $payloadGenerator();
        }
        catch (ApiBadRequestError $ex) {
            $payload = ApiPayloadError400BadRequest::create($ex);
        }
        catch (Throwable $ex) {
            $payload = ApiPayloadError500ServerError::create($ex);
        }
        
        return new JsonResponse($payload->getData(), $payload->getCode());
    }
    
    
    
}
