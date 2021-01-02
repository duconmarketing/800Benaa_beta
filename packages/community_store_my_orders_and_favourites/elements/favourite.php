<?php defined('C5_EXECUTE') or die("Access Denied.");
use Concrete\Package\CommunityStoreMyOrdersAndFavourites\Src\Favourate; 
$view->requireAsset('javascript','favourate');
$view->requireAsset('css','favourate');
$user = new User;
$userID = $user->getUserID();
if(Favourate::isFavourite($userID,$pID)){
	$class = "remove-from-favourite";
	$text = "Remove from Favourite";
	$title = "Already added as Favourite";	
}else{
	
	$class = "add-to-favourite";
	$text = "add to favourite";
	$title = "add to favourite";
	
}

if($user->isLoggedIn()){
?> 
<a href="javascript:void(0);" data-uID="<?php echo $userID; ?>" title="<?= $title ?>" data-pID="<?php echo $pID ?>" class="fav-icon <?= $class ?>"><i class="fa fa-heart" aria-hidden="true"></i><span class="message"></span></a>
<?php } ?>
