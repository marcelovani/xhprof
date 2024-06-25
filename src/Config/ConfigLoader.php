<?php
/**
 * @file Config loader.
 */

namespace Xhprof\Config;

use Symfony\Component\Dotenv\Dotenv;

/**
 * Config loader class.
 */
class ConfigLoader
{

    /**
     * Stores the configurations.
     */
    private $config;

    /**
     * Constructor for config loader.
     */
    public function __construct()
    {
        $this->setEnv();
        $this->setDbConfig();
        $this->setSerializer();
        $this->config['display'] = false;
        $this->config['doprofile'] = false;

        return $this;
    }

    /**
     * Sets env variables from .env file.
     */
    private function setEnv()
    {
        # Load .env.
        $dotenv = new Dotenv();
        $dotenv->load(XHPROF_DOCROOT . "/.env");
    }

    /**
     * Sets db configurations.
     *
     * @return array config.
     *   The configs.
     */
    private function setDbConfig()
    {
        $this->config['dbtype'] = $_ENV['DB_TYPE'];
        $this->config['dbhost'] = $_ENV['DB_HOST'];
        $this->config['dbuser'] = $_ENV['DB_USER'];
        $this->config['dbpass'] = $_ENV['DB_PASS'];
        $this->config['dbname'] = $_ENV['DB_NAME'];
        $this->config['dbadapter'] = $_ENV['DB_DRIVER'];
        $this->config['servername'] = 'my';
        $this->config['namespace'] = 'app';
        $this->config['url'] = $_ENV['PROJECT_BASE_URL'] . ':' . $_ENV['DB_DRIVER'];
        $this->config['getparam'] = "_profile";

        return $this->config;
    }

    /**
     * MySQL/MySQLi/PDO ONLY
     * Switch to JSON for better performance and support for larger profiler data sets.
     * WARNING: Will break with existing profile data, you will need to TRUNCATE the profile data table.
     */
    private function setSerializer()
    {
        $this->config['serializer'] = 'php';
    }

    /**
     * Getter for configs.
     *
     * @param $name
     *   The config name.
     * @return mixed|array
     *   The required config or all configs, if no name is provided.
     */
    public function get($name = '')
    {
        if (empty($name)) {
            return $this->config;
        } else {
            if (isset($this->config[$name])) {
                return $this->config[$name];
            }
        }
    }
}
