<?php defined('C5_EXECUTE') or die("Access Denied"); 
$Themepath= $this->getThemePath();
$c = Page::getCurrentPage();
$this->inc('elements/header.php');
?>
  <div class="bread_crumb">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <?php
            $area=new GlobalArea('Breadcrumb');
			$area->display();
			?>
        </div>
      </div>
    </div>
  </div>
  <div class="product_detail">
    <div class="container">
    <?php
    $area=new GlobalArea('Product Detail');
	$area->display();
	?>
    </div>
  </div>
  <?php
$this->inc('elements/footer.php');
 ?>