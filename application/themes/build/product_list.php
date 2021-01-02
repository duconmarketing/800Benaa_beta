<?php defined('C5_EXECUTE') or die("Access Denied"); 
$Themepath= $this->getThemePath();
$c = Page::getCurrentPage();
$this->inc('elements/header.php');
//$this->inc('elements/header.php');
//$view->requireAsset('javascript','build'); 
$view->requireAsset('javascript','underscore');
$view->requireAsset('javascript','jquery/ui');
//$view->requireAsset('css','jquery/ui');
?>
<script type="text/template" id="productTemplate">
</script>
<script>
var filterOptions = [];
filterOptions['brand'] = [];
filterOptions['category'] = [];
filterOptions['price'] = [];

var addFilter = function(section,value,id){
	communityStore.waiting();
	switch(section) {
    case 'brand':
		var selector = "[data-select='brand-"+value+"']";
	   if(jQuery.inArray(value, filterOptions['brand']) == -1){
        filterOptions['brand'].push(value);
		$(selector).addClass("active");
	   }else{
			filterOptions['brand'] = $.grep(filterOptions['brand'], function(avalue) {
  				return avalue != value;
			});
		$(selector).removeClass("active"); 
		}
        break;
    case 'category':
		var selector = "[data-select='cat-"+value+"']";
		if(jQuery.inArray(value, filterOptions['category']) == -1){
      	  filterOptions['category'].push(value);
		  $(selector).addClass("active");
	 	  }else{
			filterOptions['category'] = $.grep(filterOptions['category'], function(avalue) {
  				return avalue != value;
			}); 
			$(selector).removeClass("active"); 
		}
        break;
        
    case 'price':
		var selector = "[data-select='price-"+value+"']";
		if(jQuery.inArray(value, filterOptions['price']) == -1){
      	  filterOptions['price'].push(value);
		  $(selector).addClass("active");
	 	  }else{
			filterOptions['price'] = $.grep(filterOptions['price'], function(avalue) {
  				return avalue != value;
			}); 
			$(selector).removeClass("active"); 
		}
        break;
    default:
       break;
    }
	$('.filteredProduct').html('');
	runFilter();
	
}

var runFilter = function(ccm_paging_p){
	if (ccm_paging_p == null){
      ccm_paging_p = 1;
    }
$.ajax({    
   url: "<?php echo URL::to('/product/ajax/controller/filter');?>?ccm_paging_p="+ccm_paging_p,
   data: { 'filterOptioncategory' : filterOptions['category'],'filterOptionbrand': filterOptions['brand'],'currentSection':'<?php echo $c->getCollectionID(); ?>'<?php if($c->getAttribute('show_best_seller')){ ?>,'showBestSeller':'true'<?php } ?>, 'filterOptionPrice': filterOptions['price'],},
   error: function() {
      console.log("error occcured");
   },
   success: function($response) {
	   $('.more-loader').hide();
	 	var data = $.parseJSON($response);
				//console.log(data);
			     renderProducts(data.products);	
				if(data.hasNextPage){
					$('.paging_cover').show();
					$('.load_more').data('page',data.nextPage);
					$('.load_more').data('has-filter',true);	
				}else{
					$('.paging_cover').hide();
					$('.load_more').data('has-filter',false);	
				}
	  },
   type: 'POST'
});

}
var renderProducts = function(data){
	 var _templateSlide = _.template($('#productTemplate').html());
	 var slideContainer = $('.filteredProduct');
	  //slideContainer.html('');
	
	 if(data.length > 0){
	   $.each(JSON.parse(data),function(i,product){
		   slideContainer.append(_templateSlide({
			 pName: product.pName,
			 price: product.price,
			 thumbnail: product.image,
			 pID: product.pID,
			 sku: product.sku,
			 pageLink: product.pageLink,
			 visibility:false
		}));
	})}else{
	slideContainer.html('<h1>No Products Found</h1>');	
	}
	loadMore.addEvent();
	communityStore.exitModal();
	
}
var loadMore = {
	renderMore: function(data){
	var _templateSlide = _.template($('#productTemplate').html());
	 var slideContainer = $('.filteredProduct');
	 if(data.length > 0){
	   $.each(JSON.parse(data),function(i,product){
		   slideContainer.append(_templateSlide({
			 pName: product.pName,
			 price: product.price,
			 thumbnail: product.image,
			 pID: product.pID,
			 pageLink: product.pageLink,
			 visibility:false
		}));
	})}else{
	slideContainer.html('<h1>No Products Found</h1>');	
	}
	loadMore.addEvent();
	communityStore.exitModal();
	},
	addEvent:function(){
		var imgs = $('.filteredProduct').find('img');
		counterValue = 0;
	    lengthTotal = imgs.length;	
		[].forEach.call( imgs, function( img ) {
           img.addEventListener( 'load', loadMore.runProcess, false );
       } );	
	},
	runProcess:function(){
		counterValue = counterValue+1;
		if(this.counterValue == this.lengthTotal ){
			build.run();
			loadMore.makeVisible();
		}
	},
	_init :function(){
		lengthTotal = 0;
		counterValue = 0;
		loadMore.fetchTemplate();
	},
	makeVisible : function(){
		$('.store-product-list-item').css('visibility','visible');	
	},
	fetchTemplate:function(){
		$.ajax({
		url:'<?= URl::to('/product/ajax/get/template'); ?>',
		success:function(response){
			$('#productTemplate').html(response);			
		},
		error:function(response){
			
		},
		type:'POST'	
		})
		
	}
}


$(document).ready(function(){
loadMore._init();
<?php if($_POST['gID']){ ?>
addFilter('brand','<?php echo $_POST['gID']; ?>');
<?php } ?>
<?php
switch($level){
	
	case 1:
		break;
	case 2:
		echo "addFilter('category','".$c->getCollectionID()."');";
		//echo '$(\'.active\').parent().parent().addClass("not-respond");';
		break;	
	
}
?>


});

</script>
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
        <div class="col-md-3 col-sm-3 col-xs-12 hidden-xs">
            <div class="brands">
            <?php
                $area=new Globalarea('Brands');
                $area->display();
            ?>
          </div>
            
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
        <div class="col-md-9 col-sm-9 col-xs-12">
        <h2><?php echo $c->getCollectionName();?></h2>
          <div class="row filteredProduct">
          
            <?php
           $area=new GlobalArea('Products');
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
