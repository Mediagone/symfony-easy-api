# Symfony EasyAPI

⚠️ This project is in experimental phase.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE)

This package provides helper classes to build a Json API very easily from plain _Symfony_ controllers. \
Supported features :
- Single and Collection results
- Pagination for Collection results
- Out of the box support for most useful status codes (200, 201, 202, 204, 400, 401, 403, 404, 405, 409, 410, 415, 422, 429, 500 and 501).



## Installation
This package requires **PHP 7.4+**.

Add it as Composer dependency:
```sh
$ composer require mediagone/symfony-easy-api
```


## Introduction

This package provides several classes to handle API requests and return structured JSON responses:

- `ApiPayload200Success`
- `ApiPayload201Created`
- `ApiPayload202Accepted`
- `ApiPayload204NoContent`
- `ApiPayloadError400BadRequest`
- `ApiPayloadError401Unauthorized`
- `ApiPayloadError403Forbidden`
- `ApiPayloadError404NotFound`
- `ApiPayloadError405MethodNotAllowed`
- `ApiPayloadError409Conflict`
- `ApiPayloadError410Gone`
- `ApiPayloadError415UnsupportedMediaType`
- `ApiPayloadError422UnprocessableEntity`
- `ApiPayloadError429TooManyRequests`
- `ApiPayloadError500ServerError`
- `ApiPayloadError501NotImplemented`



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
        "resultsCount": 3,
        "resultsCountTotal": 3,
        "page": 1,
        "pageCount": 1
    }
}
```


### Collection pagination

When dealing with a lot of database entries, you may want to paginate results to retrieve them chunk by chunk. \
The package provides the `ApiPagination` class to help with that feature.

It requires two database queries: one to count the total number of results, and another to fetch the requested results:

```php
use Mediagone\Symfony\EasyApi\EasyApi;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload;
use Mediagone\Symfony\EasyApi\Payloads\ApiPayload200Success;
use Mediagone\Symfony\EasyApi\Request\ApiPagination;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/things/{requestedPage}', name:'api_things_list')]
public function __invoke(int $requestedPage = 1, ThingRepository $thingRepository): Response
{
    return $easyApi->response(static function () use ($requestedPage, $thingRepository) : ApiPayload {
        // Count the total number of Things in the db
        $thingsCount = $thingRepository->countAll();
        
        // Create a pagination object
        $pagination = ApiPagination::create($requestedPage, 5, $thingsCount);
        
        // Query the page's results
        $things = $thingRepository->findAllPaginated($pagination);
        
        return ApiPayload200Success::createWithArrayResult($things, $pagination);
    }
}
```

Assuming that you have 93 rows in your database and you are requesting the 2nd page of 5 results, you'll receive the following JSON response:
```json
{
    "success": true,
    "status": "ok",
    "statusCode": 200,
    "data": {
        "results": [
            { "id": 6, "name": "6th thing" },
            { "id": 7, "name": "7th thing" },
            { "id": 8, "name": "8th thing" },
            { "id": 9, "name": "9th thing" },
            { "id": 10, "name": "10th thing" }
        ],
        "resultsCount": 5,
        "resultsCountTotal": 93,
        "page": 2,
        "pageCount": 19
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
