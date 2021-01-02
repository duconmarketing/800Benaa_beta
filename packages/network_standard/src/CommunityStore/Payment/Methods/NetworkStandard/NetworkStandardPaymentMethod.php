<?php

namespace Concrete\Package\NetworkStandard\Src\CommunityStore\Payment\Methods\NetworkStandard;

use Page;
use Core;
use URL;
use Config;
use Session;
use Log;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as StorePaymentMethod;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Cart\Cart as StoreCart;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\Order as StoreOrder;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderStatus\OrderStatus as StoreOrderStatus;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;
use \Concrete\Package\NetworkStandard\Src\CommunityStore\Payment\Methods\NetworkStandard\NetworkonlieBitmapPaymentIntegration;

class NetworkStandardPaymentMethod extends StorePaymentMethod {

    public function dashboardForm() {
        $this->set('networkMerchantID', Config::get('network_standard.networkMerchantID'));
        $this->set('networkMerchantKey', Config::get('network_standard.networkMerchantKey'));
        $this->set('networkStandardMode', Config::get('network_standard.networkStandardMode'));

        $this->set('form', Core::make("helper/form"));
    }

    public function save(array $data = []) {
        Config::save('network_standard.networkMerchantID', $data['networkMerchantID']);
        Config::save('network_standard.networkMerchantKey', $data['networkMerchantKey']);
        Config::save('network_standard.networkStandardMode', $data['networkStandardMode']);
    }

    public function validate($args, $e) {

        $pm = StorePaymentMethod::getByHandle('network_standard');
        if ($args['paymentMethodEnabled'][$pm->getID()] == 1) {
            if ($args['networkMerchantID'] == "") {
                $e->add(t("Merchend ID must be set"));
            }
            if ($args['networkMerchantKey'] == "") {
                $e->add(t("Merchant Key must be set"));
            }
        }

        return $e;
    }

    public function redirectForm() {
        $customer = new StoreCustomer();
        $storeOrder = StoreOrder::getByID(Session::get('orderID'));
        $tot = number_format($storeOrder->getTotal(), 2, ".", "");

        $outletRef = '33c4ddf7-d153-4320-ab94-e19e58714fe1';
        $apikey = 'Njc5Yzg0NmMtMDI2My00YzU1LWIyMzYtYzI2OTVhNmY4OTJiOmYwNjI3ZjczLWFmMGYtNDkyZS1iZmE5LTI5ZjA4YmEwODc2Mw==';

        $idServiceURL = "https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token";
        $txnServiceURL = "https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/" . $outletRef . "/orders";

        $tokenHeaders = array("accept: application/vnd.ni-identity.v1+json", "authorization: Basic " . $apikey, "content-type: application/vnd.ni-identity.v1+json");
//        $tokenResponse = $this->curlRequest("POST", $idServiceURL, $tokenHeaders, http_build_query(array('grant_type' => 'client_credentials')));
        $tokenResponse = $this->curlRequest("POST", $idServiceURL, $tokenHeaders, "{\"realmName\":\"ni\"}");
        $tokenResponse = json_decode($tokenResponse);
        $access_token = $tokenResponse->access_token;
        $redirectURL = URL::to('/checkout/networkresponse');

        $order = new \stdClass();
        $order->action = "SALE";                                        // Transaction mode ("AUTH" = authorize only, no automatic settle/capture, "SALE" = authorize + automatic settle/capture)
        $order->amount = new \stdClass();
        $order->amount->currencyCode = "AED";                           // Payment currency ('AED' only for now)
        $order->amount->value = $tot * 100;                                   // Minor units (1000 = 10.00 AED)
//        $order->language = "en";                                        // Payment page language ('en' or 'ar' only)
        $order->merchantOrderReference = $storeOrder->getOrderID();
        $order->merchantAttributes = new \stdClass();
        $order->merchantAttributes->redirectUrl = 'http://marketing-test-domain.com/800benaa_beta/checkout/networkresponse';
        $order->merchantAttributes->skipConfirmationPage = 'true';
//        $order->merchantAttributes->skipConfirmationPage = 'TRUE';
        $order->billingAddress = new \stdClass();
        $order->billingAddress->firstName = $customer->getValue("billing_first_name");
        $order->billingAddress->lastName = $customer->getValue("billing_last_name");
        $order->billingAddress->address1 = $customer->getValue("billing_address")->address1;
        $order->billingAddress->city = $customer->getValue("billing_address")->city;
        $order->billingAddress->countryCode = $customer->getValue("billing_address")->country;
        $order->merchantDefinedData = new \stdClass();
        $order->merchantDefinedData->website = $_SERVER['SERVER_NAME'];
        $order->emailAddress = $customer->getEmail();

        $order = json_encode($order);

        $orderCreateHeaders = array("Authorization: Bearer " . $access_token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");
        $orderCreateResponse = $this->curlRequest("POST", $txnServiceURL, $orderCreateHeaders, $order);

        $orderCreateResponse = json_decode($orderCreateResponse);
        $paymentLink = $orderCreateResponse->_links->payment->href;     // the link to the payment page for redirection (either full-page redirect or iframe)
        $orderReference = $orderCreateResponse->reference;              // the reference to the order, which you should store in your records for future interaction with this order
        header("Location: " . $paymentLink);                              // execute redirect
        $this->set('requestParameter', '');
        $this->set('actionUrl', $paymentLink);
        // print_r($orderCreateResponse);die;
    }

    public function curlRequest($type, $url, $headers, $data = "") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($type == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $server_output = curl_exec($ch);
        curl_close($ch);
        return $server_output;
    }

    public function submitPayment() {

        //nothing to do except return true
        return array('error' => 0, 'transactionReference' => '');
    }

    public function getAction() {
        if (Config::get('network_standard.networkStandardMode') == true) {
            return "https://uat-NeO.network.ae/direcpay/secure/PaymentTxnServlet";
        } else {
            return "https://NeO.network.ae/direcpay/secure/PaymentTxnServlet";
        }
    }

    public static function decryptData() {
        // Read POST data
        // reading posted data directly from $_POST causes serialization
        // issues with array data in POST. Reading raw POST data from input stream instead.

//        if ($json = file_get_contents("php://input")) {
//            $orderStatusResponse = json_decode($json);
//            $orderStatus = $orderStatusResponse->_embedded->payment[0]->state;
//
//            if ($orderStatus == 'CAPTURED') {
//                $orderNumber = $orderStatusResponse->merchantOrderReference;
//                $order = StoreOrder::getByID($orderNumber);
//                $order->completeOrder($orderNumber);
//                $order->updateStatus(StoreOrderStatus::getStartingStatus()->getHandle());
//
//                // redirect to success page
//
//                $redirect = \Redirect::page(Page::getByID('215'));
//                $redirect->setTargetUrl('/800benaa_beta/checkout/complete');
//                $redirect->send();
//            } elseif (is_error) {
//                $redirect = \Redirect::page(Page::getByID('596'));
//                $redirect->setTargetUrl('/800benaa_beta/checkout/fail?error=Payment Failed');
//                $redirect->send();
//            }
//        }
        $raw_get_data = $_GET;

        $outletRef = '33c4ddf7-d153-4320-ab94-e19e58714fe1';
        $apikey = 'Njc5Yzg0NmMtMDI2My00YzU1LWIyMzYtYzI2OTVhNmY4OTJiOmYwNjI3ZjczLWFmMGYtNDkyZS1iZmE5LTI5ZjA4YmEwODc2Mw==';

        $idServiceURL = "https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token";
        $txnServiceURL = "https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/$outletRef/orders/" . $raw_get_data['ref'];

        $tokenHeaders = array("accept: application/vnd.ni-identity.v1+json", "authorization: Basic " . $apikey, "content-type: application/vnd.ni-identity.v1+json");
        $tokenResponse = self::curlRequest("POST", $idServiceURL, $tokenHeaders, "{\"realmName\":\"ni\"}");
        $tokenResponse = json_decode($tokenResponse);
        $access_token = $tokenResponse->access_token;

        $orderStatusHeaders = array("Authorization: Bearer " . $access_token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");
        $orderStatusResponse = self::curlRequest("GET", $txnServiceURL, $orderStatusHeaders);
        $orderStatusResponse = json_decode($orderStatusResponse);
        $orderStatus = $orderStatusResponse->_embedded->payment[0]->state;

        if ($orderStatus == 'CAPTURED') {
            $orderNumber = $orderStatusResponse->merchantOrderReference;
            $order = StoreOrder::getByID($orderNumber);
            
            $order->completeOrder($orderNumber);
            
            $order->updateStatus(StoreOrderStatus::getStartingStatus()->getHandle());

            // redirect to success page

            $redirect = \Redirect::page(Page::getByID('215'));
            $redirect->setTargetUrl('/800benaa_beta/checkout/complete');
            $redirect->send();
        } elseif (is_error) {
            $redirect = \Redirect::page(Page::getByID('596'));
            $redirect->setTargetUrl('/800benaa_beta/checkout/fail?error=Payment Failed');
            $redirect->send();
        }
    }

    public function getPaymentMinimum() {
        return 0.03;
    }

    public function getName() {
        return 'Paypal Standard';
    }

    public function isExternal() {
        return true;
    }

}
