<?php
if($c->getAttribute('enable_mega_menu')){ 
$MenuLeft = $c->getCollectionName().'Left Column';
$MenuRight = $c->getCollectionName().'Right Column';
?>
<div class="mmmenu">
<div class="row">
  <div class="col-md-8 col-sm-8">
  <?php 
  $area = new Area($MenuLeft);
  $area->display($c);
  ?>
  </div>
  <div class="col-md-4 col-sm-4">
   <?php 
  $area = new Area($MenuRight);
  $area->display($c);
  ?>
  </div>
  </div>
</div>
<?php } 

