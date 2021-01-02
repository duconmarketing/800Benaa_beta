<?php
namespace Concrete\Package\CommunityStoreMyOrdersAndFavourites;

use Package;
use Page;
use SinglePage;
use Route;
use Events;
use Concrete\Package\CommunityStoreMyOrdersAndFavourites\Src\CustomerIdentifier;

class controller extends package {
	
	protected $pkgHandle = 'community_store_my_orders_and_favourites';
    protected $appVersionRequired = '5.7.5';
    protected $pkgVersion = '0.0.0.3';
	
	public function getPackageName(){
	
		return t("My Orders and Favourites");	
	
	 }
	 
	 public function getPackageDescription(){
		 
		 return t("List Order informations in user account.");		 
		 
    }
	
	public function install(){
		$pkg = parent::install();
		self::installSinglepages($pkg);
	}
	
	public function uninstall(){
		parent::uninstall();
	}
	
	public function installSinglepages($pkg){
		self::installpage('/account/my_orders',$pkg);
		//self::installpage('/account/my_favourites',$pkg);
		
	}
	
	public function installpage($path,$pkg){
		 $page = Page::getByPath($path);
        if (!is_object($page) || $page->isError()) {
            SinglePage::add($path, $pkg);
		 }
		 return true;
    }	
	
	public function on_start(){
		Route::register('/addtofavourate','Concrete\Package\CommunityStoreMyOrdersAndFavourites\Controller\Utility\Favourite::addToFavorate');
		Route::register('/removefromfavourate','Concrete\Package\CommunityStoreMyOrdersAndFavourites\Controller\Utility\Favourite::removeFromFavorate');
		
		Events::addListener('on_community_store_order', function($event) {
			$order = $event->getOrder();
			CustomerIdentifier::addCustomer($order);
		});

		
		$al = \Concrete\Core\Asset\AssetList::getInstance();
		$al->register('javascript','favourate','js/favourate.js',array(),'community_store_my_orders_and_favourites');
		$al->register('css','favourate','css/favourate.css',array(),'community_store_my_orders_and_favourites');
	}
	
}