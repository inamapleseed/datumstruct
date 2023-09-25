<?php
error_reporting(E_ALL & ~E_NOTICE);
/**
 * @author     Opencart-api.com
 * @copyright  2017
 * @license    License.txt
 * @version    2.0
 * @link       https://opencart-api.com/product/shopping-cart-rest-api/
 * @documentations https://opencart-api.com/opencart-rest-api-documentations/
 */
abstract class RestController extends Controller
{
    public $statusCode = 200;
    public $post = array();
    public $allowedHeaders = array("GET", "POST", "PUT", "DELETE");
    public $accessControlAllowHeaders = array("Content-Type", "Authorization", "X-Requested-With", "X-Oc-Merchant-Id",
        "X-Oc-Merchant-Language", "X-Oc-Currency", "X-Oc-Image-Dimension", "X-Oc-Store-Id", "X-Oc-Session");
    public $json = array("success" => 1, "error" => array(), "data" => array());

    public $multilang = 0;
    public $opencartVersion = "";
    public $urlPrefix = "";

    public $apiRequest = null;

    private $httpVersion = "HTTP/1.1";

    public function checkPlugin()
    {

        /*check rest api is enabled*/
        if (!$this->config->get('rest_api_status')) {
            $this->json["error"][] = 'Shopping Cart Rest API is disabled. Enable it!';
            $this->statusCode = 403;
            $this->sendResponse();
        }

        $this->opencartVersion = str_replace(".", "", VERSION);
        $this->urlPrefix = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;

        $this->validateToken();

        $token = $this->getTokenValue();

        $this->update_session($token['access_token'], json_decode($token['data'], true));

        $this->setSystemParameters();
    }

    public function sendResponse()
    {
        
        $statusMessage = $this->getHttpStatusMessage($this->statusCode);

        //fix missing allowed OPTIONS header
        $this->allowedHeaders[] = "OPTIONS";

        if ($this->statusCode != 200) {
            if (!isset($this->json["error"])) {
                $this->json["error"][] = $statusMessage;
            }

            if ($this->statusCode == 405 && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
                $this->response->addHeader('Allow: ' . implode(", ", $this->allowedHeaders));
            }

            $this->json["success"] = 0;

            //enable OPTIONS header
            if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                $this->statusCode = 200;
                $this->json["success"] = 1;
                $this->json["error"] = array();
            }
        } else {

            if (!empty($this->json["error"])) {
                $this->statusCode = 400;
                $this->json["success"] = 0;
            }
            //add cart errors to the response
            if (isset($this->json["cart_error"]) && !empty($this->json["cart_error"])) {
                $this->json["error"] = $this->json["cart_error"];
                unset($this->json["cart_error"]);
            }
        }

        $this->json["error"] = array_values($this->json["error"]);

        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: '. implode(", ", $this->allowedHeaders));
            $this->response->addHeader('Access-Control-Allow-Headers: '. implode(", ", $this->accessControlAllowHeaders));
            $this->response->addHeader('Access-Control-Allow-Credentials: true');
        }

        $this->load->model('account/customer');

        if (isset($this->session->data['token_id']) || isset($_SESSION['token_id'])) {
            $token = $this->session->data['token_id'];
            $this->session->data['rest_session_id'] = $this->session->getId();

            $this->model_account_customer->updateSession($this->session->data, $token);
            unset($_SESSION['token_id']);
        }

        if (isset($this->session->data['customer_id']) && !empty($this->session->data['customer_id'])) {
            $this->model_account_customer->updateCustomerData($this->session, $this->session->data['customer_id']);
        }

        $this->response->addHeader($this->httpVersion . " " . $this->statusCode . " " . $statusMessage);
        $this->response->addHeader('Content-Type: application/json; charset=utf-8');

        if (defined('JSON_UNESCAPED_UNICODE')) {
            $this->response->setOutput(json_encode($this->json, JSON_UNESCAPED_UNICODE));
        } else {
            $this->response->setOutput($this->rawJsonEncode($this->json));
        }

        $this->response->output();

        die;
    }

    public function getHttpStatusMessage($statusCode)
    {
        $httpStatus = array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed'
        );

        return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $httpStatus[500];
    }

    private function rawJsonEncode($input, $flags = 0)
    {
        $fails = implode('|', array_filter(array(
            '\\\\',
            $flags & JSON_HEX_TAG ? 'u003[CE]' : '',
            $flags & JSON_HEX_AMP ? 'u0026' : '',
            $flags & JSON_HEX_APOS ? 'u0027' : '',
            $flags & JSON_HEX_QUOT ? 'u0022' : '',
        )));
        $pattern = "/\\\\(?:(?:$fails)(*SKIP)(*FAIL)|u([0-9a-fA-F]{4}))/";
        $callback = function ($m) {
            return html_entity_decode("&#x$m[1];", ENT_QUOTES, 'UTF-8');
        };
        return preg_replace_callback($pattern, $callback, json_encode($input, $flags));
    }

    public function validateToken()
    {
        // Handle a request to a resource and authenticate the access token
        $server = $this->getOauthServer();

        $this->apiRequest = OAuth2\Request::createFromGlobals();

        if (!$server->verifyResourceRequest($this->apiRequest)) {
            $serverResp = $server->getResponse();


            $error_description = $serverResp->getParameter('error_description');

            $this->json['error'] = array(
                'error_description' => !empty($error_description)
                && $error_description != "NULL" ? $error_description : $serverResp->getStatusText()
            );

            $this->statusCode = $serverResp->getStatusCode();

            $this->sendResponse();
        }
    }

    public function getOauthServer()
    {
        //$dsn      = DB_DRIVER.':dbname='.DB_DATABASE.';host='.DB_HOSTNAME;
        $dsn = 'mysql:dbname=' . DB_DATABASE . ';host=' . DB_HOSTNAME;
        $username = DB_USERNAME;
        $password = DB_PASSWORD;

        // Autoloading (composer is preferred, but for this example let's just do this)
        require_once(DIR_SYSTEM . 'oauth2-server-php/src/OAuth2/Autoloader.php');
        OAuth2\Autoloader::register();

        $config = array(
            'id_lifetime' => $this->config->get('rest_api_token_ttl'),
            'access_lifetime' => $this->config->get('rest_api_token_ttl')
        );

        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $oauthServer = new OAuth2\Server($storage, $config);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $oauthServer->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

        return $oauthServer;
    }

    private function getTokenValue()
    {
        $server = $this->getOauthServer();
        return $server->getAccessTokenData($this->apiRequest);
    }

    private function update_session($token, $data)
    {

        if (isset($data['rest_session_id'])) {
            $this->session->start('default', $data['rest_session_id']);
        }

        if (!empty($data)) {
            $this->session->data = $data;
        }

        $this->session->data['token_id'] = $token;

        if (isset($data['customer_id']) && !empty($data['customer_id'])) {
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->loginCustomerById($data['customer_id']);

            if ($customer_info) {
                $this->customer->login($customer_info['email'], "", true);
            }
        }

        $this->tax->unsetRates();

        if (isset($this->session->data['shipping_address'])) {
            $this->tax->setShippingAddress($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
        } elseif ($this->config->get('config_tax_default') == 'shipping') {
            $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }

        if (isset($this->session->data['payment_address'])) {
            $this->tax->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
        } elseif ($this->config->get('config_tax_default') == 'payment') {
            $this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }

        $this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

        $this->registry->set('cart', new Cart\Cart($this->registry));
    }

    private function setSystemParameters()
    {

        $headers = $this->getRequestHeaders();

        //set currency
        if (isset($headers['x-oc-currency'])) {
            $currency = $headers['x-oc-currency'];
            if (!empty($currency)) {
                $this->currency->setRestCurrencyCode($currency);
                $this->session->data['currency'] = $currency;
            } else {
                $this->currency->setRestCurrencyCode($this->session->data['currency']);
            }
        } else {
            $this->currency->setRestCurrencyCode($this->session->data['currency']);
        }

        //set store ID
        if (isset($headers['x-oc-store-id'])) {
            $this->config->set('config_store_id', $headers['x-oc-store-id']);
        }

        $this->load->model('localisation/language');
        $allLanguages = $this->model_localisation_language->getLanguages();

        if (count($allLanguages) > 1) {
            $this->multilang = 1;
        }

        //set language
        if (isset($headers['x-oc-merchant-language'])) {
            $osc_lang = $headers['x-oc-merchant-language'];

            $languages = array();
            $this->load->model('localisation/language');
            $all = $this->model_localisation_language->getLanguages();

            foreach ($all as $result) {
                $languages[$result['code']] = $result;
            }

            if (isset($languages[$osc_lang])) {
                $this->session->data['language'] = $osc_lang;
                $this->config->set('config_language', $osc_lang);
                $this->config->set('config_language_id', $languages[$osc_lang]['language_id']);

                if (isset($languages[$osc_lang]['directory']) && !empty($languages[$osc_lang]['directory'])) {
                    $directory = $languages[$osc_lang]['directory'];
                } else {
                    $directory = $languages[$osc_lang]['code'];
                }

                $language = new \Language($directory);
                $language->load($directory);
                $this->registry->set('language', $language);
            }
        }

        if (isset($headers['x-oc-image-dimension'])) {
            $d = $headers['x-oc-image-dimension'];
            $d = explode('x', $d);
            $this->config->set('config_rest_api_image_width', $d[0]);
            $this->config->set('config_rest_api_image_height', $d[1]);
        } else {
            $this->config->set('config_rest_api_image_width', 500);
            $this->config->set('config_rest_api_image_height', 500);
        }
    }

    private function getRequestHeaders()
    {
        $arh = array();
        $rx_http = '/\AHTTP_/';

        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);

                $rx_matches = explode('_', $arh_key);

                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }

                    $arh_key = implode('-', $rx_matches);
                }

                $arh[strtolower($arh_key)] = $val;
            }
        }

        return ($arh);

    }

    public function getPost()
    {

        $request = $this->apiRequest;
        $post = $request->request;

        if (!is_array($post) || empty($post)) {
            $this->statusCode = 400;
            $this->json['error'][] = 'Invalid request body, please validate the json object';
            $this->sendResponse();
        }

        return $post;
    }

    public function returnDeprecated()
    {
        $this->statusCode = 400;
        $this->json['error'] = "This service has been removed for security reasons.Please contact us for more information.";

        return $this->sendResponse();
    }

    public function clearTokensTable($token = null, $sessionid = null)
    {
        //delete all previous token to this session and delete all expired session
        $this->load->model('account/customer');
        $this->model_account_customer->clearTokens($token, $sessionid);
    }
}