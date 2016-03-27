<?php
namespace ZohoBooksAL\Transport\Uri;

use Psr\Http\Message\UriInterface;
use ZohoBooksAL\Configuration\ConfigurationInterface;

class Uri extends \GuzzleHttp\Psr7\Uri implements UriInterface
{
    /**
     * Uri constructor.
     * Overridden constructor to achieve every
     * request to the ZOHO should have Auth Token
     *
     * @param string $uri
     * @param ConfigurationInterface $configuration
     */
    public function __construct($uri = '', ConfigurationInterface $configuration)
    {
        if ($uri != null) {
            $parts = parse_url($uri);
            if ($parts === false || !array_key_exists('query', $parts)) {
                return parent::__construct($uri.'?authtoken='.$configuration->getAuthToken());
            }

            return parent::__construct(str_replace($parts['query'],
                                                   'authtoken='.$configuration->getAuthToken().'&'.$parts['query'],
                                                   $uri));
        }
    }
}
