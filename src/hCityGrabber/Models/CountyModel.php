<?php namespace hCityGrabber\Models;

/**
 * Country - Adatbázis model
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\Models
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
class CountyModel extends ModelBase {

    /**
     * Megpróbál beszúrni egy megyét az adatbázisab
     *
     * @param String $name A megye neve
     */
    public function tryAddCounty($name) {
        $this->connection->executeQuery("INSERT IGNORE INTO " . $this->table . " (name) VALUES ('" . $name . "')");
    }

}