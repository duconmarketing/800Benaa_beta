<?php
defined('C5_EXECUTE') or die("Access Denied.");
$dh = Core::make('helper/date');
$form = Core::make("helper/form");
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as Price;
use \Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
?>


<div class="ccm-dashboard-header-buttons cust">
        <form action="<?=URL::to('/dashboard/store/orders/details/sslip')?>" method="post" target="_blank">
            <input type="hidden" name="oID" value="<?= $order->getOrderID()?>">
            <button class="btn btn-primary"><?= t("Print Order Slip")?></button>
        </form>
    </div>
<div class="row">
    <div class="col-sm-8">
        <p><strong><?= t('Order Date'); ?>:</strong> <?= $dh->formatDateTime($order->getOrderDate())?></p>
     </div>
    <div class="col-sm-4">
    <?php
    if ($order->getStatus()=='Incomplete') {
        echo '<p class="alert alert-danger text-center"><strong>' . ucwords($order->getStatus()) . '</strong></p>';
    } elseif ($order->getStatus()=='Pending') {
            echo '<p class="alert alert-warning text-center"><strong>' .ucwords($order->getStatus()) . '</strong></p>';
    } elseif ($order->getStatus()=='Complete') {
            echo '<p class="alert alert-success text-center"><strong>' . ucwords($order->getStatus()) . '</strong></p>';
    } elseif ($order->getStatus()=='Shipped') {
            echo '<p class="alert alert-info text-center"><strong>' . ucwords($order->getStatus()) . '</strong></p>';
	} else {
           echo '<p class="alert alert-info text-center"><strong>' . ucwords($order->getStatus()) . '</strong></p>';
    }
    
    ?>
    </div>
</div>

    <fieldset>
    <legend><?= t("Customer Details")?></legend>

    <div class="row">
        <div class="col-sm-6">
            <h4 class="form-group"><?= t("Billing Address")?></h4>
            <p>
                <?= $order->getAttribute("billing_first_name"). " " . $order->getAttribute("billing_last_name")?><br>
                <?php $billingaddress = $order->getAttributeValueObject(StoreOrderKey::getByHandle('billing_address'));
                if ($billingaddress) {
                    echo $billingaddress->getValue('displaySanitized', 'display');
                }
                ?>
            </p>
        </div>
        <?php if ($order->isShippable()) { ?>
            <div class="col-sm-6">
                <?php if ($order->getAttribute("shipping_address")->address1) { ?>
                    <h4 class="form-group"><?= t("Shipping Address")?></h4>
                    <p>
                        <?= $order->getAttribute("shipping_first_name"). " " . $order->getAttribute("shipping_last_name")?><br>
                        <?php $shippingaddress = $order->getAttributeValueObject(StoreOrderKey::getByHandle('shipping_address'));
						if ($shippingaddress) {
                            echo $shippingaddress->getValue('displaySanitized', 'display');
                        }
                        ?>
                    </p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    </fieldset>

    <fieldset>
    <legend><?= t("Order Items")?></legend>
     
            <div class="row form-group hidden-xs">
                <div class="col-md-3 col-sm-3"><strong><?= t("Product Name")?></strong></div>
                <div class="col-md-3 col-sm-3"><strong><?= t("Product Options")?></strong></div>
                <div class="col-md-2 col-sm-2"><strong><?= t("Price")?></strong></div>
                <div class="col-md-2 col-sm-2"><strong><?= t("Quantity")?></strong></div>
                <div class="col-md-2 col-sm-2"><strong><?= t("Subtotal")?></strong></div>
            </div>
       
    
            <?php
                $items = $order->getOrderItems();

                if($items){
                    foreach($items as $item){
              ?>
                <div class="row form-group hidden-xs">
                    <div class="col-md-3 col-sm-3"><?= $item->getProductName()?>
                    <?php if ($sku = $item->getSKU()) {
                    echo '(' .  $sku . ')';
                     } ?>
                    </div>
                     <div class="col-md-3 col-sm-3">
                        <?php
                            $options = $item->getProductOptions();
                            if($options){
                                echo "<ul class='list-unstyled'>";
                                foreach($options as $option){
                                    echo "<li>";
                                    echo "<strong>".$option['oioKey'].": </strong>";
                                    echo $option['oioValue'];
                                    echo "</li>";
                                }
                                echo "</ul>";
                            }
                        ?>
                    </div>
                    <div class="col-md-2 col-sm-2"><?=Price::format($item->getPricePaid())?></div>
                    <div class="col-md-2 col-sm-2"><?= $item->getQty()?></div>
                    <div class="col-md-2 col-sm-2"><?=Price::format($item->getSubTotal())?></div>
                </div>
                <div class="row visible-xs">
                  	<div class="col-sm-12 col-xs-12">
                    <div class="panel panel-success">
    					<div class="panel-heading">
                        <?= $item->getProductName()?>
                          <?php if ($sku = $item->getSKU()) {
                    echo '(' .  $sku . ')';
                     } ?>
                        </div>
    				<div class="panel-body">
                    
                        <div class="row form-group">
                            <div class="col-sm-6 col-xs-6">
                            <span><?= t("Product Options")?></span>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                            <?php
                            $options = $item->getProductOptions();
                            if($options){
                                echo "<ul class='list-unstyled'>";
                                foreach($options as $option){
                                    echo "<li>";
                                    echo "<strong>".$option['oioKey'].": </strong>";
                                    echo $option['oioValue'];
                                    echo "</li>";
                                }
                                echo "</ul>";
                            }
                        ?>
                            </div>
                        </div>
                        
                        
                        <div class="row form-group">
                            <div class="col-sm-6 col-xs-6">
                            <span><?= t("Price")?></span>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                           <?=Price::format($item->getPricePaid())?>
                            </div>
                        </div>
                        
                        
                        <div class="row form-group">
                            <div class="col-sm-6 col-xs-6">
                            <span><?= t("Quantity")?></span>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                            	<?= $item->getQty()?>
                            </div>
                        </div>
                         <div class="row form-group">
                            <div class="col-sm-6 col-xs-6">
                            <span><?= t("Subtotal")?></span>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                            	<?=Price::format($item->getSubTotal())?>
                            </div>
                        </div>
                        
                             
                    </div>
  					</div>                
               		 </div>
                </div>
                
              <?php
                    }
                }
            ?>
        
        <div class="row form-group hidden-xs">
            <div class="col-md-3 col-md-offset-6 col-sm-3 col-sm-offset-6 text-right"><strong><?= t("Items Subtotal")?>:</strong></div>
            <div class="col-md-3 col-sm-3 text-center"><?=Price::format($order->getSubTotal())?></div>
        </div>
         <div class="row form-group visible-xs">
            <div class="col-md-3 col-md-offset-6 col-sm-3 col-sm-offset-6 col-xs-6"><strong><?= t("Items Subtotal")?>:</strong> <?=Price::format($order->getSubTotal())?></div>
          
        </div>
      


    <?php $applieddiscounts = $order->getAppliedDiscounts();

    if (!empty($applieddiscounts)) { ?>
        <h4><?= t("Discounts Applied")?></h4>
        <hr />
        <table class="table table-striped">
            <thead>
            <tr>
                <th><strong><?= t("Name")?></strong></th>
                <th><?= t("Displayed")?></th>
                <th><?= t("Deducted From")?></th>
                <th><?= t("Amount")?></th>
                <th><?= t("Triggered")?></th>
            </tr>

            </thead>
            <tbody>
            <?php foreach($applieddiscounts as $discount) { ?>
                <tr>
                    <td><?= h($discount['odName']); ?></td>
                    <td><?= h($discount['odDisplay']); ?></td>
                    <td><?= t(ucwords($discount['odDeductFrom'])); ?></td>
                    <td><?= ($discount['odValue'] > 0 ? Price::format($discount['odValue']) : $discount['odPercentage'] . '%' ); ?></td>
                    <td><?= ($discount['odCode'] ? t('by code'). ' <em>' .$discount['odCode'] .'</em>': t('Automatically') ); ?></td>
                </tr>
            <?php } ?>

            </tbody>
        </table>

    <?php } ?>

    <?php if ($order->isShippable()) { ?>
    <p>
        <strong><?= t("Shipping")?>: </strong><?=Price::format($order->getShippingTotal())?>
    </p>
    <?php } ?>

    <?php $taxes = $order->getTaxes();

    if (!empty($taxes)) { ?>
        <p>
            <?php foreach ($order->getTaxes() as $tax) { ?>
                <strong><?= $tax['label'] ?>
                    :</strong> <?= Price::format($tax['amount'] ? $tax['amount'] : $tax['amountIncluded']) ?><br>
            <?php } ?>
        </p>
    <?php } ?>

    <p>
        <strong><?= t("Grand Total") ?>: </strong><?= Price::format($order->getTotal()) ?>
    </p>
   
    <?php if ($order->isShippable()) { ?>
        <br /><p>
            <strong><?= t("Shipping Name") ?>: </strong><?= $order->getShippingMethodName() ?>
        </p>

        <?php
        $shippingInstructions = $order->getShippingInstructions();
        if ($shippingInstructions) { ?>
            <p>
                <strong><?= t("Delivery Instructions") ?>: </strong><?= $shippingInstructions ?>
            </p>
        <?php } ?>

    <?php } ?>

     <div class="row">
        <?php if (!empty($orderChoicesAttList)) { ?>
            <div class="col-sm-12">
                <h4><?= t("Other Choices")?></h4>
                <?php foreach ($orderChoicesAttList as $ak) {
                    $attValue = $order->getAttributeValueObject(StoreOrderKey::getByHandle($ak->getAttributeKeyHandle()));
                    if ($attValue) {  ?>
                    <label><?= $ak->getAttributeKeyDisplayName()?></label>
                    <p><?= str_replace("\r\n", "<br>", $attValue->getValue('displaySanitized', 'display')); ?></p>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>


    </fieldset>
    









