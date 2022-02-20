<?php

/**
 * Barcode Buddy for Grocy
 *
 * PHP version 7
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU General
 * Public License v3.0 that is attached to this project.
 *
 * @author     Marc Ole Bulling
 * @copyright  2019 Marc Ole Bulling
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html  GNU GPL v3.0
 * @since      File available since Release 1.5
 */


require_once __DIR__ . "/../api.inc.php";
require_once __DIR__ .'/ProviderJumbo/vendor/Net/URL2.php';
require_once __DIR__ .'/ProviderJumbo/vendor/HTTP/Request2/Adapter.php';
require_once __DIR__ .'/ProviderJumbo/vendor/HTTP/Request2/SocketWrapper.php';
require_once __DIR__ .'/ProviderJumbo/vendor/HTTP/Request2/Response.php';
require_once __DIR__ .'/ProviderJumbo/vendor/HTTP/Request2.php';

class ProviderJumbo extends LookupProvider {


    function __construct(string $apiKey = null) {
        parent::__construct($apiKey);
        $this->providerName       = "Jumbo Group";
        $this->providerConfigKey  = "LOOKUP_USE_JUMBO";
        $this->ignoredResultCodes = array();
    }
    
     /**
     * @param string $url
     * @param string $method
     * @param array|null $formdata
     * @param string|null $userAgent
     * @param array|null $headers
     * @param bool $decodeJson
     * @param string|null $jsonData
     * @return bool|mixed|string|null
     */
    protected function execute(string $url, string $method = METHOD_GET, array $formdata = null, string $userAgent = null, ?array $headers = null, bool $decodeJson = true, string $jsonData = null) {
    
        // We need to override the default execute function of LookupProvide to work around the CURL block by Jumbo
        $request = new HTTP_Request2();
        $request->setMethod(HTTP_Request2::METHOD_GET);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader($header);
        $request->setBody($formdata);
        try {
            $result = $request->send();
        } catch (HTTP_Request2_Exception $e) {
            API::logError("Provider lookup error for " . $this->providerName . " - ".$e->getMessage(), false);
            return null;
        }
        return $result;        
    }

    /**
     * Looks up a barcode
     * @param string $barcode The barcode to lookup
     * @return array|null Name of product, null if none found
     */
    public function lookupBarcode(string $barcode): ?array {
        if (!$this->isProviderEnabled())
            return null;

        $url    = "https://mobileapi.jumbo.com/v12/search?q=" . $barcode;
        $header = array(
            'Host' => 'mobileapi.jumbo.com',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:81.0) Gecko/20100101 Firefox/81.0'
        );
        $result = $this->execute($url, null, null, null, $header);
        if (!isset($result["products"]) || !isset($result["products"]["data"]) || !isset($result["products"]["total"]) || $result["products"]["total"] == "O")
            return null;

        if (isset($result["products"]["data"][0]["title"]) && $result["products"]["data"][0]["title"] != "") {
            return self::createReturnArray(sanitizeString($result["products"]["data"][0]["title"]));
        } else
            return null;
    }
}