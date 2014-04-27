<?php namespace hCityGrabber\Starter;

use hCityGrabber\Models\CityModel;
use hCityGrabber\Models\CountyModel;
use hCityGrabber\pageLister\pageLister;
use hCityGrabber\simpleWebClient\simpleWebClient;
use hCityGrabber\Utils\StringUtils;
use PHPHtmlParser\Dom;

/**
 * GrabberStarter osztály, ez indítja el a teljes folyamatot
 * és végzi a települések adatainak feldolgozását
 *
 * @author Zoltan Borsos <zolli07@gmail.com>
 * @package hCityGrabber\Starter
 * @license MIT
 * @version 1.0
 *
 * @since 1.0
 */
class GrabberStarter {

    /**
     * Egy URL lista ami a pageLister objektumból lesz feltöltve
     *
     * @var array
     */
    private $urlList = [];

    /**
     * Egy simpleWebClient osztály példánya
     *
     * @var \hCityGrabber\simpleWebClient\simpleWebClient
     */
    private $simpleWebClient = null;

    /**
     * A cityModel példánya a globális elérés miatt
     *
     * @var \hCityGrabber\Models\CityModel|null
     */
    private $cityModel = null;

    /**
     * Konstruktor
     */
    public function __construct() {
        $lister = new pageLister();
        $this->urlList = $lister->getPagesUrls();
        $this->cityModel = new CityModel();

        $this->simpleWebClient = new simpleWebClient();

        $this->processCities();
    }

    /**
     * Elkezdi a városok feldolgozását és a nem létező
     * megyéket minden iterációban megpróbálja beszúrni
     */
    private function processCities() {
        $countyM = new CountyModel();

        foreach($this->urlList as $url) {
            $parser = $this->simpleWebClient->getPageAsDomObject($url);
            $rows = $parser->getElementById("mw-content-text")->find(".wikitable")->find("tr");

            foreach($rows as $row) {
                $cells = $row->find("td");
                $city = $cells->find("a");

                //Ez tényleg egy bejegyzés, csinálni kell vele valamit :)
                if($city instanceof \PHPHtmlParser\Dom\Collection) {

                    $cityHeadArray = [
                        "name" => $city->innerHtml,
                        "detailsUrl" => pageLister::$baseUrl . $city->getAttribute("href"),
                        "population" => $cells[4]->innerHtml,
                        "county" => $cells[2]->innerHtml,
                        "postal_code" => $cells[5]->innerHtml,
                    ];

                    $countyM->tryAddCounty($cityHeadArray["county"]);
                    $cityHeadArray["county_id"] = $countyM->exist("name", $cityHeadArray["county"]);

                    $this->processCityDetails($cityHeadArray);
                }
            }

        }
    }

    /**
     * Feldolgoz egy várost előzetesen megapja a listából
     * származó nyers adatokat a gyorsabb feldolgozás érdekében
     *
     * @param array $currentMap
     */
    private function processCityDetails($currentMap) {
        $detailPage = $this->simpleWebClient->getPageAsDomObject($currentMap["detailsUrl"]);
        $infoBox = $detailPage->find(".ujinfobox");
        $rows = $infoBox->find("tr");

        $cityDetails = [];
        $cityDetails["name"] = $currentMap["name"];
        $cityDetails["county_id"] = $currentMap["county_id"];
        $cityDetails["population"] = str_replace(" ", "", $currentMap["population"]);
        $cityDetails["postal_code"] = $currentMap["postal_code"];

        foreach($rows as $row) {
            if(is_string($row->find(".fejlec ")->innerHtml)) {
                $cityDetails["name"] = $row->find(".fejlec ")->innerHtml;
            }

            if(StringUtils::contains($row->innerHtml, "Körzethívószám")) {
                $p = new Dom();
                $p->load($row->innerHtml);
                $cityDetails["phone_prefix"] = $p->find("td")[1]->innerHtml;
            }

            if(StringUtils::contains($row->innerHtml, "Népsűrűség")) {
                $p = new Dom();
                $p->load($row->innerHtml);
                $cityDetails["population_density"] = floatval( str_replace(",", ".", explode(" ", $p->find("td")[1]->innerHtml)[0]) );
            }

            if(StringUtils::contains($row->innerHtml, "Terület")) {
                $p = new Dom();
                $p->load($row->innerHtml);
                $cityDetails["area"] = floatval( str_replace(",", ".", explode(" ", $p->find("td")[1]->innerHtml)[0]) );
            }

            if(StringUtils::contains($row->innerHtml, "é. sz.")) {
                $p = new Dom();
                $p->load($row->innerHtml);
                $cityDetails["latitude"] = round((float) StringUtils::processCoordinates($p->find("a")->find("span")[0]->innerHtml), 6);
                $cityDetails["longitude"] = round((float) StringUtils::processCoordinates($p->find("a")->find("span")[1]->innerHtml), 6);
            }

        }

        $this->cityModel->insert($cityDetails);
    }
}