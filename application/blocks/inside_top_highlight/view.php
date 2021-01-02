<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php  
$path='';
if ($image) {
	
	$path=$image->getURL();
	 ?>
   <?php /*?> <img src="<?php  echo $image->getURL(); ?>" alt="<?php  echo $image->getTitle(); ?>"/><?php */?>
	
	
	<?php  } ?>

	
    
    <div class="inside_high" style="background-image:url('<?php echo $image->getURL(); ?>')">
    
    <?php  if (isset($Content) && trim($Content) != "") { ?>
    <?php  echo $Content; ?><?php  } ?>
    
    </div>