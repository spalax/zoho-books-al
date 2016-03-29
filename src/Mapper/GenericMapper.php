<?php
namespace ZohoBooksAL\Mapper;

use DI\FactoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use ZohoBooksAL\Configuration\ConfigurationInterface;
use ZohoBooksAL\Mapper\Exception\UnexpectedResponseException;
use ZohoBooksAL\Transport\TransportInterface;
use ZohoBooksAL\Transport\Uri\Uri;

class GenericMapper implements MapperInterface
{
    /**
     * @var TransportInterface
     */
    protected $transport = null;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * Default ZOHO items limit
     */
    const DEFAULT_LIMIT = 200;

    /**
     * GenericMapper constructor.
     *
     * @param TransportInterface $transport
     * @param ConfigurationInterface $configuration
     * @param FactoryInterface $factory
     */
    public function __construct(TransportInterface $transport,
                                ConfigurationInterface $configuration,
                                FactoryInterface $factory)
    {
        $this->transport = $transport;
        $this->configuration = $configuration;
        $this->factory = $factory;
    }

    /**
     * Fetch all items from ZOHO Books collection
     * by collectionPath and fetch all data from
     * response array by key collectionName. This
     * method will do a few request if defined limit
     * greater than limit provided by remote service.
     * Than all results will be merged to array and
     * returned
     *
     * @param string $collectionPath
     * @param string $collectionItemName
     * @param array $params [OPTIONAL]
     * @param int $offset [OPTIONAL]
     * @param int $limit [OPTIONAL]
     * @return array
     *
     * @throws UnexpectedResponseException If some data/key/name missed in response structure
     * @throws ClientException If HTTP response status is not 200
     */
    public function fetchAll($collectionPath, $collectionItemName,
                                array $params = [], $offset = null, $limit = null)
    {
        $uri = $this->factory->make(Uri::class, ['uri' => $this->configuration->getServiceUri().$collectionPath]);

        foreach ($params as $name=>$value) {
            $uri = $uri->withQueryValue($uri, $name, $value);
        }

        $page = 1;
        $perPage = self::DEFAULT_LIMIT;

        if (!is_null($offset) && $offset > 0) {
            $page = 2;
            $perPage = $offset;
        } else if (!is_null($limit)) {
            $perPage = $limit;
        }

        $returnItems = [];

        do {
            $uri = $uri->withQueryValue($uri, 'page', $page);
            $uri = $uri->withQueryValue($uri, 'per_page', $perPage);

            $result = $this->transport->get($uri);

            if (empty($result->page_context)) {
                throw new UnexpectedResponseException('Could not found page_context in response');
            }

            $pageContext = $result->page_context;
            $morePages = $pageContext->has_more_page;

            $items = $result->{$collectionItemName};
            foreach ($items as $item) {
                $returnItems[] = (array)$item;
                if (!is_null($limit) && count($returnItems) >= $limit){
                    return $returnItems;
                }
            }
        } while ($morePages === true);
        
        return $returnItems;
    }

    /**
     * Fetch data from remote
     * service by collectionPath using identifier
     * and retrieve data from response array
     * by itemName.
     *
     * @param string $collectionPath
     * @param string $collectionItemName
     * @param string | number $identifier
     * @return array
     *
     * @throws UnexpectedResponseException If some data/key/name missed in response structure
     * @throws ClientException If HTTP response status is not 200
     */
    public function fetchOne($collectionPath, $collectionItemName, $identifier)
    {
        $uri = $this->factory->make(Uri::class, ['uri'=>$this->configuration->getServiceUri().
                                                        $collectionPath.'/'.$identifier]);

        $result = $this->transport->get($uri);

        if (empty($result) ) {
            throw new UnexpectedResponseException('Empty body requesting from '.$collectionPath.
                                                  ' using identifier '.$identifier.
                                                  ' and itemName '.$collectionItemName);
        }

        if (empty($result->{$collectionItemName})) {
            throw new UnexpectedResponseException('Empty itemName '.$collectionItemName.' from ' . $collectionPath .
                                                  ' using identifier '.$identifier);
        }
        

        return (array) $result->{$collectionItemName};
    }

    /**
     * @param string $collectionPath
     * @param string $collectionItemName
     * @param string | number $identifier
     * @param array $data
     *
     * @return array|null
     */
    public function update($collectionPath, $collectionItemName, $identifier, array $data)
    {
        $uri = $this->factory->make(Uri::class, ['uri'=>$this->configuration->getServiceUri().
                                                        $collectionPath.'/'.$identifier]);

        $result = $this->transport->put($uri, $data);

        if (empty($result)) {
            return null;
        }

        if (empty($result->{$collectionItemName})) {
            throw new UnexpectedResponseException('Empty itemName '.$collectionItemName.' from ' . $collectionPath .
                                                  ' using identifier '.$identifier. ' after PUT/change');
        }

        return (array) $result->{$collectionItemName};
    }

    /**
     * @param $collectionPath
     * @param number $identifier
     */
    public function delete($collectionPath, $identifier)
    {
        $uri = $this->factory->make(Uri::class, ['uri'=>$this->configuration->getServiceUri().
                                                        $collectionPath.'/'.$identifier]);
        $this->transport->delete($uri);
    }

    /**
     * @param string $collectionPath
     * @param string $collectionItemName
     * @param array $data
     *
     * @return array|null
     */
    public function create($collectionPath, $collectionItemName, array $data)
    {
        $uri = $this->factory->make(Uri::class, ['uri' => $this->configuration->getServiceUri().$collectionPath]);

        $result = $this->transport->post($uri, $data);

        if (empty($result)) {
            return null;
        }

        if (empty($result->{$collectionItemName})) {
            throw new UnexpectedResponseException('Empty itemName '.$collectionItemName.' from ' .
                                                    $collectionPath. ' after POST/create');
        }

        return (array) $result->{$collectionItemName};
    }
}
