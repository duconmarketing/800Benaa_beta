<?php
defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Validation\CSRF\Token;

$Themepath = $this->getThemePath();
global $u;

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php Loader::element('header_required'); ?>
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo $Themepath ?>/images/favicon.png" type="image/x-icon" />
        <link rel="apple-touch-icon" href="<?php echo $Themepath ?>/images/apple-touch-icon.png">

        <!-- Web Font -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="<?php echo $Themepath ?>/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $Themepath ?>/css/hover.css" rel="stylesheet">
        <link href="<?php echo $Themepath ?>/css/meanmenu.css" rel="stylesheet">
        <link href="<?php echo $Themepath ?>/css/mobilemenubutton.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="<?php echo $Themepath ?>/css/main.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo $Themepath ?>/css/xzoom.css" media="all" />

        <!-- Custom JS -->
        <?php $view->requireAsset('javascript', 'jquery'); ?>
        <?php if (!$c->isEditMode()) { ?>
            <script src="<?php echo $Themepath ?>/js/bootstrap.min.js" type="text/javascript"></script>
        <?php } ?>
        <script src="<?php echo $Themepath ?>/js/jquery.meanmenu.js" type="text/javascript"></script>
        <script src="<?php echo $Themepath ?>/js/build.js" type="text/javascript"></script>
        <script src="<?php echo $Themepath ?>/js/site.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $Themepath ?>/js/xzoom.js"></script>
        <script type="text/javascript">
            $(document).ready(function (e) {
                $('.mobile_nav nav').meanmenu();
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        <style>
            ::-webkit-input-placeholder {
                color:#4a4949;
            }

            ::-moz-placeholder {
                color:#4a4949;
            }

            ::-ms-placeholder {
                color:#4a4949;
            }

            ::placeholder {
                color:#4a4949;
            }

            .modal {
                text-align: center;
                padding: 0!important;
              }

              .modal:before {
                content: '';
                display: inline-block;
                height: 100%;
                vertical-align: middle;
                margin-right: -4px;
              }

              .modal-dialog {
                display: inline-block;
                text-align: left;
                vertical-align: middle;
              }
              input[type=number]::-webkit-inner-spin-button {
                opacity: 1
            }
            .cover .customerAttach input.btn.btn-primary{
                background-color: #ec7c05;border-color: #ec7c05;color: #fff;
            }
            
            .button-leadform {
                background-color: #4CAF50; /* Green */
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 20px;
                margin: 0px 2px;
                cursor: pointer;
                -webkit-transition-duration: 0.4s; /* Safari */
                transition-duration: 0.4s;
              }

              .button-leadform:hover {
                box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
              }
        </style>
    </head>
    <?php
    $page = Page::getCurrentPage();
    $pageHandle = $page->getCollectionHandle();
    ?>
    <body id="<?php echo $pageHandle; ?>">
        <div class="<?php echo $c->getPageWrapperClass() ?>">
            <div class="cover">
			<script type="text/javascript">
				piAId = '632421';
				piCId = '11022';
				piHostname = 'pi.pardot.com';

				(function() {
					function async_load(){
						var s = document.createElement('script'); s.type = 'text/javascript';
						s.src = ('https:' == document.location.protocol ? 'https://pi' : 'http://cdn') + '.pardot.com/pd.js';
						var c = document.getElementsByTagName('script')[0]; c.parentNode.insertBefore(s, c);
					}
					if(window.attachEvent) { window.attachEvent('onload', async_load); }
					else { window.addEventListener('load', async_load, false); }
				})();
				</script>
                                
                                <!-- Google Tag Manager (noscript) -->
                                <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PGRVFPR"
                                height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
                                <!-- End Google Tag Manager (noscript) -->
                                
                <div class="mobile_nav visible-xs">
                    <nav>
                        <?php
                        $stack = Stack::getByName('Mobile nav');
                        $stack->display();
                        ?>
                    </nav>
                </div>
                <header>
                    <div class="top_bar">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="sign_up">

                                        <?php
                                        if (!id(new User())->isLoggedIn()) {
                                            $url = URL::to('/login');
                                        } else {
                                            $url = URL::to('/login', 'logout', id(new Token())->generate('logout'));
                                        }

                                        if ($u->isLoggedIn() && !$c->isEditMode()) {
                                            ?>
                                            <ul><li><a class="user" href="javascript:void(0)">Hello! <em><?php echo $u->getUserName(); ?></em><i class="fa fa-cog" aria-hidden="true"></i></a>
                                                    <ul>
                                                        <li><a href="/account"><i class="fa fa-user" aria-hidden="true"></i>My Details</a></li>
                                                        <li><a href="<?= $url ?>"><i class="fa fa-sign-out" aria-hidden="true"></i>Sign out</a></li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        <?php } else { ?>
                                            <a href="<?= $url ?>" class="sign_in"><i class="fa fa-lock" aria-hidden="true"></i>Sign In / Sign Up</a>
                                        <?php } ?>
                                        <?php
//$area=new GlobalArea('Signup Links');
//$area->display();
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-xs-12 col-sm-3">
                                    <div class="logo"> 
                                        <?php
                                        $area = new GlobalArea('Site Logo');
                                        $area->display();
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12 col-sm-5">


                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-4">


                                    <div class="cart"> 
                                        <?php
                                        $area = new GlobalArea('Cart Items');
                                        $area->display();
                                        ?>
                                    </div>
                                    <div class="social_media">
                                        <ul>
                                            <li><a href="https://www.facebook.com/800benaa" title="facebook" target="_blank"><img src="<?php echo $Themepath ?>/images/facebook1.png" alt="Facebook" class="img-responsive"></a></li>
                                            <li><a href="https://www.instagram.com/800benaa/" title="Instagram" target="_blank"><img src="<?php echo $Themepath ?>/images/instagram.png" alt="Instagram" class="img-responsive"></a></li>
                                            <?php /*?><li><a href="https://twitter.com/duconindustries" title="Twitter" target="_blank"><img src="<?php echo $Themepath ?>/images/twitter1.png" alt="Twitter" class="img-responsive"></a></li>
                                            <li><a href="#" title="LinkedIN" target="_blank"><img src="<?php echo $Themepath ?>/images/linkedIn2.png" alt="Linkedin" class="img-responsive"></a></li><?php */?>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="menu_bar">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="search">
                                        <?php
                                        $area = new GlobalArea('Search');
                                        $area->display();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="menu_bar hidden-xs">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="menu">
                                        <?php
                                        $area = new GlobalArea('Main nav');
                                        $area->display();
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $c = Page::getCurrentPage();
                            if ($c->isEditMode() && $c->getAttribute('enable_mega_menu')) {
                                $MenuLeft = $c->getCollectionName() . 'Left Column';
                                $MenuRight = $c->getCollectionName() . 'Right Column';
                                ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php
                                        $area = new Area($MenuLeft);
                                        $area->display($c);
                                        ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?php
                                        $area = new Area($MenuRight);
                                        $area->display($c);
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="bottom_bar">
                        <div class="container">
                            <div class="col-md-12">
                                <?php
                                $area = new GlobalArea('Header Content');
                                $area->display();
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php //echo BASE_URL.DIR_REL.'/application/themes/build/images/topex.png'; ?>
                </header>
                
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content text-center">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Fast Track Order</h4>
                          <small>Drop us your Number and our Team will call you for the Order</small><br/>
                          <small>... ضع رقمك وسنتواصل معك في أقرب وقت</small>
                        </div>
                        <div class="modal-body">
                            <span class="modal-succ-msg" style="display:none;">
                                <h3 style="color:#ec7c05;">Thank you...<br /></h3><h4>We will contact you within <span style="color: #ec7c05;">5 minutes!</span></h4>
                            </span>
                            <form action="https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST" id="fastTrackForm">

                                <input type=hidden name="oid" value="00D0O000000Z7K2">
                                <input type=hidden name="retURL" value="https://www.800benaa.com/">

                                <input id="recordType" name="recordType" type="hidden" value="0121r000000nbcQ" />
                                <input id="00N1r00000KB5yK" name="00N1r00000KB5yK" type="hidden" value="Prospect - Benaa" />
                                
                                <div class="form-group">
                                    <!--<label for="mobile">Mobile</label>-->
                                    <div class="input-group mb-2">
                                        <div class="input-group-addon">
                                            <div class="input-group-text">Mobile</div>
                                        </div>
                                        <input  id="phone2" maxlength="40" name="phone" size="20" type="text" class="form-control" />
                                    </div>
                                    <span id="phone-info2" style="font-size:.8em;color: #FF6600;letter-spacing:1px;padding-left:5px;"></span>
                                </div>
                                <input type="submit" name="submit" value="Call Me" class="button-leadform" style="background-color: #ec7c05;color: #fff;">
                            </form>
                            
                            <br />
                            Or send your inquiry on <a href="mailto:sales@800benaa.com">sales@800benaa.com</a> <br/>
                            <a href="mailto:sales@800benaa.com">sales@800benaa.com</a> أو ارسل استفسارك على بريدنا الالكتروني
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>

                    </div>
                  </div>
                                
                  <div class="modal fade" id="termsModal" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content text-center">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Terms and Conditions</h4>
                        </div>
                        <div class="modal-body">
                            <?php
                            $page = Page::getByID(3198);
//                            echo $page->getCollectionName();
                            $blocks = $page->getBlocks('Main');
                            foreach ($blocks as $block) {
                                if ($block->btHandle == 'content') {
                                    $content = $block->getInstance()->getContent();
                                    if (!empty($content)) {
                                        echo $content;
                                        break;
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>

                    </div>
                  </div>
                                