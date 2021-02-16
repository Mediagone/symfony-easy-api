<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi;

use LogicException;
use Mediagone\Symfony\EasyApi\Errors\ApiBadRequestError;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload;
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
            
            if (! $payload instanceof ApiPayload) {
                throw new LogicException('The callback must return an ApiPayload instance.');
            }
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
