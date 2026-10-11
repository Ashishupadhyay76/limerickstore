<?php
/**
 * Plugin Name: WooCommerce PayGlocal Payment
 * Plugin URI: https://payglocal.in
 * Description: This plugin allows any merchant to accept payments with payglocal payment service
 * Author: PayGlocal
 * Author URI: https://payglocal.in
 * Version: 1.1.0
 * Text Domain: woocommerce-payglocal-payment
 * Domain Path: /languages
 *
 */
if (!defined('ABSPATH')) {
    exit;
}

define('WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR', rtrim(plugin_dir_path(__FILE__), '/'));
define('WC_PAYGLOCAL_PAYMENT_PLUGIN_URL', rtrim(plugin_dir_url(__FILE__), '/'));

// Require composer.
require __DIR__ . '/vendor/autoload.php';

use Jose\Component\Encryption\Algorithm\ContentEncryption\A128CBCHS256;
use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\Converter\StandardConverter;
use Jose\Component\Encryption\Algorithm\KeyEncryption\A256KW;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256CBCHS512;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Core\JWK;
use Jose\Component\Encryption\Serializer\CompactSerializer;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer as SigCompactSerializer;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\JWSLoader;

add_action('plugins_loaded', 'wc_payglocal_payment_gateway_init', 0);

function wc_payglocal_payment_gateway_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    class WC_PayGlocal_Payment_Gateway extends WC_Payment_Gateway
    {
        public $payment_mode;
        public $merchant_id;
        public $public_kid;
        public $private_kid;
        public $public_pem;
        public $private_pem;
        public $payment_endpoint;

        public function __construct()
        {
            $this->id = 'payglocal_payment_gateway';
            $this->icon = apply_filters('woocommerce_payglocal_icon',
                plugins_url('assets/images/payglocal.png', __FILE__));
            $this->has_fields = false;
            $this->method_title = __('International Debit / Credit Cards', 'woocommerce-payglocal-payment');
            $this->method_description = __('This plugin allows any merchant to accept payments with payglocal payment service.',
                'woocommerce-payglocal-payment');

            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->payment_mode = $this->get_option('payment_mode');
            $this->merchant_id = $this->get_option('merchant_id');
            $this->public_kid = $this->get_option('public_kid');
            $this->private_kid = $this->get_option('private_kid');
            $this->public_pem = $this->get_option('public_pem');
            $this->private_pem = $this->get_option('private_pem');
            $this->payment_endpoint = ($this->payment_mode) ? 'https://api.prod.payglocal.in' : 'https://api.uat.payglocal.in';
            add_action('woocommerce_update_options_payment_gateways_' . $this->id,
                array($this, 'process_admin_options'));
            add_action('woocommerce_api_wc_payglocal_payment_gateway',
                array($this, 'check_payglocal_payment_response'));
            add_action('woocommerce_order_details_after_order_table', array($this, 'order_details_after_order_table'),
                9);
            add_action('woocommerce_admin_order_data_after_shipping_address',
                array($this, 'admin_order_data_after_order_details'));
        }

        public function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce-payglocal-payment'),
                    'label' => __('Enable PayGlocal Payment Gateway', 'woocommerce-payglocal-payment'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => 'no',
                ),
                'title' => array(
                    'title' => __('Title', 'woocommerce-payglocal-payment'),
                    'description' => __('Payment method title to show on the checkout page.',
                        'woocommerce-payglocal-payment'),
                    'type' => 'text',
                    'default' => __('International Debit / Credit Cards', 'woocommerce-payglocal-payment'),
                    'desc_tip' => false,
                    'required' => true,
                ),
                'description' => array(
                    'title' => __('Description', 'woocommerce-payglocal-payment'),
                    'description' => __('Payment description to show on the checkout page.',
                        'woocommerce-payglocal-payment'),
                    'type' => 'text',
                    'default' => __('You will be redirected to payglocal hosted payment page.',
                        'woocommerce-payglocal-payment'),
                    'desc_tip' => false,
                ),
                'payment_mode' => array(
                    'title' => __('Payment Mode', 'woocommerce-payglocal-payment'),
                    'description' => __('Chose payglocal payment mode ', 'woocommerce-knet-payment'),
                    'type' => 'select',
                    'default' => '0',
                    'options' => array(
                        '1' => __('Live', 'woocommerce-payglocal-payment'),
                        '0' => __('Sandbox', 'woocommerce-payglocal-payment'),
                    )
                ),
                'merchant_id' => array(
                    'title' => __('Merchant ID', 'woocommerce-payglocal-payment'),
                    'type' => 'text',
                    'description' => __('Enter a merchant id which is provided by payglocal.',
                        'woocommerce-payglocal-payment'),
                    'default' => '',
                    'desc_tip' => false,
                ),
                'public_kid' => array(
                    'title' => __('Public KID', 'woocommerce-payglocal-payment'),
                    'type' => 'password',
                    'description' => __('Enter a public kid which is provided by payglocal.',
                        'woocommerce-payglocal-payment'),
                    'default' => '',
                    'desc_tip' => false,
                ),
                'private_kid' => array(
                    'title' => __('Private KID', 'woocommerce-payglocal-payment'),
                    'type' => 'password',
                    'description' => __('Enter a private kid which is provided by payglocal.',
                        'woocommerce-payglocal-payment'),
                    'default' => '',
                    'desc_tip' => false,
                ),
                'public_pem' => array(
                    'title' => __('Public Pem', 'woocommerce-payglocal-payment'),
                    'type' => 'file',
                    'description' => __('Upload a public pem file which is provided by payglocal.',
                        'woocommerce-payglocal-payment'),
                    'default' => '',
                    'desc_tip' => false,
                ),
                'private_pem' => array(
                    'title' => __('Private Pem', 'woocommerce-payglocal-payment'),
                    'type' => 'file',
                    'description' => __('Upload a private pem file which is provided by payglocal.',
                        'woocommerce-payglocal-payment'),
                    'default' => '',
                    'desc_tip' => false,
                ),
            );
        }

        public function generate_file_html($key, $data)
        {
            $field_key = $this->get_field_key($key);
            $defaults = array(
                'title' => '',
                'disabled' => false,
                'class' => '',
                'css' => '',
                'placeholder' => '',
                'type' => 'text',
                'desc_tip' => false,
                'description' => '',
                'custom_attributes' => array(),
            );

            $data = wp_parse_args($data, $defaults);
            ob_start();

            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label
                        for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?><?php echo $this->get_tooltip_html($data); // WPCS: XSS ok.
                        ?></label>
                </th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span>
                        </legend>
                        <input class="input-text regular-input <?php echo esc_attr($data['class']); ?>"
                               type="<?php echo esc_attr($data['type']); ?>" name="<?php echo esc_attr($field_key); ?>"
                               id="<?php echo esc_attr($field_key); ?>" style="<?php echo esc_attr($data['css']); ?>"
                               value=""
                               placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php disabled($data['disabled'],
                            true); ?> <?php echo $this->get_custom_attribute_html($data); // WPCS: XSS ok.
                        ?> />
                        <?php echo esc_attr($this->get_option($key)); ?>
                        <?php echo $this->get_description_html($data); // WPCS: XSS ok.
                        ?>
                    </fieldset>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }

        public function process_admin_options()
        {
            $current_public_pem = $this->public_pem;
            $current_private_pem = $this->private_pem;

            parent::process_admin_options();

            $public_pem_name = $_FILES['woocommerce_payglocal_payment_gateway_public_pem']['name'];
            if (!empty($public_pem_name)) {
                if (file_exists(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $this->public_pem)) {
                    wp_delete_file(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $this->public_pem);
                }
                if (move_uploaded_file($_FILES['woocommerce_payglocal_payment_gateway_public_pem']['tmp_name'],
                    WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $public_pem_name)) {
                    $this->update_option('public_pem', $public_pem_name);
                }
            } else {
                $this->update_option('public_pem', $current_public_pem);
            }

            $private_pem_name = $_FILES['woocommerce_payglocal_payment_gateway_private_pem']['name'];
            if (!empty($private_pem_name)) {
                if (file_exists(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $this->private_pem)) {
                    wp_delete_file(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $this->private_pem);
                }
                if (move_uploaded_file($_FILES['woocommerce_payglocal_payment_gateway_private_pem']['tmp_name'],
                    WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $private_pem_name)) {
                    $this->update_option('private_pem', $private_pem_name);
                }
            } else {
                $this->update_option('private_pem', $current_private_pem);
            }
        }

        public function process_payment($order_id)
        {
            $logger = wc_get_logger();
            $logger->info("process_payment Called", array('source' => 'failed-orders'));
            $payment = $this->createPayGlSPaymentUrl($order_id);
            $logger->info("payment", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($payment, true), array('source' => 'failed-orders'));
            if (isset($payment['data']['redirectUrl']) && array_key_exists('redirectUrl', $payment['data'])) {
                $order = wc_get_order($order_id);
                $order->update_meta_data('_payglocal_gid', $payment['gid']);
                $order->save();
                return array(
                    'result' => 'success',
                    'redirect' => $payment['data']['redirectUrl']
                );
            } else {
                //$message = $payment['errors']['detailedMessage'];
                //$message = $payment['errors']['displayMessage'];
                $message = __('Unfortunately your order cannot be processed as an error has occured. Please contact site owner.',
                    'woocommerce-payglocal-payment');
                wc_add_notice($message, 'error');
            }
        }

        // public function createPayGlSPaymentUrl($order_id)
        // {
        //     $logger = wc_get_logger();
        //     $logger->info("createPayGlSPaymentUrl Called", array('source' => 'failed-orders'));
        //     $jweToken = $this->createPayGlSPaymentJweToken($order_id);
        //     $logger->info("jweToken", array('source' => 'failed-orders'));
        //     $logger->info(wc_print_r($jweToken, true), array('source' => 'failed-orders'));
        //     $jwsToken = $this->createPayGlSPaymentJwsToken($jweToken);
        //     $logger->info("jwsToken", array('source' => 'failed-orders'));
        //     $logger->info(wc_print_r($jwsToken, true), array('source' => 'failed-orders'));
        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => $this->payment_endpoint . '/gl/v1/payments/initiate/paycollect',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS => $jweToken,
        //         CURLOPT_HTTPHEADER => array(
        //             'x-gl-token-external: ' . $jwsToken,
        //             'Content-Type: text/plain'
        //         ),
        //     ));

        //     $response = curl_exec($curl);
        //     $data = json_decode($response, true);
        //     $logger->info("paycollect response", array('source' => 'failed-orders'));
        //     $logger->info(wc_print_r($response, true), array('source' => 'failed-orders'));
        //     curl_close($curl);

        //     return $data;
        // }
        
        public function createPayGlSPaymentUrl($order_id) {
            $logger = wc_get_logger();
            $logger->info("=== Starting createPayGlSPaymentUrl ===", array('source' => 'payglocal-debug'));
            
            try {
                // Step 1: Create JWE Token
                $logger->info("Creating JWE Token...", array('source' => 'payglocal-debug'));
                $jweToken = $this->createPayGlSPaymentJweToken($order_id);
                if (empty($jweToken)) {
                    throw new Exception("JWE Token creation failed");
                }
                $logger->info("JWE Token created successfully", array('source' => 'payglocal-debug'));
                
                // Step 2: Create JWS Token
                $logger->info("Creating JWS Token...", array('source' => 'payglocal-debug'));
                $jwsToken = $this->createPayGlSPaymentJwsToken($jweToken);
                if (empty($jwsToken)) {
                    throw new Exception("JWS Token creation failed");
                }
                $logger->info("JWS Token created successfully", array('source' => 'payglocal-debug'));
                
                // Step 3: Prepare cURL request
                $logger->info("Preparing cURL request to: " . $this->payment_endpoint . '/gl/v1/payments/initiate/paycollect', array('source' => 'payglocal-debug'));
                
                $headers = array(
                    'x-gl-token-external: ' . $jwsToken,
                    'Content-Type: text/plain'
                );
                
                $logger->info("Request Headers:", array('source' => 'payglocal-debug'));
                $logger->info(print_r($headers, true), array('source' => 'payglocal-debug'));
        
                // Initialize cURL
                $curl = curl_init();
                
                // Set cURL options with error handling
                $curlOptions = array(
                    CURLOPT_URL => $this->payment_endpoint . '/gl/v1/payments/initiate/paycollect',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $jweToken,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_VERBOSE => true
                );
                
                curl_setopt_array($curl, $curlOptions);
                
                // Execute cURL request
                $logger->info("Executing cURL request...", array('source' => 'payglocal-debug'));
                $response = curl_exec($curl);
                
                // Check for cURL errors
                if (curl_errno($curl)) {
                    $error = curl_error($curl);
                    $logger->error("cURL Error: " . $error, array('source' => 'payglocal-debug'));
                    throw new Exception("Payment gateway connection error: " . $error);
                }
                
                // Get HTTP status code
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $logger->info("HTTP Response Code: " . $httpCode, array('source' => 'payglocal-debug'));
                
                curl_close($curl);
                
                // Log raw response
                $logger->info("Raw Response:", array('source' => 'payglocal-debug'));
                $logger->info($response, array('source' => 'payglocal-debug'));
                
                // Parse response
                $data = json_decode($response, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid JSON response: " . json_last_error_msg());
                }
                
                // Log parsed response
                $logger->info("Parsed Response:", array('source' => 'payglocal-debug'));
                $logger->info(print_r($data, true), array('source' => 'payglocal-debug'));
                
                // Check for redirect URL
                if (!isset($data['data']['redirectUrl'])) {
                    $logger->error("No redirect URL in response", array('source' => 'payglocal-debug'));
                    if (isset($data['errors'])) {
                        $logger->error("Payment Error: " . print_r($data['errors'], true), array('source' => 'payglocal-debug'));
                    }
                } else {
                    $logger->info("Redirect URL found: " . $data['data']['redirectUrl'], array('source' => 'payglocal-debug'));
                }
                
                return $data;
                
            } catch (Exception $e) {
                $logger->error("Exception in createPayGlSPaymentUrl: " . $e->getMessage(), array('source' => 'payglocal-debug'));
                throw $e;
            }
        }

        protected function createPayGlSPaymentJweToken($order_id)
        {
            $logger = wc_get_logger();
            $logger->info("createPayGlSPaymentJweToken Called", array('source' => 'failed-orders'));
            $keyEncryptionAlgorithmManager = new AlgorithmManager([
                new RSAOAEP256(),
            ]);
            $contentEncryptionAlgorithmManager = new AlgorithmManager([
                new A128CBCHS256(),
            ]);
            $compressionMethodManager = new CompressionMethodManager([
                new Deflate(),
            ]);
            $jweBuilder = new JWEBuilder(
                $keyEncryptionAlgorithmManager,
                $contentEncryptionAlgorithmManager,
                $compressionMethodManager
            );

            $key = JWKFactory::createFromKeyFile(
                WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $this->public_pem,
                null,
                [
                    'kid' => $this->public_kid,
                    'use' => 'enc',
                    'alg' => 'RSA-OAEP-256',
                ]
            );
            $logger->info("key", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($key, true), array('source' => 'failed-orders'));
            $header = [
                'issued-by' => $this->merchant_id,
                'enc' => 'A128CBC-HS256',
                'exp' => 30000,
                'iat' => (string)round(microtime(true) * 1000),
                'alg' => 'RSA-OAEP-256',
                'kid' => $this->public_kid,
            ];
            $logger->info("header", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($header, true), array('source' => 'failed-orders'));

            $payload = $this->createPayGlSPaymentDatas($order_id);
            $logger->info("payload", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($payload, true), array('source' => 'failed-orders'));

            $jwe = $jweBuilder
                ->create()
                ->withPayload($payload)
                ->withSharedProtectedHeader($header)
                ->addRecipient($key)
                ->build();

            $serializer = new CompactSerializer();
            $token = $serializer->serialize($jwe, 0);
            $logger->info("token", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($token, true), array('source' => 'failed-orders'));

            return $token;
        }

        protected function createPayGlSPaymentJwsToken($jweToken)
        {
            $logger = wc_get_logger();
            $logger->info("createPayGlSPaymentJwsToken Called", array('source' => 'failed-orders'));
            $algorithmManager = new AlgorithmManager([
                new RS256(),
            ]);

            $jwsBuilder = new JWSBuilder(
                $algorithmManager
            );

            $jwskey = JWKFactory::createFromKeyFile(
                WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $this->private_pem,
                null,
                [
                    'kid' => $this->private_kid,
                    'use' => 'sig'
                    //'alg' => 'RSA-OAEP-256',
                ]
            );

            $jwsheader = [
                'issued-by' => $this->merchant_id,
                'is-digested' => 'true',
                'alg' => 'RS256',
                'x-gl-enc' => 'true',
                'x-gl-merchantId' => $this->merchant_id,
                'kid' => $this->private_kid
            ];
            $logger->info("jwsheader", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($jwsheader, true), array('source' => 'failed-orders'));

            $hashedPayload = base64_encode(hash('sha256', $jweToken, $BinaryOutputMode = true));

            $jwspayload = json_encode([
                'digest' => $hashedPayload,
                'digestAlgorithm' => "SHA-256",
                'exp' => 300000,
                'iat' => (string)round(microtime(true) * 1000)
            ]);

            $logger->info("jwspayload", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($jwspayload, true), array('source' => 'failed-orders'));

            $jws = $jwsBuilder
                ->create()
                ->withPayload($jwspayload)
                ->addSignature($jwskey, $jwsheader)
                ->build();

            $jwsserializer = new \Jose\Component\Signature\Serializer\CompactSerializer(); // The serializer
            $jwstoken = $jwsserializer->serialize($jws, 0);
            $logger->info("jwstoken", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($jwstoken, true), array('source' => 'failed-orders'));

            return $jwstoken;
        }

        protected function createPayGlSPaymentDatas($order_id)
        {
            $logger = wc_get_logger();
            $logger->info("createPayGlSPaymentDatas Called", array('source' => 'failed-orders'));

            $order = wc_get_order($order_id);

            $products = array();
            foreach ($order->get_items() as $item_id => $item) {
                $product = $item->get_product();
                $data = [
                    'productDescription' => $item->get_name(),
                   // 'itemUnitPrice' => $product->get_price(),
                    'itemQuantity' => $item->get_quantity()
                ];
                $products[] = $data;
            }

            $data = [
                "merchantTxnId" => $order->get_id() . $this->createPayGlSRandomString(16),
                "merchantUniqueId" => $order->get_id() . '#' . $this->createPayGlSRandomString(16),
                "paymentData" => array(
                    "totalAmount" => $order->get_total(),
                    "txnCurrency" => $order->get_currency(),
                    "billingData" => array(
                        "firstName" => $order->get_billing_first_name(),
                        "lastName" => $order->get_billing_last_name(),
                        "addressStreet1" => $order->get_billing_address_1(),
                        "addressStreet2" => $order->get_billing_address_2(),
                        "addressCity" => $order->get_billing_city(),
                        "addressState" => $order->get_billing_state(),
                        "addressPostalCode" => $order->get_billing_postcode(),
                        "addressCountry" => $order->get_billing_country(),
                        "emailId" => $order->get_billing_email(),
                        "callingCode" => "",
                        "phoneNumber" => $order->get_billing_phone(),
                    )
                ),
                "riskData" => array(
                    "orderData" => $products,
                    "customerData" => array(
                        //"merchantAssignedCustomerId" => $customer->id,
                        //"customerAccountType" => "1",
                        //"customerAccountCreationDate" => date_create($customer->date_add),
                        "ipAddress" => $order->get_customer_ip_address(),
                        "httpAccept" => isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null,
                        "httpUserAgent" => $order->get_customer_user_agent(),
                    ),
                    "shippingData" => array(
                        "firstName" => $order->get_shipping_first_name(),
                        "lastName" => $order->get_shipping_last_name(),
                        "addressStreet1" => $order->get_shipping_address_1(),
                        "addressStreet2" => $order->get_shipping_address_2(),
                        "addressCity" => $order->get_shipping_city(),
                        "addressState" => $order->get_shipping_state(),
                        "addressPostalCode" => $order->get_shipping_postcode(),
                        "addressCountry" => $order->get_shipping_country(),
                        "emailId" => $order->get_billing_email(),
                        "callingCode" => "",
                        "phoneNumber" => $order->get_shipping_phone(),
                    )
                ),
                "clientPlatformDetails" => array(
                    "platformName" => "Woocommerce",
                    "platformVersion" => $order->get_version(),
                ),
                "merchantCallbackURL" => WC()->api_request_url('WC_PayGlocal_Payment_Gateway')
            ];

            $logger->info("Data", array('source' => 'failed-orders'));
            $logger->info(wc_print_r($data, true), array('source' => 'failed-orders'));

            return json_encode($data);
        }

        protected function createPayGlSRandomString($length = 16)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        public function check_payglocal_payment_response()
        {
            WC()->session->set_customer_session_cookie(true);
            //$response = $_POST;
            $response = $_REQUEST;
            if (isset($response['x-gl-token']) && array_key_exists('x-gl-token', $response)) {
                $payment = $this->verifyPayGlSPayment($response['x-gl-token']);
                if (isset($payment['merchantUniqueId']) && array_key_exists('merchantUniqueId', $payment)) {
                    list($order_id,) = explode('#', $payment['merchantUniqueId']);
                    $order = wc_get_order($order_id);
                    if (isset($payment['status']) && $payment['status'] == 'SENT_FOR_CAPTURE') {
                        $order->update_status('processing');
                        $order->update_meta_data('_payglocal_gid', $payment['gid']);
                        $order->update_meta_data('_payglocal_merchantid', $payment['merchantUniqueId']);
                        $order->update_meta_data('_payglocal_iat', $payment['iat']);
                        $order->save();
                        WC()->cart->empty_cart();
                        $redirect_url = esc_url($this->get_return_url($order));
                        wp_redirect($redirect_url);
                        exit;
                    } else {
                        $order->update_status('failed');
                        $order->update_meta_data('_payglocal_gid', $payment['gid']);
                        $order->update_meta_data('_payglocal_merchantid', $payment['merchantUniqueId']);
                        $order->update_meta_data('_payglocal_iat', $payment['iat']);
                        $order->save();
                        $message = __('Unfortunately your order cannot be processed as an error has occured. Please contact site owner.',
                            'woocommerce-payglocal-payment');
                        wc_add_notice($message, 'error');
                        $redirect_url = esc_url(wc_get_page_permalink('checkout'));
                        wp_redirect($redirect_url);
                        exit;
                    }
                } else {
                    $payment = $this->getPayGlSPaymentOrderStatus($payment['statusUrl']);
                    list($order_id,) = explode('#', $payment['data']['merchantTxnId']);
                    $order = wc_get_order($order_id);
                    $order->update_status('failed');
                    $order->update_meta_data('_payglocal_gid', $payment['gid']);
                    $order->save();
                    $message = __('Unfortunately your order cannot be processed as an error has occured. Please contact site owner.',
                        'woocommerce-payglocal-payment');
                    wc_add_notice($message, 'error');
                    $redirect_url = esc_url(wc_get_page_permalink('checkout'));
                    wp_redirect($redirect_url);
                    exit;
                }
            } else {
                $redirect_url = esc_url(wc_get_page_permalink('checkout'));
                wp_redirect($redirect_url);
                exit;
            }
        }

        protected function verifyPayGlSPayment($token)
        {
            $algorithmManager = new AlgorithmManager([
                new RS256(),
            ]);
            $jwsVerifier = new JWSVerifier(
                $algorithmManager
            );
            $jwk = JWKFactory::createFromKeyFile(
                WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $this->public_pem,
                null,
                [
                    'kid' => $this->public_kid,
                    'use' => 'sig'
                    //'alg' => 'RSA-OAEP-256',
                ]
            );
            $serializerManager = new JWSSerializerManager([
                new SigCompactSerializer(),
            ]);

            $jws = $serializerManager->unserialize($token);
            $isVerified = $jwsVerifier->verifyWithKey($jws, $jwk, 0);

            $headerCheckerManager = $payload = null;

            $jwsLoader = new JWSLoader(
                $serializerManager,
                $jwsVerifier,
                $headerCheckerManager
            );

            $jws = $jwsLoader->loadAndVerifyWithKey($token, $jwk, $signature, $payload);

            return json_decode($jws->getPayload(), true);
        }

        protected function getPayGlSPaymentOrderStatus($url)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);
            $data = json_decode($response, true);
            curl_close($curl);

            return $data;
        }

        public function admin_order_data_after_order_details($order)
        {
            $payment_method = $order->get_payment_method();
            if ('payglocal_payment_gateway' == $payment_method) {
                ob_start();
                echo "<h3>" . __('PayGlocal Payment Info', 'woocommerce-payglocal-payment') . "</h3>";
                echo "<p>" . __('Gid',
                        'woocommerce-payglocal-paymen') . " : " . $order->get_meta('_payglocal_gid') . "</p>";
                echo "<p>" . __('Merchant Unique Id',
                        'woocommerce-payglocal-paymen') . " : " . $order->get_meta('_payglocal_merchantid') . "</p>";
            }
        }

        public function order_details_after_order_table($order)
        {
            $payment_method = $order->get_payment_method();
            if ('payglocal_payment_gateway' == $payment_method) {

                ?>
                <h2 style="margin-top:20px;" class="woocommerce-order-details__title"><?php _e('PayGlocal Payment Info',
                        'woocommerce-payglocal-payment'); ?></h2>
                <table class="<?php echo $this->id; ?>_table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td><?php _e('Gid', 'woocommerce-payglocal-payment'); ?>:</td>
                        <td><?php echo $order->get_meta('_payglocal_gid'); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Merchant Unique Id', 'woocommerce-payglocal-payment'); ?>:</td>
                        <td><?php echo $order->get_meta('_payglocal_merchantid'); ?></td>
                    </tr>
                </table>
                <?php
            }
        }
    }

    function wc_payglocal_payment_gateway_class($methods)
    {
        $methods[] = 'WC_PayGlocal_Payment_Gateway';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'wc_payglocal_payment_gateway_class');

    function wc_payglocal_settings_link($links)
    {
        $plugin_links[] = '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=payglocal_payment_gateway') . '">' . __('Settings',
                'woocommerce-payglocal-payment') . '</a>';
        return array_merge($plugin_links, $links);
    }

    add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'wc_payglocal_settings_link');
}

function wc_payglocal_deactivation()
{
    $payment_gateway = WC()->payment_gateways->payment_gateways()['payglocal_payment_gateway'];

    $payment_gateway->update_option('payment_mode', '');
    $payment_gateway->update_option('merchant_id', '');
    $payment_gateway->update_option('public_kid', '');
    $payment_gateway->update_option('private_kid', '');

    if (file_exists(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $payment_gateway->public_pem)) {
        wp_delete_file(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $payment_gateway->public_pem);
    }
    if (file_exists(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $payment_gateway->private_pem)) {
        wp_delete_file(WC_PAYGLOCAL_PAYMENT_PLUGIN_DIR . '/' . $payment_gateway->private_pem);
    }

    $payment_gateway->update_option('public_pem', '');
    $payment_gateway->update_option('private_pem', '');
}

register_deactivation_hook(__FILE__, 'wc_payglocal_deactivation');
