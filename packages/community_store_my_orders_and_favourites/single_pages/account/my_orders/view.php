<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
 <div class="row my-orders">
 <div class="col-xs-12 col-sm-3 col-md-3 left-col">
    <?php  Loader::packageElement('sidebar','community_store_my_orders_and_favourites'); ?>
 </div>
 <div class="col-xs-12 col-sm-9 col-md-9">
 <h3 class="page-header"><?php echo t('My Orders')?></h3>
<?php if($controller->getTask() == "view"){?>

	<?php if(sizeof($orderList)>0){ 
	
				Loader::packageElement('orders_list','community_store_my_orders_and_favourites',array("orderList"=>$orderList));
				
	} ?>


<?php }else if($controller->getTask() == "detail"){ ?>
	<?php
	 if(is_object($order)){
		 
		 		Loader::packageElement('order_detail','community_store_my_orders_and_favourites',array("order"=>$order));	
				 
		}	
	?>
	
<?php } ?>
</div>
</div>