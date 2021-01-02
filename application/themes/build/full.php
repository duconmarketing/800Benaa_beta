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
 

 <div class="full">
  
      <div class="page_title">
         <div class="container">
           <div class="row">
               <div class="col-md-12 col-sm-12 col-xs-12">
                   <?php
                   $area=new GlobalArea('Page Title');
				   $area->display();
				   ?>
               </div>
           </div>
         </div>
     </div>
  
 <div class="content"> 
   <div class="container">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    <?php
    $area=new Area('Main');
	$area->enableGridContainer();
	$area->display($c);
	?>
    </div>
   </div>
 </div>
 </div>
</div>
 
 <?php
$this->inc('elements/footer.php');
 ?> 