<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Kairee Wu (krwu)
 */

declare(strict_types=1);

namespace Tests\Traits;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;

trait InteractsWithHttp
{
    public function get(
        $uri,
        array $query = [],
        array $headers = [],
        array $cookies = []
    ): ResponseInterface {
        return $this->http->handle($this->request($uri, 'GET', $query, $headers, $cookies));
    }

    public function getWithAttributes(
        $uri,
        array $attributes,
        array $headers = []
    ): ResponseInterface {
        $r = $this->request($uri, 'GET', [], $headers, []);
        foreach ($attributes as $k => $v) {
            $r = $r->withAttribute($k, $v);
        }

        return $this->http->handle($r);
    }


    public function post(
        $uri,
        array $data = [],
        array $headers = [],
        array $cookies = []
    ): ResponseInterface {
        return $this->http->handle(
            $this->request($uri, 'POST', [], $headers, $cookies)->withParsedBody($data)
        );
    }

    public function request(
        $uri,
        string $method,
        array $query = [],
        array $headers = [],
        array $cookies = []
    ): ServerRequest {
        $headers = array_merge([
            'accept-language' => 'en'
        ], $headers);

        return new ServerRequest(
            [],
            [],
            $uri,
            $method,
            'php://input',
            $headers,
            $cookies,
            $query
        );
    }

    public function fetchCookies(array $header)
    {
        $result = [];
        foreach ($header as $line) {
            $cookie = explode('=', $line);
            $result[$cookie[0]] = rawurldecode(substr($cookie[1], 0, strpos($cookie[1], ';')));
        }

        return $result;
    }
}
