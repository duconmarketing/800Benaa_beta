<?php

namespace Application\Controller\SinglePage;

use PageController;
use Config;
use Loader;
use Page;
use Core;
use Session;
use Concrete\Core\Mail\Service as MailService;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Cart\Cart as StoreCart;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCartCalculator;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Discount\DiscountRule as StoreDiscountRule;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Discount\DiscountCode as StoreDiscountCode;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;
use Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductList as StoreProductList;
use Concrete\Package\CommunityStore\Src\CommunityStore\Group\Group as Group;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Cart extends PageController {

    public function view() {
        //die('sdfdsf');
        if (Config::get('community_store.shoppingDisabled') == 'all') {
            $this->redirect("/");
        }

        $codeerror = false;
        $codesuccess = false;

        if ($this->post()) {
            if ($this->post('action') == 'code') {
                $codeerror = false;
                $codesuccess = false;

                if ($this->post('code')) {
                    $codesuccess = StoreDiscountCode::storeCartCode($this->post('code'));
                    $codeerror = !$codesuccess;
                } else {
                    StoreDiscountCode::clearCartCode();
                }
            }

            if ($this->post('action') == 'update') {
                $data = $this->post();

                if (is_array($data['instance'])) {
                    $result = StoreCart::updateMutiple($data);
                    $quantity = 0;
                    foreach ($data['pQty'] as $q) {
                        $quantity += $q;
                    }

                    $added = 0;
                    foreach ($result as $r) {
                        $added += $r['added'];
                    }
                } else {
                    $result = StoreCart::update($data);
                    $added = $result['added'];
                    $quantity = (int) $data['pQty'];
                }

                $returndata = array('success' => true, 'quantity' => $quantity, 'action' => 'update', 'added' => $added);
            }
            $email = $this->post('quote_email');

            if ($this->post('action') == 'quote') {
                //Check Quote mail exist
                //if exist send Quote
                self::sendQuote();
            }

            if ($this->post('action') == 'clear') {
                StoreCart::clear();
                $returndata = array('success' => true, 'action' => 'clear');
            }

            if ($this->post('action') == 'remove') {
                $data = $this->post();
                if (isset($data['instance'])) {
                    StoreCart::remove($data['instance']);
                    $returndata = array('success' => true, 'action' => 'remove');
                }
            }
        }

        $this->set('actiondata', $returndata);
        $this->set('codeerror', $codeerror);
        $this->set('codesuccess', $codesuccess);

        $this->set('cart', StoreCart::getCart());
        $this->set('discounts', StoreCart::getDiscounts());

        $totals = StoreCalculator::getTotals();

        if (StoreCart::isShippable()) {
            $this->set('shippingEnabled', true);

            if (\Session::get('community_store.smID')) {
                $this->set('shippingtotal', $totals['shippingTotal']);
            } else {
                $this->set('shippingtotal', false);
            }
        } else {
            $this->set('shippingEnabled', false);
        }

        $this->set('total', $totals['total']);
        $this->set('subTotal', $totals['subTotal']);
        $this->set('taxes',$totals['taxes']);
        $this->set('taxtotal',$totals['taxTotal']);

        $this->requireAsset('javascript', 'jquery');
        $js = \Concrete\Package\CommunityStore\Controller::returnHeaderJS();
        $this->addFooterItem($js);
        $this->requireAsset('javascript', 'community-store');
        $this->requireAsset('css', 'community-store');

        $discountsWithCodesExist = StoreDiscountRule::discountsWithCodesExist();
        $this->set("discountsWithCodesExist", $discountsWithCodesExist);
    }

    public function add() {
        $data = $this->post();
        $result = StoreCart::add($data);

        $added = $result['added'];

        $error = 0;

        if ($result['error']) {
            $error = 1;
        }

        $product = StoreProduct::getByID($data['pID']);
        $productdata['pAutoCheckout'] = $product->autoCheckout();
        $productdata['pName'] = $product->getName();

        $returndata = array('quantity' => (int) $data['quantity'], 'added' => $added, 'product' => $productdata, 'action' => 'add', 'error' => $error);
        echo json_encode($returndata);
        exit();
    }

    /* public function getQuote($det){



      } */

    public function code() {
        StoreDiscountCode::storeCartCode($this->post('code'));
        exit();
    }

    public function update() {
        $data = $this->post();

        if (is_array($data['instance'])) {
            $result = StoreCart::updateMutiple($data);
            $quantity = 0;
            foreach ($data['pQty'] as $q) {
                $quantity += $q;
            }

            $added = 0;
            foreach ($result as $r) {
                $added += $r['added'];
            }
        } else {
            $result = StoreCart::update($data);
            $added = $result['added'];
            $quantity = (int) $data['pQty'];
        }

        $returndata = array('success' => true, 'quantity' => $quantity, 'action' => 'update', 'added' => $added);

        echo json_encode($returndata);
        exit();
    }

    public function remove() {
        $instanceID = $_POST['instance'];
        StoreCart::remove($instanceID);
        $returndata = array('success' => true, 'action' => 'remove');
        echo json_encode($returndata);
        exit();
    }

    public function clear() {
        StoreCart::clear();
        $returndata = array('success' => true, 'action' => 'clear');
        echo json_encode($returndata);
        exit();
    }

    public function sendQuote() {

        $cart = StoreCart::getCart();

        Loader::Element('print_spa/download_pdf', array(
            'cart' => $cart, 'postValues' => $_POST));
    }

    public function printquote() {

        $arr_detail = array();
        $arr_state = array("AD"=>"Abu Dhabi","AA"=>"Al Ain","AW"=>"Al Wagan/ Eastern Boundaries","RW"=>"Ruwais/ Western Boundaries","AJ"=>"Ajman","DXB"=>"Dubai","HT"=>"Hatta","FJ"=>"Fujairah","RK"=>"RAK","SHJ"=>"Sharjah","ADD"=>"Al Dhaid","AMD"=>"Al Madam","MH"=>"Maleha","SY"=>"Seyooh","UQ"=>"UAQ");
        $cart = StoreCart::getCart();
        $cartcal = StoreCartCalculator::getTotals();

        $arr_detail['subTotal'] = $cartcal['subTotal'];
        $arr_detail['shippingTotal'] = $cartcal['shippingTotal'];
        $arr_detail['taxTotal'] = $cartcal['taxTotal'];
        $arr_detail['total'] = $cartcal['total'];
        $arr_detail['fname'] = Session::get('billing_first_name');
        $arr_detail['lname'] = Session::get('billing_last_name');
        $arr_detail['billing_phone'] = Session::get('billing_phone');
        $arr_detail['email'] = Session::get('email');

        $arr[] = Session::get('billing_address');
        //print_r($arr);
        $arr_detail['address1'] = $arr[0]['address1'];
        $arr_detail['state_province'] = $arr_state[$arr[0]['state_province']];
        $arr_detail['postal_code'] = $arr[0]['postal_code'];
        $arr_detail['country'] = 'United Arab Emirates';
        $arr_detail['comp_name'] = $_POST['comp1'];
        $arr_detail['comp_dev_per'] = $_POST['comp2'];
        $arr_detail['comp_dev_no'] = $_POST['comp3'];

        Loader::Element('print_spa/download_pdf', array(
            'cart' => $cart, 'extdetail' => $arr_detail));
    }


    public function ajaxSearch() {
        $data = $this->post();
        $key = $data['key'];

        $products = new StoreProductList();
        $products->setSortBy($this->sortOrder);

        if ($key != '') {
            $products->setSearch($key);
        }

        $products->setItemsPerPage(12);
        $products->setFeaturedOnly($this->showFeatured);
        $products->setSaleOnly($this->showSale);
        $products->setShowOutOfStock($this->showOutOfStock);
        $products->setGroupMatchAny($this->groupMatchAny);
        $paginator = $products->getPagination();
        $pagination = $paginator->renderDefaultView();
        $products = $paginator->getCurrentPageResults();

        foreach ($products as $product) {
            $product->setInitialVariation();
        }


        $prodSuggestion = array();
        $sortedGroupList = array();
        $categories = array();

        if(!empty($products)){
            foreach ($products as $product) {
                $prodSuggestion[] = implode(' ',array_slice(explode(' ', $product->getName()), 0, 3));
                $pIDs[] = $product->getID();
            }
            $productid=implode(',',$pIDs);
            $db = Loader::db();
            $getproductgroup=$db->GetAll("SELECT gID FROM CommunityStoreProductGroups WHERE pID IN(".$productid.")");
            if(!empty($getproductgroup)){
                foreach ($getproductgroup as $getproductgroupval) {
                    $progroupval[]=$getproductgroupval['gID'];
                }
                if(!empty($progroupval)){
                    $productgroupid=implode(',',$progroupval);
                }
                if($productgroupid){
                    $checkgroup='WHERE gID IN('.$productgroupid.')';
                    //$sortedGroupList = Loader::db()->getAll("SELECT * FROM CommunityStoreGroups ORDER BY groupName");
                    $sortedGroupList = Loader::db()->getAll("SELECT * FROM CommunityStoreGroups ".$checkgroup." ORDER BY groupName LIMIT 5");
                }
            }

            $list = new \Concrete\Core\Page\PageList();
            $list->filterByPageTypeHandle('page');
            $list->filterByKeywords($key);
            $list->filterByExcludeNav(FALSE);
            $list->filterByPath('/product');
            $list->filterByPageTemplate(\PageTemplate::getByHandle('product_list'));
            $catPag = $list->getPagination();
            $catPag->setMaxPerPage(10);
            $categories = $catPag->getCurrentPageResults();
        }

        $prodSuggestion = array_unique($prodSuggestion);
        $html = $this->createSearchHTML($products, $categories, array_slice($prodSuggestion, -5), $sortedGroupList);

        $returndata = array('html' => $html);
        echo json_encode($returndata);

//        View::element("ajax_search", array('products' => $products, 'categories' => $categories, 'suggestion' => array_slice($prodSuggestion, -5), 'sortedGroupList' => $sortedGroupList));
        exit();
    }

    public function createSearchHTML($products, $categories, $suggestion, $sortedGroupList) {
        $html = '';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-3 hidden-xs hidden-sm" style="position: -webkit-sticky;position: sticky;top: 0;">';
        $html .= '<p class="search-result text-uppercase">CATEGORIES</p>';
        foreach($categories as $cat){
            $html .= '<div class="row" style="text-align: left;padding-left: 25px; overflow:hidden;">';
            $html .= '<a href="'. Core::make('helper/navigation')->getLinkToCollection($cat) .'">' . strtoupper(Loader::helper('text')->entities($cat->getCollectionName())) . '</a>';
            $html .= '</div>';
        }
        if(count($categories) <= 0){
            $html .= '<div class="row"> No Categories </div>';
        }

        $html .= '<p class="search-result text-uppercase" style="margin-top: 1.5em;">Brands</p>';
        foreach($sortedGroupList as $brand){
            $html .= '<div class="row" style="text-align: left;padding-left: 25px; overflow:hidden;">';
//            $html .= '<a href="" onclick="suggestSearch(\''. str_replace("'", "\'", $brand['groupName']) .'\');">' . strtoupper($brand['groupName']) . '</a>';
            $html .= '<a style="cursor: default;text-decoration: none;" href="#" onclick="">' . strtoupper($brand['groupName']) . '</a>';
            $html .= '</div>';
        }
        if(count($sortedGroupList) <= 0){
            $html .= '<div class="row"> No Brands </div>';
        }

        $html .= '<p class="search-result text-uppercase" style="margin-top: 1.5em;">SUGGESTIONS</p>';
        foreach($suggestion as $cat){
            $html .= '<div class="row" style="text-align: left;padding-left: 25px; overflow:hidden;">';
//            $html .= '<a href="'. Core::make('helper/navigation')->getLinkToCollection($cat) .'">' . strtoupper(Loader::helper('text')->entities($cat->getCollectionName())) . '</a>';
            $html .= '<a class="p-sugstn" href="" onclick="suggestSearch(\''. str_replace(" ", "_", $cat) .'\');">' . $cat . '</a>';
            $html .= '</div>';
        }
        if(count($suggestion) <= 0){
            $html .= '<div class="row"> No Suggestions </div>';
        }
        $html .= '</div>';

        $html .= '<div class="col-md-9">';
        if ($products) {
            foreach ($products as $product) {
                $imgObj = $product->getImageObj();
                $thumb = Core::make('helper/image')->getThumbnail($imgObj, 150, 140, true);
                $html .= '<div class="store-product-list-item_ajx col-md-3 col-sm-3 col-xs-6">';
                $html .= '<div class="product_item_ajx pro hvr-float">';
                $html .= '<a href="' . ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '' ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) . '">';
                $html .= '<img src="' . $thumb->src . '" class="img-responsive" alt="' . $product->getName() . '"/>';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '<a class="a_title_ajx" href="' . ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '' ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) . '">';
                $html .= $product->getName();
                $html .= '</a>';
                $html .= '<h6>';
                $salePrice = $product->getSalePrice();
                if (isset($salePrice) && $salePrice != "") {
                    $html .= $product->getFormattedSalePrice() . '<span style="font-size: 14px;color: #000;text-transform: none;font-weight:normal;"> Ex VAT</span><br>';
//                    $html .= '<span class="store-original-price" style="color: #999;font-size: 13px;text-decoration: line-through;">' . $product->getFormattedOriginalPrice() . '</span>';
                } else {
                    $html .= $product->getFormattedPrice() . '<span style="font-size: 14px;color: #000;text-transform: none;font-weight:normal;"> Ex VAT</span>';
                }
                $html .= '</h6>';

                $html .= '</div>';
            }
            $html .= '<div class="row mb-3"><div class="col-md-12"><button class="btn btn-primary" style="background-color: #ec7c05;border-color: #ec7c05;margin: 10px;" type="submit">View All results...</button></div></div>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html .= '<h4 style="padding-top: 2%;">No products were found !!! </h4></div></div>';
        }
        return $html;
    }

}
