<?php namespace hCityGrabber\Database;

use hCityGrabber\Config\Config;

/**
 * Connection osztály ami singleton mintával valósítja
 * meg az adatbázis kapcsolatot.
 * A konstruktorban beállítja a karakterkészletet is
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\Database
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
class Connection {

    /**
     * @var \Doctrine\DBAL\Connection A felépített kapcsolatot tárolja
     */
    private static $instance = null;

    /**
     * A singleton példány konstruktora és gettere
     *
     * @return \Doctrine\DBAL\Connection
     */
    public static function getInstance() {
        if(self::$instance === null) {
            $config = new \Doctrine\DBAL\Configuration();

            $connectionParams = array(
                'dbname' => Config::get("database.database"),
                'user' => Config::get("database.user"),
                'password' => Config::get("database.password"),
                'host' => Config::get("database.host"),
                'driver' => Config::get("database.driver"),
            );

            self::$instance = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            self::$instance->executeQuery("SET NAMES " . Config::get("database.charset"));

            return self::$instance;
        }

        return self::$instance;
    }

}