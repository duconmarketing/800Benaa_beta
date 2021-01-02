<?php
namespace Concrete\Package\CommunityStoreMyOrdersAndFavourites\Src;

use Loader;

use Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product;

use Exception;


class Favourate {
	
	public static $_table = 'userFavourateProducts';
	
	public function addToFavourate($pID,$uID){
		
		try{
		
		$db = Loader::db();
		
		$sql_query = 'insert into '.self::$_table.' (uID,pID) values (?,?)';
		
		$db->Execute($sql_query,array($uID,$pID));	
		
		return array('success'=>true);	
		
		}catch(Exception $e){
			
			return array('success'=>false,'message'=>$e->getMessage());	
					
		}
		
	}	
	
	public function removeFromFavorate($pID,$uID){
		
		$db = Loader::db();
		
		if(self::isFavourite($uID,$pID)){
		
		$sql_query = 'delete from '.self::$_table.' where uID = ? and pID=?';
		
		$db->Execute($sql_query,array($uID,$pID));
		
		return array('success'=>true);
			
		}
	
	}
	
	public function isFavourite($uID,$pID){
		
		try{
			
		$db = Loader::db();	
		
		$sql_query = 'select * from '.self::$_table.' where uID = ? and pID = ?';
		
		$result = $db->getAll($sql_query,array($uID,$pID));
		
		if(sizeof($result)>0){
			
			return true;
			
		}else{
			
			return false;
			
		}
		
		}catch(Exception $e){
			
		}		
			
	}
	public function getFavouriteByUID($uID){
		
		try{
			
			$db = Loader::db();	
			$sql_query = 'select pID from '.self::$_table.' where uID = ?';
			$favouritepID = $db->getAll($sql_query,$uID);
			$productList = array();
			foreach($favouritepID as $fpID){
				$product = Product::getByID($fpID['pID']);		
				if($product->isActive()){
					array_push($productList,$product);
				}				
			}
			
			return $productList;
			
		die;
			
		}catch(Exception $e){
			
		}
		
	}
	
}