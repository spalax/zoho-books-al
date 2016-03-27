<?php
use Interop\Container\ContainerInterface;

return [
    \ICanBoogie\Inflector::class => function () {
        return \ICanBoogie\Inflector::get('en');
    }
];
