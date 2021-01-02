<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<div class="row my-favourites">
 <div class="col-xs-12 col-sm-3 col-md-3 left-col">
    <?php  Loader::packageElement('sidebar','community_store_my_orders_and_favourites'); ?>
 </div>
 <div class="col-xs-12 col-sm-9 col-md-9">
  <h3 class="page-header"><?php echo t('My Favourites')?></h3>
<?php

if(sizeof($productList)>0){
	
	
	Loader::packageElement('favourite_products','community_store_my_orders_and_favourites',array("products"=>$productList));
	
} ?>
</div>
</div>