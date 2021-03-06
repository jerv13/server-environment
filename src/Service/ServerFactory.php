<?php

namespace Jerv\ServerEnvironment\Service;

use Jerv\ServerEnvironment\Data\Env;
use Jerv\ServerEnvironment\Data\PathConfig;
use Jerv\ServerEnvironment\Data\PathData;
use Jerv\ServerEnvironment\Data\Secrets;
use Jerv\ServerEnvironment\Data\Version;
use Jerv\ServerEnvironment\Exception\ServerException;

/**
 * Class ServerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ServerFactory
{
    /**
     * @var Server
     */
    protected static $instance = null;

    /**
     * build - Done on bootstrap
     *
     * @param string $pathConfig
     * @param string $serverConfigFile
     * @param string $serverConfigKey
     * @param string $pathData
     *
     * @return Server
     * @throws ServerException
     */
    public static function build(
        $pathConfig = PathConfig::PATH_DEFAULT,
        $serverConfigFile = Env::SERVER_CONFIG_FILE,
        $serverConfigKey = Env::SERVER_CONFIG_KEY,
        $pathData = PathData::PATH_DEFAULT
    ) {
        if (!empty(self::$instance)) {
            return self::$instance;
        }

        PathConfig::build($pathConfig);
        PathData::build($pathData);

        $pathData = PathData::get();
        $pathConfig = PathConfig::get();

        Env::build(
            $pathConfig,
            $serverConfigFile,
            $serverConfigKey,
            $pathData
        );

        Secrets::build(
            $pathData
        );

        Version::build(
            $pathData
        );

        self::$instance = new Server(
            $pathData,
            $pathConfig,
            Env::isProduction(),
            Env::get(),
            Env::getServerVars(),
            Secrets::get(),
            Version::get()
        );

        return self::$instance;
    }

    /**
     * getInstance
     *
     * @return Server
     * @throws ServerException
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            throw new ServerException('No _server instance built');
        }

        return self::$instance;
    }

    /**
     * @param null $container
     *
     * @return Server
     * @throws ServerException
     */
    public function __invoke($container = null)
    {
        return self::getInstance();
    }
}
