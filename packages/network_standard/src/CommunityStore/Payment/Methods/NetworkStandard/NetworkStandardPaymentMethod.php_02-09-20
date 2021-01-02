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

class NetworkStandardPaymentMethod extends StorePaymentMethod 
{
    public function dashboardForm()
    {
        $this->set('networkMerchantID',Config::get('network_standard.networkMerchantID'));
        $this->set('networkMerchantKey',Config::get('network_standard.networkMerchantKey'));
        $this->set('networkStandardMode',Config::get('network_standard.networkStandardMode'));
        
        $this->set('form',Core::make("helper/form"));
    }
    
    public function save(array $data = [])
    {
		Config::save('network_standard.networkMerchantID',$data['networkMerchantID']);
		Config::save('network_standard.networkMerchantKey',$data['networkMerchantKey']);
        Config::save('network_standard.networkStandardMode',$data['networkStandardMode']);
        
    }
    public function validate($args,$e)
    {
		
        $pm = StorePaymentMethod::getByHandle('network_standard');
		if($args['paymentMethodEnabled'][$pm->getID()]==1){
			 if($args['networkMerchantID']==""){
                $e->add(t("Merchend ID must be set"));
            }
            if($args['networkMerchantKey']==""){
                $e->add(t("Merchant Key must be set"));
            }
        }
		
		return $e;
        
    }
    public function redirectForm()
    {
        $customer = new StoreCustomer();
        $totals = StoreCalculator::getTotals();
		
        $MerchantID = Config::get('network_standard.networkMerchantID');
		$MerchantKey = Config::get('network_standard.networkMerchantKey');
		
		$order = StoreOrder::getByID(Session::get('orderID'));
		$tot= number_format($order->getTotal(),2, ".","");
		$networkOnlineArray = array('Network_Online_setting' => array(
                                            'merchantKey'    => $MerchantKey, //Your key provided by network international
                                            'merchantId'     => $MerchantID, //  Your merchant ID ex: 201408191000001
                                            'collaboratorId' => 'NI',               // Constant used by Network Online international
                                            'iv'              => '0123456789abcdef',// Used for initializing CBC encryption mode
                                            'url'             => true              // Set to false if you are using testing environment , set to true if you are using live environment
                                ),
                                'Block_Existence_Indicator' => array(
                                            'transactionDataBlock'    => true,     // Transaction Data Block  ==> This is mandatory block  , 1
                                            'billingDataBlock'        => true,   // Billing Data Block      ==> This is an optional block ,0
                                            'shippingDataBlock'       => false,  // Shipping Data Block     ==> This is an optional block ,0
                                            'paymentDataBlock'        => false,  // Payment Data Block      ==> This is mandatory block , 1
                                            'merchantDataBlock'       => false,   // Merchant Data Block     ==> This is an optional block ,0
                                            'otherDataBlock'          => false,    // Other Details Data Block==> This is an optional block ,0
                                            'DCCDataBlock'            => false      // DCC Data Block          ==> This is an optional block ,0
                                ),
                                'Field_Existence_Indicator_Transaction' => array(
                                            'merchantOrderNumber'  => 'BNA'.$order->getOrderID(),
                                            'amount'               => $tot,
                                            'successUrl'           => URL::to('/checkout/networkresponse'),
                                            'failureUrl'           => URL::to('/checkout/networkresponse'),
                                            'transactionMode'      => 'INTERNET',
                                            'payModeType'          => '',
                                            'transactionType'      => '01',
                                            'currency'             => 'AED'
                                ),
                                'Field_Existence_Indicator_Billing' => array(
                                            'billToFirstName'       => $customer->getValue("billing_first_name"),
                                            'billToLastName'        => $customer->getValue("billing_last_name"),
                                            'billToStreet1'         => $customer->getValue("billing_address")->address1,
                                            'billToStreet2'         => $customer->getValue("billing_address")->address2,
                                            'billToCity'            => $customer->getValue("billing_address")->state_province,
                                            'billToState'           => $customer->getValue("billing_address")->state_province,
                                            'billtoPostalCode'      => ($customer->getValue("billing_address")->postal_code==""?'00000':$customer->getValue("billing_address")->postal_code),
                                            'billToCountry'         => $customer->getValue("billing_address")->country,
                                            'billToEmail'           => $customer->getEmail(),
                                            'billToMobileNumber'    => $customer->getValue("billing_phone"),
                                            'billToPhoneNumber1'    => '',
                                            'billToPhoneNumber2'    => '',
                                            'billToPhoneNumber3'    => ''
                                ),
                                'Field_Existence_Indicator_Shipping' => array(
                                            'shipToFirstName'    => 'Soloman',
                                            'shipToLastName'     => 'Vandy',
                                            'shipToStreet1'      => '123ParkStreet',
                                            'shipToStreet2'      => 'parkstreet',
                                            'shipToCity'         => 'Mumbai',
                                            'shipToState'        => 'Maharashtra',
                                            'shipToPostalCode'   => '400081',
                                            'shipToCountry'      => 'IN',
                                            'shipToPhoneNumber1' => '',
                                            'shipToPhoneNumber2' => '',
                                            'shipToPhoneNumber3' => '',
                                            'shipToMobileNumber' => '9820998209'
                                ),
                                'Field_Existence_Indicator_Payment' => array(
                                            'cardNumber'        => '4111111111111111', // 1. Card Number 
                                            'expMonth'          => '08',               // 2. Expiry Month
                                            'expYear'           => '2020',             // 3. Expiry Year
                                            'CVV'               => '123',              // 4. CVV 
                                            'cardHolderName'    => 'Soloman',          // 5. Card Holder Name
                                            'cardType'          => 'Visa',             // 6. Card Type
                                            'custMobileNumber'  => '9820998209',       // 7. Customer Mobile Number
                                            'paymentID'         => '123456',           // 8. Payment ID
                                            'OTP'               => '123456',           // 9. OTP field
                                            'gatewayID'         => '1026',             // 10.Gateway ID
                                            'cardToken'         => '1202'              // 11.Card Token
                                ),
                                'Field_Existence_Indicator_Merchant'  => array(
                                                    'UDF1'   => '115.121.181.112', // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF2'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF3'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF4'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF5'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF6'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF7'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF8'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF9'   => 'abc',             // This is a 'user-defined field' that can be used to send additional information about the transaction.
                                                    'UDF10'  => 'abc'              // This is a 'user-defined field' that can be used to send additional information about the transaction.                               
                                ),
                                'Field_Existence_Indicator_OtherData'  => array(
                                        'custID'                 => '12345', 
                                        'transactionSource'      => 'IVR',                     
                                        'productInfo'            => 'Book',                         
                                        'isUserLoggedIn'         => 'Y',                             
                                        'itemTotal'              => '500.00, 1000.00',
                                        'itemCategory'           => 'CD, Book',                        
                                        'ignoreValidationResult' => 'FALSE'
                                ),
                                'Field_Existence_Indicator_DCC'   => array(
                                        'DCCReferenceNumber' 	=> '09898787', // DCC Reference Number
                                        'foreignAmount'         => '240.00',  // Foreign Amount
                                        'ForeignCurrency'       => 'USD'  	 // Foreign Currency
                                )
                            );
		 
	   $networkOnlineObject = new NetworkonlieBitmapPaymentIntegration($networkOnlineArray);
       $requestParameter    = $networkOnlineObject->NeoPostData;
	   $this->set('requestParameter',$requestParameter);
	   $this->set('actionUrl',$this->getAction());
        
       
	  }
    
    public function submitPayment()
    {
        
        //nothing to do except return true
        return array('error'=>0, 'transactionReference'=>'');
        
    }
    public function getAction()
    {
        if(Config::get('network_standard.networkStandardMode')==true){
            return "https://uat-NeO.network.ae/direcpay/secure/PaymentTxnServlet";
        } else {
            return "https://NeO.network.ae/direcpay/secure/PaymentTxnServlet";
        }
    }
    public static function decryptData()
    {
        // Read POST data
        // reading posted data directly from $_POST causes serialization
        // issues with array data in POST. Reading raw POST data from input stream instead.
        $raw_post_data = $_POST;
		
		//decrypt the response data
		$MerchantKey = Config::get('network_standard.networkMerchantKey');
		
		$response  =  NetworkonlieBitmapPaymentIntegration::decryptData($raw_post_data['responseParameter'], $MerchantKey,'0123456789abcdef');
		
		$txnstatus = explode("|", $response["Transaction_Status_information"]);
		//check the response if it is success update transaction
		// 
		//$c = Page::getCurrentPage();
		//$cID=$c->getCollectionID();
		if($txnstatus[1]=='SUCCESS') {	 
		     
			 $txnresponse = explode("|", $response["Transaction_Response"]);
			 
			 $txninformation = explode("|", $response["Transaction_related_information"]);
			 
            $order = StoreOrder::getByID(substr($txnresponse['1'],3)); //To remove the first 3 prefix added to the order id
            $order->completeOrder($txninformation['1']);
            $order->updateStatus(StoreOrderStatus::getStartingStatus()->getHandle());
			
			
			// redirect to success page
			
			$redirect = \Redirect::page(Page::getByID('215'));
			$redirect->setTargetUrl('/checkout/complete');
			$redirect->send();
        } elseif (is_error) {
            // log for manual investigation
            // Add business logic here which deals with invalid IPN messages
            //Log::addError("Invalid IPN: $req");
			// redirect to error page
			
			$redirect = \Redirect::page(Page::getByID('596'));
			$redirect->setTargetUrl('/checkout/fail?error='.$txnstatus[3]);
			$redirect->send();
        }
		
		
	  
    }


    public function getPaymentMinimum() {
        return 0.03;
    }


    public function getName()
    {
        return 'Paypal Standard';
    }

    public function isExternal() {
        return true;
    }

	
}