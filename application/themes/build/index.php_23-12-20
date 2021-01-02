<?php
defined('C5_EXECUTE') or die("Access Denied");
$Themepath = $this->getThemePath();
$c = Page::getCurrentPage();
$this->inc('elements/header.php');
?>
<!--  <div class="home_slider">
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
$area = new Area('Home Slider');
$area->display($c);
?>
          </ul>
        </div>
      </div>
    </div>
  </div>-->
<!--  <div class="top_highlight">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-5 col-xs-12">
<?php
$area = new Area('Home highlight1');
$area->display($c);
?>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
<?php
$area = new Area('Home highlight2');
$area->display($c);
?>
        </div>
        <div class="col-md-5 col-sm-5 col-xs-12">
<?php
$area = new Area('Home Highlight3');
$area->display($c);
?>
        </div>
      </div>
    </div>
  </div>-->
<script>
$(window).load(function () {
    setTimeout(function () {
                $('#myModal').modal();
            }, 1000);
//        var d = new Date();
//        var popDate = d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate();
//        if (localStorage.getItem('popState') != popDate) {
//            setTimeout(function () {
//                $('#myModal').modal();
//            }, 1000);
//            localStorage.removeItem('popState');
//            localStorage.setItem('popState', popDate);
//        }
    });  
</script>

<div class="top_highlight">
    <div class="container">
    <div class="row">
            <div class="col-md-4 col-sm-4">
                <a href="<?= \URL::to('/catalogue') ?>">
                    <img src="<?= $this->getThemePath(); ?>/images/price_guide_banner.jpg" class="img-responsive" />
                </a>
            </div>            
            <div class="col-md-4 col-sm-4">
              <!-- <a href="<?= \URL::to('/trade-services/courier-service') ?>">
                  <img src="<?= $this->getThemePath(); ?>/images/within_3working_days.jpg" class="img-responsive" />
              </a> -->
              <div class="box" style="background-color:#fff; padding:0px 10px;">							
    				<div class="icon">
    				    <!-- <div class="image"><i class="fa fa-thumbs-o-up"></i></div> -->
    					<div class="info">
                            <div style="margin-bottom: 8px;">
                                <a href="<?= \URL::to('product/handyman') ?>">
                                    <img src="<?= $this->getThemePath()?>/images/supply-and-apply.jpg" class="img-responsive" />
                                </a>                    
                            </div>                                                            
                            <div>
                                <a href="<?= \URL::to('product/supply-replacement') ?>">
                                    <img src="<?= $this->getThemePath()?>/images/supply-and-replace.jpg" class="img-responsive" />
                                </a>                                                              
                            </div> 
    					</div>
    				</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div style="background-image: linear-gradient(to right, #ec7c05bf, #fff);padding: 5px 35px;text-align: center;">
                    <h4 class="modal-title" style="border: 1px solid #52381c; border-radius: 3px;font-size: 22px;">Fast Track Order</h4>
                    <small class="hidden-sm">Drop us your Number and our Team will call you</small>
                    <form action="https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST" id="fastTrackFormHome">

                        <input type=hidden name="oid" value="00D0O000000Z7K2">
                        <input type=hidden name="retURL" value="https://www.800benaa.com/">

                        <!--  ----------------------------------------------------------------------  -->
                        <!--  NOTE: These fields are optional debugging elements. Please uncomment    -->
                        <!--  these lines if you wish to test in debug mode.                          -->
                        <!--  <input type="hidden" name="debug" value=1>                              -->
                        <!--  <input type="hidden" name="debugEmail" value="bd@duconodl.com">         -->
                        <!--  ----------------------------------------------------------------------  -->
                        <input id="recordType" name="recordType" type="hidden" value="0121r000000nbcQ" />
                        <input id="00N1r00000KB5yK" name="00N1r00000KB5yK" type="hidden" value="Prospect - Benaa" />
                        <div class="form-group">                
                            <div class="input-group mb-2">
                                <div class="input-group-addon hidden-sm" style="background-color: #ec7c05b8;border-color: #ec7c05b8;color: #fff;">
                                    <!--<i class="fa fa-mobile" aria-hidden="true"></i>-->
                                    <div class="input-group-text">Mobile</div>
                                </div>
                                <input  id="phone" maxlength="40" name="phone" size="20" type="text" class="form-control" required="" />
                            </div>
                        </div>
                        <input type="submit" name="submit" value="Call Me" class="btn" style="background-color: #ec7c05;color: #fff;">
                    </form>
                    <small>Or send your inquiry on <a href="mailto:sales@800benaa.com">sales@800benaa.com</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="top_highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?php
                $area = new Area('Home Hilight4');
                $area->display();
                ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?php
                $area = new Area('Home highlight5');
                $area->display($c);
                ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?php
                $area = new Area('Home Highlight6');
                $area->display($c);
                ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?php
                $area = new Area('Home Highlight7');
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
            $area = new Area('Home Products');
            $area->display();
            ?>
        </div>
    </div>
</div>
<?php
$this->inc('elements/footer.php');
?>
