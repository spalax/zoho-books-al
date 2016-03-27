<?php
namespace ZohoBooksAL\Transport;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\UriInterface;

class GenericTransport implements TransportInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient = null;

    /**
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param UriInterface $httpUri
     * @param string $method
     * @param array $data [OPTIONAL]
     *
     * @return array
     */
    protected function proceed(UriInterface $httpUri, $method, array $data = [])
    {
        if (!empty($data)) {
            $response = $this->httpClient->request($method, $httpUri, ['headers'=> ['Content-Type' =>
                                                                                'application/x-www-form-urlencoded'],
                                                                        'body' => 'JSONString='.\GuzzleHttp\json_encode($data)]);
        } else {
            $response = $this->httpClient->request($method, $httpUri);
        }

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

    /**
     * @param UriInterface $httpUri
     * @return array
     */
    public function get(UriInterface $uri)
    {
        return $this->proceed($uri, 'get');
    }

    /**
     * @param UriInterface $uri
     * @param string $data
     * @return array
     */
    public function post(UriInterface $uri, $data)
    {
        return $this->proceed($uri, 'post', $data);
    }

    /**
     * @param UriInterface $uri
     *
     * @return array
     */
    public function delete(UriInterface $uri)
    {
        return $this->proceed($uri, 'delete');
    }

    /**
     * @param UriInterface $uri
     * @param string $data
     *
     * @return array
     */
    public function put(UriInterface $uri, $data)
    {
        return $this->proceed($uri, 'put', $data);
    }
}
