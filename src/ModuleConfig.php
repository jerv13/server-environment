<?php

namespace Jerv\ServerEnvironment;

use Jerv\ServerEnvironment\Service\Server;
use Jerv\ServerEnvironment\Service\ServerFactory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    Server::class => ServerFactory::class
                ],
            ],
        ];
    }
}
