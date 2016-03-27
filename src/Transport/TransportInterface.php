<?php
namespace ZohoBooksAL\Transport;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface TransportInterface
{
    /**
     * @param UriInterface $httpUri
     *
     * @return array
     */
    public function get(UriInterface $uri);

    /**
     * @param UriInterface $uri
     * @param string $data
     *
     * @return array
     */
    public function post(UriInterface $uri, $data);

    /**
     * @param UriInterface $uri
     *
     * @return array
     */
    public function delete(UriInterface $uri);

    /**
     * @param UriInterface $uri
     * @param string $data
     *
     * @return array
     */
    public function put(UriInterface $uri, $data);
}
