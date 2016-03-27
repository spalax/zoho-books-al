<?php
namespace ZohoBooksAL\Configuration\Reader;

interface ReaderInterface
{
    /**
     * @param string $primaryKey
     *
     * @throws InvalidConfigException
     * @return array
     */
    public function getConfiguration($primaryKey = '');
}
