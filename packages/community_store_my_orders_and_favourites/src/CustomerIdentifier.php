<?php
namespace Concrete\Package\CommunityStoreMyOrdersAndFavourites\Src;

use Loader;

use User;

use Concrete\Package\CommunityStore\Src\CommunityStore\Order\Order;


class CustomerIdentifier {
	
	public static $_table = "customerIdentifiers";
	
	public function addCustomer($order){
		
		$db = Loader::db();
		
		$user  = new User;
		
		if(is_object($user)){
		
		$uID = $user->getUserID();
				
		$oID = $order->getOrderID();
		
		$sql_query = "insert into ".self::$_table." (uID,oID) values (?,?)";
		
		$db->Execute($sql_query,array($uID,$oID)); 	
		
		}
		
	}
	public function getOrdersByuID($uID){
		
		$db = Loader::db();
		
		$query = 'select oID from '.self::$_table.' where uID = ? order by cuID DESC';
		
		$ordersIDs = $db->getAll($query,array($uID));
		
		$orderList = array();
		
		foreach($ordersIDs as $oID){
			$order = Order::getByID($oID['oID']);
			if(is_object($order)){
				array_push($orderList,$order);		
			}
						
		}
		return $orderList;
		
	}
	
	
}