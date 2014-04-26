<?php namespace hCityGrabber\simpleWebClient;

use Guzzle\Http\Client;
use PHPHtmlParser\Dom;

/**
 * simpleWebClient osztály, segíti a Guzzle kliens
 * egyszerűbb használatát néhány helper függvény megvalósításával
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\simpleWebClient
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
class simpleWebClient {

    /**
     * A Guzzle kliens példánya
     *
     * @var \Guzzle\Http\Client
     */
    private $guzzle = null;

    /**
     * A konstruktorban létrehozzuk a guzzle kliens példányát
     */
    public function __construct() {
        $this->guzzle = new Client();
    }

    /**
     * Lekér egy URL-t és visszaadj a teljes választ egy sztringként
     *
     * @param $url Az URL aminek a tartalmát lekérjük
     * @return bool|string A lekért URL tartalma, vagy FALSE ha nem 200 volt a státusz kód
     */
    public function getPageContent($url) {
        $response = $this->guzzle->get($url)->send();
        $responseStatus = $response->getStatusCode();

        if($responseStatus === 200) {
            return $response->getBody(TRUE);
        }

        return FALSE;
    }

    /**
     * Lekéri egy URL tartalmát majd visszaadja azt egy
     * DOMParser objektumként
     *
     * @param String $url A lekért URL
     * @return \PHPHtmlParser\Dom
     */
    public function getPageAsDomObject($url) {
        $content = $this->getPageContent($url);
        $domParser = new Dom();
        return $domParser->load($content);
    }

}