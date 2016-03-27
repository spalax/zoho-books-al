<?php
use Interop\Container\ContainerInterface;

return [
    \ICanBoogie\Inflector::class => function () {
        return \ICanBoogie\Inflector::get('en');
    },

    Zend\Code\Scanner\DirectoryScanner::class => function (ContainerInterface $container) {
        $configuration = $container->get(ZohoBooksAL\Configuration\Configuration::class);
        return new \Zend\Code\Scanner\DirectoryScanner(array_merge([ __DIR__.'/../../src/Entity' ],
                                                                    $configuration->getEntitiesPaths()));
    }
];
