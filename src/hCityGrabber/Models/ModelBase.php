<?php namespace hCityGrabber\Models;

use hCityGrabber\Database\Connection;

/**
 * Absztrakt modell osztály, minden modell őse
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\Models
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
abstract class ModelBase {

    /**
     * Az adatbázis-kezelő példánya
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection = null;

    /**
     * Tartalmazza a tábla nevét ami a modellhez kapcsolódik,
     * ha nem adjuk meg akkor a modell megpróbálja kideríteni
     * az osztály nevéből
     *
     * @var String A modellhez tartozó tábla neve
     */
    public $table = null;

    /**
     * A tábla elsődleges kulcsának neve
     *
     * @var String
     */
    public $primaryKey = "id";

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->connection = Connection::getInstance();

        if($this->table === null) {
            $this->tryDetectTableName();
        }
    }

    /**
     * A konstruktor hívja meg hogy az osztály nevéből megpróbálja
     * kitalálni a tábla nevét ha nem definiáltuk a gyerekben
     * Letrimmeli a Model-t az osztály nevéből és camelCase szerint _
     * cseréli az elválasztókat
     *
     * @example userPackageSubscriptionModel -> user_package_subscription
     */
    private function tryDetectTableName() {
        $class = join('', array_slice(explode('\\', get_class($this)), -1));
        $class = rtrim($class, "Model");
        $this->table = strtolower( preg_replace( '/([A-Z])/', '_$1', lcfirst($class) ) );
    }

    /**
     * Beszúr egy rekordot a táblába
     *
     * @param array $data Egy tömb ami kulcs-érték párokban tartalmazza az adatokat
     * @param bool $returnInsertId Vissza adja-e beszúrás után a beszúrt sor AI ID-ját
     * @return bool|int bool ha nem kell az AI id, int ha igen
     */
    public function insert($data, $returnInsertId = TRUE) {
        $this->connection->insert($this->table, $data);

        if($returnInsertId) {
            $this->connection->lastInsertId();
        } else {
            return TRUE;
        }
    }

    /**
     * Futtat egy nyers lekérdezést
     *
     * @param string $queryString A lekérdezés
     */
    public function executeRaw($queryString) {
        $this->connection->executeQuery($queryString);
    }

    /**
     * Megvizsgálja hogy létezik-e már egy adott rekord egy táblában
     *
     * @param string $key A kulcs amit keresünk
     * @param string $value Ami a kulcs értéke kell hogy legyen
     * @param bool $returnPrimaryKey Visszaadja-e a rekord elsődleges kulcsát
     * @return bool|int Ha vissza kell adni a kulcsát akkor ha létezik a rekord int, ha nem TRUE, ha nincs ilyen rekord akkor FALSE
     */
    public function exist($key, $value, $returnPrimaryKey = TRUE) {
        $result = $this->connection->executeQuery("SELECT ". $this->primaryKey . " FROM " . $this->table . " WHERE " . $key . " = '" . $value . "' LIMIT 1")->fetchAll();

        if(is_array($result)) {
            if($returnPrimaryKey) {
                return $result[0][$this->primaryKey];
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

}