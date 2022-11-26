<?php declare(strict_types=1);

namespace Mediagone\Symfony\EasyApi\Payloads;


interface ApiPayload
{
    public function getCode() : int;
    public function getData() : array;
    public function getHeaders() : array;
}
