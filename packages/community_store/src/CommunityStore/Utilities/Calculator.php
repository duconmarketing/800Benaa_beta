<?php
namespace Concrete\Package\CommunityStore\Src\CommunityStore\Utilities;

use Concrete\Package\CommunityStore\Src\CommunityStore\Cart\Cart as StoreCart;
use Concrete\Package\CommunityStore\Src\CommunityStore\Tax\Tax as StoreTax;
use Concrete\Package\CommunityStore\Src\CommunityStore\Shipping\Method\ShippingMethod as StoreShippingMethod;
use Config;
use Session;

class Calculator
{
    public static function getSubTotal()
    {
        $cart = StoreCart::getCart();
		
		//print_r($cart);die;
        $subtotal = 0;
        if ($cart) {
            foreach ($cart as $cartItem) {
                $qty = $cartItem['product']['qty'];
                $product = $cartItem['product']['object'];

                if (is_object($product)) {

                    if (isset($cartItem['product']['customerPrice']) && $cartItem['product']['customerPrice'] > 0) {
                        $price = $cartItem['product']['customerPrice'];
                    } else {
                        $price = $product->getActivePrice();
                    }
                    $temp_attr = trim($product->getSKU());
                    $temp_attr = substr($temp_attr, 0, 5);
                    if(($temp_attr == '8BN03') && ($qty > 1)){
                        $temp_ship = $price * 0.12;
                        $temp_price = $price - $temp_ship;
                        $productSubTotal = ($temp_price * ($qty - 1)) + $price;
                    } else{
                        $productSubTotal = $price * $qty;
                    }
                    //$productSubTotal = $price * $qty;
                    $subtotal = $subtotal + $productSubTotal;
                }
            }
        }

        return max($subtotal, 0);
    }
    public static function getShippingTotal($smID = null)
    {
        $cart = StoreCart::getCart();
        if (empty($cart)) {
            return false;
        }

        $existingShippingMethodID = Session::get('community_store.smID');
        if ($smID) {
            $shippingMethod = StoreShippingMethod::getByID($smID);
            Session::set('community_store.smID', $smID);
        } elseif ($existingShippingMethodID) {
            $shippingMethod = StoreShippingMethod::getByID($existingShippingMethodID);
        }

        if (is_object($shippingMethod) && $shippingMethod->getCurrentOffer()) {
            
            $cart = StoreCart::getCart();
            $stmproduct = false;
            if ($cart) {
                foreach ($cart as $cartItem) {
                    $product = $cartItem['product']['object'];
                    if (is_object($product)) {                        
                        $temp_attr = trim($product->getSKU());
                        $temp_attr = substr($temp_attr, 0, 3);
                        if($temp_attr == 'STM'){
                            $stmproduct = true;
                            break;
                        }
                    }
                }
            }

            if($stmproduct){
                $shippingTotal = $shippingMethod->getCurrentOffer()->getRate();
            }else{
                $shippingTotal = 30;
            }
            
        } else {
            $shippingTotal = 0;
        }

        if(Session::get('no_shipping')){   //added for 'collect or delivery' option 25/2/2021
            $shippingTotal = 0;
        }

        return $shippingTotal;
    }
    public static function getTaxTotals()
    {
        return StoreTax::getTaxes();
    }

    public static function getGrandTotal()
    {
        $totals = self::getTotals();
        return $totals['total'];
    }

        // returns an array of formatted cart totals
    public static function getTotals()
    {
        $subTotal = self::getSubTotal();

        $taxes = StoreTax::getTaxes();

        $shippingTotal = self::getShippingTotal();
        $discounts = StoreCart::getDiscounts();

        $addedTaxTotal = 0;
        $includedTaxTotal = 0;
        $addedShippingTaxTotal = 0;
        $includedShippingTaxTotal = 0;

        $taxCalc = Config::get('community_store.calculation');

        if ($taxes) {
            foreach ($taxes as $tax) {
                if ($taxCalc != 'extract') {
                    $addedTaxTotal += $tax['producttaxamount'];
                    $addedShippingTaxTotal += $tax['shippingtaxamount'];
                } else {
                    $includedTaxTotal += $tax['producttaxamount'];
                    $includedShippingTaxTotal += $tax['shippingtaxamount'];
                }
            }
        }

        $adjustedSubtotal = $subTotal;
        $adjustedShippingTotal = $shippingTotal;
		$discountRatio = 1;
        $discountShippingRatio = 1;

        $formattedtaxes = array();
        if (!empty($discounts)) {
            foreach ($discounts as $discount) {
                if ($discount->getDeductFrom() == 'subtotal') {
                    if ($discount->getDeductType() == 'value') {
                        $adjustedSubtotal -= $discount->getValue();
                    }

                    if ($discount->getDeductType() == 'percentage') {
                        $adjustedSubtotal -= ($discount->getPercentage() / 100 * $adjustedSubtotal);
                    }
                } elseif($discount->getDeductFrom() == 'shipping') {

                    if ($discount->getDeductType() == 'value') {
                        $adjustedShippingTotal -= $discount->getValue();
                    }

                    if ($discount->getDeductType() == 'percentage') {
                        $adjustedShippingTotal -= ($discount->getPercentage() / 100 * $adjustedShippingTotal);
                    }
                }
            }



            if ($subTotal > 0) {
                $discountRatio = $adjustedSubtotal / $subTotal;
            }

            if ($shippingTotal > 0) {
                $discountShippingRatio = $adjustedShippingTotal / $shippingTotal;
            }

            $addedTaxTotal  = $discountRatio * $addedTaxTotal;
            $addedShippingTaxTotal  =  $discountShippingRatio * $addedShippingTaxTotal;

            $includedTaxTotal  = $discountRatio * $includedTaxTotal ;
            $includedShippingTaxTotal  =  $discountShippingRatio * $includedShippingTaxTotal;


            foreach($taxes as $tax) {
                $tax['taxamount'] =  ($discountRatio * $tax['producttaxamount']) + ($discountShippingRatio * $tax['shippingtaxamount']);
                $formattedtaxes[] = $tax;
            }

            $taxes = $formattedtaxes;
        }

        $adjustedSubtotal = max($adjustedSubtotal, 0);
        $adjustedShippingTotal = max($adjustedShippingTotal, 0);

        $addedTaxTotal = max($addedTaxTotal, 0);
        $addedShippingTaxTotal = max($addedShippingTaxTotal, 0);

        $includedTaxTotal = max($includedTaxTotal, 0);
        $includedShippingTaxTotal = max($includedShippingTaxTotal, 0);

        $total = $adjustedSubtotal + $adjustedShippingTotal + $addedTaxTotal + $addedShippingTaxTotal;
        $totalTax = $addedTaxTotal + $addedShippingTaxTotal + $includedTaxTotal + $includedShippingTaxTotal;

        return array('discountRatio'=>$discountRatio,'subTotal' => $adjustedSubtotal, 'taxes' => $taxes, 'taxTotal' => $totalTax, 'addedTaxTotal'=>$addedTaxTotal + $addedShippingTaxTotal,'includeTaxTotal'=>$includedTaxTotal + $includedShippingTaxTotal, 'shippingTotal' => $adjustedShippingTotal, 'total' => $total);
    }
}
