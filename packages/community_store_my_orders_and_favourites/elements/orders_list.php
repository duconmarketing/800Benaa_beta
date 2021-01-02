<?php
defined('C5_EXECUTE') or die("Access Denied.");
$dh = Core::make('helper/date');
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as Price;
use \Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
 ?>
<div class="row hidden-xs hidden-sm form-group">
<div class="col-md-2 text-center"><h5><?= t("Order %s","#")?></h5></div>
<div class="col-md-3 text-center"><h5><?= t("Order Date")?></h5></div>
<div class="col-md-2 text-center"><h5><?= t("Total")?></h5></div>
<div class="col-md-3 text-center"><h5><?= t("Status")?></h5></div>
<div class="col-md-2 text-center"><h5><?= t("View")?></h5></div>
</div>
        
            <?php
                foreach($orderList as $order){

                $cancelled = $order->getCancelled();
                $canstart = '';
                $canend = '';
                if ($cancelled) {
                    $canstart = '<strike>';
                    $canend = '</strike>';
                }
            ?>
                <div class="row hidden-xs hidden-sm form-group">
                    <div class="col-md-2 text-center"><?= $canstart; ?>
                    <a href="<?=URL::to('/account/my_orders/detail',$order->getOrderID())?>"><?= $order->getOrderID()?></a><?= $canend; ?>

                    <?php if ($cancelled) {
                        echo '<span class="text-danger">' . t('Cancelled') .'</span>';
                    }
                    ?>
                    </div>
                   <div class="col-md-3 text-center"><?= $canstart; ?><?= $dh->formatDateTime($order->getOrderDate())?><?= $canend; ?></div>
                   <div class="col-md-2 text-center"><?= $canstart; ?><?=Price::format($order->getTotal())?><?= $canend; ?></div>
                   <div class="col-md-3 text-center"><?=t(ucwords($order->getStatus()))?></div>
                   <div class="col-md-2 text-center"><a href="<?=URL::to('/account/my_orders/detail',$order->getOrderID())?>"><?= t("View")?></a></div>
              </div>
              <div class="row visible-xs visible-sm">
              <div class="col-sm-12 col-xs-12">
              		<div class="panel panel-success">
    					<div class="panel-heading">Order No :<?= $canstart; ?>
                    <a href="<?=URL::to('/account/my_orders/detail',$order->getOrderID())?>"><?= $order->getOrderID()?></a><?= $canend; ?>

                    <?php if ($cancelled) {
                        echo '<span class="text-danger">' . t('Cancelled') .'</span>';
                    }
                    ?>  <a href="<?=URL::to('/account/my_orders/detail',$order->getOrderID())?>" class="pull-right"> View Detail</a> </div>
    				<div class="panel-body">
                    
                        <div class="row form-group">
                            <div class="col-sm-6 col-xs-6">
                            <span><?= t("Order Date")?></span>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                            <?= $canstart; ?><?= $dh->formatDateTime($order->getOrderDate())?><?= $canend; ?>
                            </div>
                        </div>
                        
                        
                        <div class="row form-group">
                            <div class="col-sm-6 col-xs-6">
                            <span><?= t("Total")?></span>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                            <?= $canstart; ?><?=Price::format($order->getTotal())?><?= $canend; ?>
                            </div>
                        </div>
                        
                        
                        <div class="row form-group">
                            <div class="col-sm-6 col-xs-6">
                            <span><?= t("Status")?></span>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                            	<?=t(ucwords($order->getStatus()))?>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                    </div>
  					</div>
              	</div>
              </div>
              
              
            <?php } ?>
