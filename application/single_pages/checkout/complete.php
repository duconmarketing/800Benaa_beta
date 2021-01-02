<div class="store-order-complete-page">
<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<h2><?= t("Order #%s has been placed",$order->getOrderID())?></h2>

<p><?= t("Thank you for your order. A receipt will be emailed to you shortly. Please make sure to keep the Order Number for future reference!")?></p>
<a href="<?php echo BASE_URL.DIR_REL ?>">Back to Home</a>
<!--<a href="/account/my_orders">See order details</a>-->
<?php 
    $downloads = array();
    $orderItems = $order->getOrderItems();
    foreach($orderItems as $item){
        $pObj = $item->getProductObject();
        if(is_object($pObj)){
            if($pObj->hasDigitalDownload()){
                $fileObjs = $pObj->getDownloadFileObjects();
                $downloads[$item->getProductName()] = $fileObjs[0];
            }
        }
    }
    if(count($downloads) > 0){?>
        <h3><?= t("Your Downloads")?></h3>
        <ul class="order-downloads">
        <?php
        foreach($downloads as $name=>$file){
            echo '<li><a href="'.$file->getForceDownloadURL().'">'.$name.'</a></li>';
        }?>
        </ul>
    <?php }
    
    
/*
 *  The Order object is loaded should we wish to place receipt details here.
 *  Example:
 *  echo $order->getTaxTotal()
 *  echo $order->getShippingTotal()
 *  echo $order->getTotal()
 *  
 *  $orderItems = $order->getOrderItems();
 *  foreach($orderItems as $item){
 *      echo $item->getProductName();
 *      echo $item->getQty();
 *      echo $item->getPricePaid();
 *  }
 * 
 */
?>
</div>