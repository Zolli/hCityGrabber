<?php namespace hCityGrabber\Utils;

/**
 * String utility osztály, pár egyszerű string helper
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\Utils
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
class StringUtils {

    /**
     * Megvizsgálja hogy egy kapott stringben létezik-e egy másik
     *
     * @param String $input A bemenet amiben keresünk
     * @param String $str A darab amit keresünk
     * @return bool true, ha benne van, false, ha nem
     */
    public static function contains($input, $str) {
        if (strpos($input, $str) !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Feldolgoz egy koordináta stringet és decimális
     * formába alakítja azt
     *
     * @param String $str A bemenő sztring
     * @return String
     */
    public static function processCoordinates($str) {
        $parts = explode(" ", $str);

        $direction = rtrim($parts[0], ".");
        $direction = str_replace("é", "e", $direction);
        $c = self::DMS2Decimal(self::clearCoord($parts[2]), self::clearCoord($parts[3]), self::clearCoord($parts[4]), $direction);
        return $c;
    }

    /**
     * Megtisztít egy koordináta sztringet
     * a speciális karaktereketől
     *
     * @param String $str A nyers adat
     * @return string
     */
    private static function clearCoord($str) {
        return rtrim($str, ",°′″");
    }

    /**
     * A ° ′ " formátumú koordinátákat
     * decimális formátumba alakítja
     *
     * @param int $degrees
     * @param int $minutes
     * @param int $seconds
     * @param string $direction
     * @return Float
     */
    public static function DMS2Decimal($degrees = 0, $minutes = 0, $seconds = 0, $direction = 'n') {
        $d = strtolower($direction);
        $ok = array('e', 'd', 'k', 'ny');

        if(!is_numeric($degrees) || $degrees < 0 || $degrees > 180) {
            $decimal = false;
        }

        elseif(!is_numeric($minutes) || $minutes < 0 || $minutes > 59) {
            $decimal = false;
        }

        elseif(!is_numeric($seconds) || $seconds < 0 || $seconds > 59) {
            $decimal = false;
        } elseif(!in_array($d, $ok)) {
            $decimal = false;
        } else {
            $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

            if($d == 'd' || $d == 'ny') {
                $decimal *= -1;
            }
        }

        return $decimal;
    }

}