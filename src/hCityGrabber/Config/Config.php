<?php namespace hCityGrabber\Config;

/**
 * Konfigrációs osztály
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\Config
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
class Config {

    /**
     * Tartalmazza a jelenlegi beállításokat
     * @var array
     */
    private static $configValues = [
        "database" => [
            "host" => "localhost",
            "user" => "root",
            "password" => "",
            "database" => "test",
            "driver" => "pdo_mysql",
            "charset" => "utf8",
        ],
    ];

    /**
     * Lekér egy beállítást a tömbből
     * dot-notation rendszerű
     *
     * @param $key A kulcs
     * @return mixed A lekért konfiguráció értéke
     */
    public static function get($key) {
        $parts = explode(".", $key);

        $currentValue = self::$configValues;
        foreach($parts as $part) {
            $currentValue = $currentValue[$part];
        }

        return $currentValue;
    }

}