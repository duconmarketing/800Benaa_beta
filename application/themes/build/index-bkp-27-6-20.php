<?php defined('C5_EXECUTE') or die("Access Denied"); 
$Themepath= $this->getThemePath();
$c = Page::getCurrentPage();
$this->inc('elements/header.php');
?>
  <div class="home_slider">
    <div class="container">
      <div class="row">
        <div class="col-md-12"> 
        <div class="hom_slid">
        
        </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <ul class="outerslide">
            <?php
           $area= new Area('Home Slider');
		   $area->display($c);
			?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="top_highlight">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-5 col-xs-12">
          <?php
          $area=new Area('Home highlight1');
		$area->display($c);
		  ?>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
          <?php
          $area=new Area('Home highlight2');
		  $area->display($c);
		  ?>
        </div>
        <div class="col-md-5 col-sm-5 col-xs-12">
          <?php
          $area=new Area('Home Highlight3');
		  $area->display($c);
		  ?>
        </div>
      </div>
    </div>
  </div>
  <div class="top_highlight">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-12">
          <?php
          $area=new Area('Home Hilight4');
		  $area->display();
		  ?>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <?php 
		  $area=new Area('Home highlight5');
		  $area->display($c);
		  ?>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <?php
         $area=new Area('Home Highlight6');
		 $area->display($c);
		  ?>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <?php
          $area=new Area('Home Highlight7');
		  $area->display($c);
		  ?>
        </div>
      </div>
    </div>
  </div>
  <div class="home_products">
    <div class="container">
      <div class="row">
      <?php
     $area=new Area('Home Products');
	  $area->display();
	  ?>
      </div>
    </div>
  </div>
<?php
$this->inc('elements/footer.php');
 ?>