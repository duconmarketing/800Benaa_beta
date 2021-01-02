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
<div class="left_sidebar">
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
  <div class="products">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12 hidden-xs">
          <div class="sidebar">
            <?php
            $area=new GlobalArea('Side Nav');
			$area->display();
			?>
           <?php /*?> <?php 
  $nh = Core::make('helper/navigation');
  $cobj = $nh->getTrailToCollection($c);
 
  $rcobj = array_reverse($cobj);
  //print_r($rcobj);die;
    if(is_object($rcobj[1])) {
		?>
        <div class="back_links">
        <?php
      $pID  = $rcobj[1]->getCollectionID();
      $page = Page::getByID($pID);  
    if($page->getCollectionName()=='Product'){
		
		
		echo '<ul class="sec_nav">';
		foreach($cobj as $parent){
		$url=$nh->getLinkToCollection($parent);	
		
			 ?>
            <li><a href="<?php echo $url?>" title="<?php echo $parent->getCollectionName();?>"><?php echo $parent->getCollectionName();?></a></li>
            <?php 
			
			}
		echo '</ul>';	
		
		}
	  ?>
      </div>
      <?php 
	  
    }
?><?php */?>
          </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <?php
        $area=new Area('Page Description');
		$area->display($c);
		?>
        <h3>IN THIS CATEGORY</h3>
        </div>
        </div>
          <div class="row cat_list">
          
            <?php
           $area=new GlobalArea('Categories');
		   $area->display();
		   ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$this->inc('elements/footer.php');
 ?>
