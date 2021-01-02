<?php
namespace Concrete\Package\CommunityStoreMyOrdersAndFavourites\Controller\SinglePage\Account;

use \Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer;
use Concrete\Package\CommunityStore\Src\CommunityStore\Order\Order;
use Exception;
use Concrete\Package\CommunityStoreMyOrdersAndFavourites\Src\CustomerIdentifier;

class MyOrders extends AccountPageController {
	
	public function view(){
		$customer = new Customer;
		$uID = $customer->getUserID();
		$orderList =  CustomerIdentifier::getOrdersByuID($uID);
		$this->set('orderList',$orderList);
        
	}	
	
	public function detail($oID = NULL){
		try{
		if($oID){
			$order = Order::getByID($oID);
			$this->set('order',$order);
		}else{
			throw new Exception('Invalid argument passed');	
		}
		}catch(Exception $e){
			$this->error->add(($e->getMessage()));
		}
	}
	
}