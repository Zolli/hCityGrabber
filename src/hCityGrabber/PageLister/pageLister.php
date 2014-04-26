<?php namespace hCityGrabber\pageLister;

use hCityGrabber\simpleWebClient\simpleWebClient;
use PHPHtmlParser\Dom;

/**
 * pageLister osztály, ami visszaadja az összes
 * település listaoldalt a főoldalról
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\pageLister
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
class pageLister {

    /**
     * A megtalálta lapok URL-jét tartalmazó tömb
     *
     * @var array
     */
    private $pages = [];

    /**
     * Az oldal amin el kell kezdeni a keresését
     *
     * @var string
     */
    private $startPage = "http://hu.wikipedia.org/wiki/Magyarorsz%C3%A1g_telep%C3%BCl%C3%A9sei:_A,_%C3%81";

    /**
     * A prefix amit minden megtalált link elé kell csapni
     *
     * @var string
     */
    public static $baseUrl = "http://hu.wikipedia.org";

    /**
     * A lekérésből kapott válasz
     *
     * @var String
     */
    private $resultString = null;

    /**
     *
     *
     * @var \hCityGrabber\simpleWebClient\simpleWebClient
     */
    private $simpleWebClient = null;

    /**
     * Konstruktor
     */
    public function __construct() {
        //A link ahonnan lekérjük az oldalakat az a lista első eleme
        $this->pages[] = $this->startPage;

        //Kliens init
        $this->simpleWebClient = new simpleWebClient();

        //Elindítjuk az oldalak lekérését
        $this->getPages();
    }

    /**
     * Lekéri a kezdőoldalt, majd elindítja a parser-t
     */
    private function getPages() {
        $this->resultString = $this->simpleWebClient->getPageContent($this->startPage);
        $this->parseResultToUrls();
    }

    /**
     * A visszakapot válasz-t parsolja és az előre definiált tömbbr
     * rakja a megtalált URL-eket
     */
    private function parseResultToUrls() {
        $domParser = new Dom();
        $domParser->load($this->resultString);
        $elements = $domParser->getElementById("toc")->find("a");

        foreach($elements as $element) {
            $this->pages[] = self::$baseUrl . $element->getAttribute("href");
        }

    }

    /**
     * Visszaadja a lekért oldalakat egy tömbben
     *
     * @return array
     */
    public function getPagesUrls() {
        return $this->pages;
    }

}