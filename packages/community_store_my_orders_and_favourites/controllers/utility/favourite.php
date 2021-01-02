<?php
namespace Concrete\Package\CommunityStoreMyOrdersAndFavourites\Controller\Utility;

use Concrete\Core\Controller\Controller;
use Concrete\Package\CommunityStoreMyOrdersAndFavourites\Src\Favourate;

class Favourite extends Controller {
	
	public function addToFavorate(){
		$data = $this->post();
		$pID = $data['pID'];
		$uID = $data['uID'];
		$response = Favourate::addToFavourate($pID,$uID);
		echo json_encode($response);
		exit();
	}
	
	public function removeFromFavorate(){
		$data = $this->post();
		$pID = $data['pID'];
		$uID = $data['uID'];
		$response = Favourate::removeFromFavorate($pID,$uID);
		echo json_encode($response);
		exit();
	}
			
	
}