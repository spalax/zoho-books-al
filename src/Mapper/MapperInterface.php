<?php
namespace ZohoBooksAL\Mapper;

use GuzzleHttp\Exception\ClientException;
use ZohoBooksAL\Mapper\Exception\UnexpectedResponseException;

interface MapperInterface
{
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
    public function fetchAll($collectionPath, $collectionName,
                                array $params = [], $offset = null, $limit = null);

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
    public function fetchOne($collectionPath, $collectionItemName, $identifier);

    /**
     * @param string $collectionPath
     * @param string $collectionItemName
     * @param string | number $identifier
     * @param array $data
     * @return array
     */
    public function update($collectionPath, $collectionItemName, $identifier, array $data);

    /**
     * @param string $collectionPath
     * @param string $collectionItemName
     * @param array $data
     * @return array
     */
    public function create($collectionPath, $collectionItemName, array $data);
}
