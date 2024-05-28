<?php

namespace WebSystems\Toyota;

use GuzzleHttp\Exception\GuzzleException;

interface ApiInterface
{
    /**
     * Return request id and deep link.
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function getRequestId(): ?array;

    /**
     * Get data from dia.
     *
     * @param string $requestId
     * @return string|null
     * @throws GuzzleException
     */
    public function getData(string $requestId): ?string;
}