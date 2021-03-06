# Symfony EasyAPI

⚠️ This project is in experimental phase.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE)

This package provides helper classes to easily build a Json API from plain _Symfony_ controllers :
- Single and Collection results
- Custom filters
- Pagination



## Installation
This package requires **PHP 7.4+**.

Add it as Composer dependency:
```sh
$ composer require mediagone/symfony-easy-api
```


## Examples

The easiest way to build an API controller is to use the `EasyApi` class that will do error handling for you, however you can do it by hand if you prefer.


### Entity instance API endpoint

The `EasyApi->response` method accepts any callable argument that returns an `ApiPayload` instance.

```php
use App\Thing\ThingRepository;
use Mediagone\Symfony\EasyApi\EasyApi;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload200Success;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayloadError404NotFound;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/things/{thingId}", name="api_things", methods={"GET"})
 */
final class ApiEndpointController
{
    
    public function __invoke(int $thingId, EasyApi $easyApi, ThingRepository $thingRepository) : Response
    {
        return $easyApi->response(
            function() use ($thingId, $thingRepository)
            {
                $thing = $thingRepository->find($thingId);
                if ($thing === null) {
                    return ApiPayloadError404NotFound::create('Thing not found (id: '.$thingId.')');
                }
                
                return ApiPayload200Success::createWithSingleResult($thing);
            }
        );
    }
    
}
```

In case of success, the previous controller will return the following JSON object:
```json
{
    "success": true,
    "status": "ok",
    "statusCode": 200,
    "data": {
        "result": {
            "id": 1,
            "name": "First thing"
        }
    }
}
```
Or an error response:
```json
{
    "success": false,
    "status": "not_found",
    "statusCode": 404,
    "error": "not_found",
    "error_description": "Thing not found (id: 1)"
}
```



### Entity collection API endpoint

You can also return multiple results by using the `ApiPayload200Success::createWithArrayResult` factory method:

```php
/**
 * @Route("/api/things", name="api_things", methods={"GET"})
 */
final class ApiEndpointController
{
    
    public function __invoke(EasyApi $easyApi, ThingRepository $thingRepository) : Response
    {
        return $easyApi->response(
            function() use ($thingRepository)
            {
                $things = $thingRepository->findAll();
                return ApiPayload200Success::createWithArrayResult($things);
            }
        );
    }
    
}
```

It will result in a slightly different JSON object:
```json
{
    "success": true,
    "status": "ok",
    "statusCode": 200,
    "data": {
        "results": [
            { "id": 1, "name": "First thing" },
            { "id": 2, "name": "Second thing" },
            { "id": 3, "name": "Third thing" }
        ],
        "resultsCount": 3
    }
}
```



## License

_Symfony EasyAPI_ is licensed under MIT license. See LICENSE file.



[ico-version]: https://img.shields.io/packagist/v/mediagone/symfony-easy-api.svg
[ico-downloads]: https://img.shields.io/packagist/dt/mediagone/symfony-easy-api.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg

[link-packagist]: https://packagist.org/packages/mediagone/symfony-easy-api
[link-downloads]: https://packagist.org/packages/mediagone/symfony-easy-api
