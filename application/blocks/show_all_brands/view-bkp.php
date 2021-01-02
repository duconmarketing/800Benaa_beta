<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php  if($ShowallBrands): ?>
<?php //$grouplists = usort($grouplist, function($a, $b) {  return $a['order'] - $b['order'];}); ?>
<?php if(!empty($sortedGroupList)){ ?>
<h2>Brands</h2>
<?php } ?>
<div class="brat hidden-xs">

<ul>
 <?php 
 if(!empty($sortedGroupList)){
 foreach($sortedGroupList as $group){ ?>
	 <li> <a href="javascript:addFilter('brand','<?php echo $group['gID']; ?>')" data-select="brand-<?php echo $group['gID']; ?>"><?php echo $group['groupName']; ?></a></li>
<?php }} ?>
 </ul>

</div>
 <?php 
// if(!empty($sortedGroupList)){
// $brandArray = array(); 
// foreach($sortedGroupList as $group){?>
	<?php //$brandArray[$group['gID']] = $group['groupName']; ?>
<?php //}} ?>
<?php
$form = Loader::helper('form');
//echo $form->selectMultiple('selectedBrand', $brandArray,$selectedvalue,array('class'=>'select2-select visible-xs'));
?>
<script>

$(document).ready(function(){
	//$('.select2-select').select2({
	//placeholder: "Select Brand"
	//})<?php //if($_POST['gID']){ ?>.val(["<?php // echo $_POST['gID']; ?>"]).trigger("change")<?php //} ?>.on("select2-selecting",function(e){
	//	addFilter('brand',e.val);
	//}).on("select2-removing", function (e) {
	//	 addFilter('brand',e.val);   
	//});
	<?php if($_POST['gID']){ ?>
	
	<?php } ?>
});

</script>

<?php endif; ?>
