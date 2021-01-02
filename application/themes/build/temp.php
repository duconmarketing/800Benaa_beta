<?php defined('C5_EXECUTE') or die("Access Denied"); 
$Themepath= $this->getThemePath();
$c = Page::getCurrentPage();
$this->inc('elements/header.php');
?>
  
  
 

 <div class="full">
  
      <div class="page_title">
         <div class="container">
           <div class="row">
               <div class="col-md-12 col-sm-12 col-xs-12">
               
               <?php if($c->getCollectionID()=='596' && $_GET['error']){
				   
				echo '<h3 class="failure">'.$_GET['error'].'</h3><a href="'.BASE_URL.DIR_REL.'">Back to Home</a>';   
				   
				   }?>
               
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