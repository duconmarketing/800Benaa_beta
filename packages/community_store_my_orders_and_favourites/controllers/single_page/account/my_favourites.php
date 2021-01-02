<?php
namespace Concrete\Package\CommunityStoreMyOrdersAndFavourites\Controller\SinglePage\Account;

use \Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Package\CommunityStoreMyOrdersAndFavourites\Src\Favourate;
use User;

class MyFavourites extends AccountPageController {
	
	public function view(){
		
		$user = new User;
				
		$productList  = Favourate::getFavouriteByUID($user->getUserID());	
		
		$this->set('productList',$productList);
				
			
	}	
	
}