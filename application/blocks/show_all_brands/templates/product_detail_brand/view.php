<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php  if($ShowallBrands): ?>
<?php //$grouplists = usort($grouplist, function($a, $b) {  return $a['order'] - $b['order'];}); ?>
<div class="hidden-xs">
<div class="scrollbar-inner">
<ul>
 <?php 
 foreach($sortedGroupList as $group){?>
	 <li>
     <form action="<?php BASE_URL ?>/mama" method="post" id="brand_<?php echo $group['gID']; ?>">
      <input type="hidden" name="gID" value="<?php echo $group['gID']; ?>" />
      <a href="javascript:$('#brand_<?php echo $group['gID']; ?>').submit();" data-select="brand-<?php echo $group['gID']; ?>"><?php echo $group['groupName']; ?></a>
      </form>
      </li>
<?php } ?>
 </ul>
 </div>
 </div>
<?php endif; ?>
<?php $brandArray = array(); ?>
<?php  foreach($sortedGroupList as $group){?>
	
     <form action="<?php BASE_URL ?>/mama" method="post" id="brand_<?php echo  $group['gID']; ?>">
      <input type="hidden" name="gID" value="<?php echo $group['gID']; ?>" />
     </form>
     <?php $brandArray['brand_'.$group['gID']] =   $group['groupName']; ?>
<?php } ?>
<?php 
$form = Loader::helper('form');
echo $form->select('selectedBrand', $brandArray,array('class'=>'visible-xs'));
?>
<script>
$(document).ready(function(){
	$('#selectedBrand').on('change',function(){
		var ID = "#"+$(this).val();
		$(ID).submit();
	});
});
</script>
