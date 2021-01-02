<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
//die('asdasdasd');
require('tcpdf/config/lang/eng.php');
require('tcpdf/config/tcpdf_config.php');
require('tcpdf/config/tcpdf_config_alt.php');
require('tcpdf/tcpdf.php');
require_once('fpdi.php'); // Extend the TCPDF class to create custom Header and Footer
// Extend the TCPDF class to create custom Header and Footer




class MYPDF extends FPDI {
 var $_tplIdx; 
 //Page header
 public function Header() {
  
   if (is_null($this->_tplIdx)) { 
           $this->setSourceFile(DIR_BASE.'/application/tools/print_spa/Web_Form_Template3.pdf');  
            $this->_tplIdx = $this->importPage(1); 
         } 
        $this->useTemplate($this->_tplIdx,0,0); 
  
  
  // Logo
 }
 public function Footer() {
 }

 
 }
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(SITE);
$pdf->SetTitle(SITE);
$pdf->SetSubject(SITE);
$pdf->SetKeywords(SITE);
$pdf->setPageOrientation('P',TRUE,10);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(13, 65, 13);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(0);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('arial', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
$pdf->setCellHeightRatio(1.5);



$pdf1 = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
// set document information
$pdf1->SetCreator(PDF_CREATOR);
$pdf1->SetAuthor(SITE);
$pdf1->SetTitle(SITE);
$pdf1->SetSubject(SITE);
$pdf1->SetKeywords(SITE);
$pdf1->setPageOrientation('P',TRUE,10);
// set default monospaced font
$pdf1->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf1->SetMargins(13, 65, 13);
$pdf1->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf1->SetFooterMargin(0);

//set auto page breaks
$pdf1->SetAutoPageBreak(TRUE, 20);

//set image scale factor
$pdf1->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf1->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf1->SetFont('arial', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf1->AddPage();
$pdf1->setCellHeightRatio(1.5);


// output the HTML content
//$content
ob_start();

?>
<style type="text/css">
."border_bottom {
	border:1px solid #000;
	}
</style>
<?php 
$cssstyle=ob_get_contents();
ob_clean();
ob_start();
//print_r($postValues);die('asas');
?>

<h1>Employment Application</h1>
<table cellpadding="5" class="employe_info" width="100%" border="1">
  <tr class="gray_bg" valign="middle">
    <td colspan="4"><strong><h2>A. Personal Information</h2></strong></td>
  </tr>
  <tr  class="border" style="border:1px solid #000;">
    <td width="25%" class="border_bottom"><b>First Name :</b>&nbsp;<?php echo $postValues['firstname']; ?></td>
    <td width="25%" class="border_bottom"><b>Last Name :</b>&nbsp;<?php echo $postValues['lastname']; ?></td>
    <td colspan="2" class="border_bottom"><b>Phone with area code :</b>&nbsp;<?php echo $postValues['aphone']; ?></td>
    
  </tr>
  <tr>
    <td colspan="3" class="border_bottom border_left"><b>Position :</b>&nbsp;<?php echo $postValues['position']; ?></td>
    <td class="border_bottom"></td>
    
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b>Are any relatives employed by this company?</b>&nbsp;<?php echo $postValues['r_employ']; ?></td>
    
    <td colspan="2" class="border_bottom border_right"><b>Name:</b>&nbsp;<?php echo $postValues['rname'];?></td>
  </tr>
  <tr>
    <td width="20%" class="border_bottom border_left"><b>Current Address</b></td>
    <td width="20%" class="border_bottom"><b>Street</b>&nbsp;<?php echo $postValues['street']; ?></td>
     <td width="20%" class="border_bottom"><b>City</b>&nbsp;<?php echo $postValues['city']; ?></td>
    <td width="20%" class="border_bottom"><b>State/Province</b>&nbsp;<?php echo $postValues['state_province']; ?></td>
    <td width="20%" class="border_bottom border_right"><b> ZIP</b>&nbsp;<?php echo $postValues['zip']; ?></td>
  </tr>
  <tr>
    <td colspan="4" class="border_bottom border_left"><b>Are you prevented from lawfully becoming employed in this country because of visa or immigration status?</b>&nbsp;<?php echo $postValues['v_status']; ?></td>
  </tr>
  <tr class="gray_bg" valign="middle">
    <td colspan="4"><strong><h2>B. EDUCATION</h2></strong></td>
  </tr>
  
   <tr>
    <td width="10%" class="border_bottom border_left"><b>Highest Level</b></td>
    <td width="30%" class="border_bottom"><b>Name Location</b></td>
    <td width="30%" class="border_bottom"><b>Years Attended</b></td>
    <td width="30%" class="border_bottom border_right"><b> Subjects Studied</b></td>
  </tr>
  <tr>
    <td width="10%" class="border_bottom border_left"><b>High School</b></td>
    <td width="30%" class="border_bottom">&nbsp;<?php echo $postValues['namelocation1']; ?></td>
    <td width="30%" class="border_bottom">&nbsp;<?php echo $postValues['yearsattended1']; ?></td>
    <td width="30%" class="border_bottom border_right">&nbsp;<?php echo $postValues['subjectstudied1']; ?></td>
  </tr>
   <tr>
    <td width="10%" class="border_bottom border_left"><b>Jr. College or College or University</b></td>
    <td width="30%" class="border_bottom">&nbsp;<?php echo $postValues['namelocation2']; ?></td>
    <td width="30%" class="border_bottom">&nbsp;<?php echo $postValues['yearsattended2']; ?></td>
    <td width="30%" class="border_bottom border_right">&nbsp;<?php echo $postValues['subjectstudied2']; ?></td>
  </tr>
  <tr>
    <td width="10%" class="border_bottom border_left"><b>Business or Trade School</b></td>
    <td width="30%" class="border_bottom">&nbsp;<?php echo $postValues['namelocation3']; ?></td>
    <td width="30%" class="border_bottom">&nbsp;<?php echo $postValues['yearsattended3']; ?></td>
    <td width="30%" class="border_bottom border_right">&nbsp;<?php echo $postValues['subjectstudied3']; ?></td>
  </tr>
  </table>
  <?php 
  $testhtml1=ob_get_contents();
  ob_clean();
  $pdf->writeHTML($cssstyle.$testhtml1);
  ob_start();
  $pdf->AddPage();
  ?>
 <table cellpadding="5" class="employe_info" width="100%" border="1">
  <tr class="gray_bg" valign="middle">
    <td colspan="4"><strong><h2>C. EMPLOYMENT HISTORY - List present or most recent employer first</h2></strong></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b>Company Name : </b>&nbsp;<?php echo $postValues['companyname']; ?></td>
    <td colspan="2" class="border_bottom"><b>Telephone : </b>&nbsp;<?php echo $postValues['phonenumber']; ?></td>
  </tr>
   <tr>
    <td colspan="2" class="border_bottom border_left"><b> Street Address : </b>&nbsp;<?php echo $postValues['streetaddress']; ?></td>
    <td colspan="2" class="border_bottom"><b>Date Employed : </b>&nbsp;<?php echo $postValues['dateemployed']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b> City, State and Zip Code : </b>&nbsp;<?php echo $postValues['city_tate_zip']; ?></td>
    <td  class="border_bottom"><b>Rate of Pay : </b></td>
    <td  class="border_bottom"><b>Start : </b>&nbsp;<?php echo $postValues['start']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b>  Contact Person or Supervisor : </b>&nbsp;<?php echo $postValues['contactperson']; ?></td>
    <td  class="border_bottom"><b>Pay Period : </b>&nbsp;<?php echo $postValues['pay_period']; ?></td>
    <td  class="border_bottom"><b> End : </b>&nbsp;<?php echo $postValues['end']; ?></td>
  </tr>
  <tr>
    <td colspan="4" class="border_bottom border_left"><b> Primary Responsibilities : </b>&nbsp;<?php echo $postValues['primary_responsibilities']; ?></td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> Reason for leaving : </b>&nbsp;<?php echo $postValues['reason_leaving']; ?></td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> Positions : </b>&nbsp;<?php echo $postValues['positions']; ?></td>
  </tr>
   <tr>
    <td colspan="2" class="border_bottom border_left"><b>Company Name : </b>&nbsp;<?php echo $postValues['companyname1']; ?></td>
    <td colspan="2" class="border_bottom"><b>Telephone : </b>&nbsp;<?php echo $postValues['phonenumber1']; ?></td>
  </tr>
   <tr>
    <td colspan="2" class="border_bottom border_left"><b> Street Address : </b>&nbsp;<?php echo $postValues['streetaddress1']; ?></td>
    <td colspan="2" class="border_bottom"><b>Date Employed : </b>&nbsp;<?php echo $postValues['dateemployed1']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b> City, State and Zip Code : </b>&nbsp;<?php echo $postValues['city_tate_zip1']; ?></td>
    <td  class="border_bottom"><b>Rate of Pay : </b></td>
    <td  class="border_bottom"><b>Start : </b>&nbsp;<?php echo $postValues['start1']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b>  Contact Person or Supervisor : </b>&nbsp;<?php echo $postValues['contactperson1']; ?></td>
    <td  class="border_bottom"><b>Pay Period : </b>&nbsp;<?php echo $postValues['pay_period1']; ?></td>
    <td  class="border_bottom"><b> End : </b>&nbsp;<?php echo $postValues['end1']; ?></td>
  </tr>
  <tr>
    <td colspan="4" class="border_bottom border_left"><b> Primary Responsibilities : </b>&nbsp;<?php echo $postValues['primary_responsibilities1']; ?></td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> Reason for leaving : </b>&nbsp;<?php echo $postValues['reason_leaving1']; ?></td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> Positions : </b>&nbsp;<?php echo $postValues['positions1']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b>Company Name : </b>&nbsp;<?php echo $postValues['companyname2']; ?></td>
    <td colspan="2" class="border_bottom"><b>Telephone : </b>&nbsp;<?php echo $postValues['phonenumber2']; ?></td>
  </tr>
   <tr>
    <td colspan="2" class="border_bottom border_left"><b> Street Address : </b>&nbsp;<?php echo $postValues['streetaddress2']; ?></td>
    <td colspan="2" class="border_bottom"><b>Date Employed : </b>&nbsp;<?php echo $postValues['dateemployed2']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b> City, State and Zip Code : </b>&nbsp;<?php echo $postValues['city_tate_zip2']; ?></td>
    <td  class="border_bottom"><b>Rate of Pay : </b></td>
    <td  class="border_bottom"><b>Start : </b>&nbsp;<?php echo $postValues['start2']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="border_bottom border_left"><b>  Contact Person or Supervisor : </b>&nbsp;<?php echo $postValues['contactperson2']; ?></td>
    <td  class="border_bottom"><b>Pay Period : </b>&nbsp;<?php echo $postValues['pay_period2']; ?></td>
    <td  class="border_bottom"><b> End : </b>&nbsp;<?php echo $postValues['end2']; ?></td>
  </tr>
  <tr>
    <td colspan="4" class="border_bottom border_left"><b> Primary Responsibilities : </b>&nbsp;<?php echo $postValues['primary_responsibilities2']; ?></td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> Reason for leaving : </b>&nbsp;<?php echo $postValues['reason_leaving2']; ?></td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> Positions : </b>&nbsp;<?php echo $postValues['positions2']; ?></td>
  </tr>
  </table>
  
  <?php 
  $testhtml=ob_get_contents();
  ob_clean();
  $pdf->writeHTML($cssstyle.$testhtml);
  ob_start();
  ?>
  <table>
  <tr class="gray_bg" valign="middle">
    <td colspan="4"><strong><h2>D. OPTIONAL APPLICATION SURVEY  </h2></strong></td>
  </tr>
  <tr class="gray_bg" valign="middle">
    <td colspan="4"><p>As part of Tiller Corporation's Affirmative Action Program and its compilation of data for equal employment opportunity reporting requirements, we are required to collect statistical information about the ethnic/sex/disability composition of its job applicants to various governmental agencies. In order to accurately report its applicant statistics, Tiller is requesting your assistance in providing the information requested by completing the following survey. <br>

THIS SURVEY IS CONFIDENTIAL AND IS FOR REPORTING PURPOSES ONLY. IT WILL NOT BE USED TO MAKE A DECISION ON WHETHER TO HIRE YOU. THIS SURVEY IS NOT PART OF YOUR APPLICATION AND IS MAINTAINED AS A SEPARATE DOCUMENT BY TILLER'S EEO COMPLIANCE OFFICER.<br>

This information is strictly voluntary.<br>

THANK YOU FOR YOUR COOPERATION!  </p></td>
   </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> 1. Age : </b>&nbsp;<?php echo $postValues['age']; ?></td>
  </tr>
  <tr>
    <td colspan="4" class="border_bottom border_left"><b> 2. Type of job applied for : </b>&nbsp;<?php
   if($postValues['surjob']){
	$sr=$postValues['surjob'];
	
	foreach($sr as $s){
	echo $s;
	}
	}
	 ?> </td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> 3. Gender : </b>&nbsp;<?php echo $postValues['gender']; ?></td>
  </tr>
  <tr>
    <td colspan="4" class="border_bottom border_left"><b> 4. Race : </b>&nbsp;<?php //echo implode(",",$postValues['race']);
	if($postValues['race']){
	$rc=$postValues['race'];
	foreach($rc as $r){
	echo $r;
	}
	}
	 ?></td>
  </tr>
  <tr>
    <td colspan="4" class="border_bottom border_left"><b> 5. Disability : </b><br />
	A disability is defined as a "physical or mental impairment that substantially limits a major life activity." This means major life activities such as walking, seeing, hearing,  speaking, breathing, learning and working.<br>

If you perceive yourself to have a disability please check "Yes" and specify the disability. If you do not perceive yourself to have a disability, check "No."<br><br>
	
	<?php //echo implode(",",$postValues['disability']); 
	if($postValues['disability']=='Yes'){
	?>
     <div style="padding-top:5px;margin-left:10px;display:table-cell"><img src="<?php echo BASE_URL.DIR_REL ?>/application/themes/tiller/images/ticked_condn.png"  /> yes, I perceive myself as having a disability </div>
    <?php }else{?>
    <div style="padding-top:5px;margin-left:10px;display:table-cell"><img src="<?php echo BASE_URL.DIR_REL ?>/application/themes/tiller/images/ticked_condn.png"  /> No, I do not perceive myself as having a disability </div>
    <?php }?>
	
	<?php echo $postValues['disabilityspecify']; ?><br /></td>
  </tr>
   <tr>
    <td colspan="4" class="border_bottom border_left"><b> 6. How did you hear about employment with Tiller? : </b>&nbsp;
	<?php 
	if($postValues['hear']){
	$hr=$postValues['hear']; 
	foreach($hr as $h){
	echo '<br><div style="padding-top:5px;margin-left:10px;"><img src="'.BASE_URL.DIR_REL.'/application/themes/tiller/images/ticked_condn.png"  />' .$h.'</div>';
	}
	
	?>
	,<?php echo $postValues['employeename']; ?>
	<?php echo $postValues['publication']; ?><?php echo $postValues['agency']; ?><?php echo $postValues['source'];} ?></td>
  </tr>
</table>
<?php 
$testhtml2=ob_get_contents();
ob_clean();
$pdf1->writeHTML($cssstyle.$testhtml2);

$js .= 'print(true);';

// set javascript
$pdf->IncludeJS($js);
$pdf1->IncludeJS($js);
//$pdf->Output('spa_pdfs/form_'.$spaID.'_ca2.pdf');
$pdf->Output('emp_pdfs/'.$pdfName.'','F');
$pdf1->Output('emp_pdfs/N-'.$pfName.'','F');