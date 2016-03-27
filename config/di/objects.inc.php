<?php
return [
    ZohoBooksAL\Configuration\Reader\ConfigFileReader::class => DI\object()
                    ->constructorParameter('configurationFileName',
                                            __DIR__ . '/../config.inc.php'),

    ZohoBooksAL\Persister\PersistedEntityTracker::class => DI\object()->scope(\DI\Scope::PROTOTYPE),

    ZohoBooksAL\Configuration\Configuration::class => DI\object()
                    ->constructorParameter('configurationReader',
                                            DI\get(ZohoBooksAL\Configuration\Reader\ConfigFileReader::class)),

    ZohoBooksAL\Transport\GenericTransport::class => DI\object()
                    ->constructorParameter('httpClient', DI\get(GuzzleHttp\Client::class)),

    ZohoBooksAL\Mapper\GenericMapper::class => DI\object()
                    ->constructorParameter('transport', DI\get(ZohoBooksAL\Transport\GenericTransport::class)),

    ZohoBooksAL\EntityManager::class => DI\object()
                    ->constructorParameter('configuration',
                                            DI\get(ZohoBooksAL\Configuration\Configuration::class)),
    ZohoBooksAL\Transport\Uri\Uri::class => DI\object()
                    ->constructorParameter('configuration',
                                            DI\get(ZohoBooksAL\Configuration\Configuration::class)),
    ZohoBooksAL\UnitOfWork::class => DI\object()
                    ->constructorParameter('mapper', DI\get(ZohoBooksAL\Mapper\GenericMapper::class)),

    Zend\Code\Scanner\DirectoryScanner::class => DI\object()
                    ->constructorParameter('directory', __DIR__.'/../../src/Entity'),

    Zend\Code\Annotation\AnnotationManager::class => DI\object()
                    ->method('attach', DI\get(ZohoBooksAL\Code\Annotation\Parser\AnnotationParser::class)),

    ZohoBooksAL\Metadata\Collector\EntityCollector::class => DI\object()
                    ->constructorParameter('manager', DI\get(Zend\Code\Annotation\AnnotationManager::class))
                    ->constructorParameter('scanner', DI\get(Zend\Code\Scanner\DirectoryScanner::class)),

    ZohoBooksAL\Metadata\MetadataCollection::class => DI\object()
                    ->constructorParameter('collector', DI\get(ZohoBooksAL\Metadata\Collector\EntityCollector::class))
];
