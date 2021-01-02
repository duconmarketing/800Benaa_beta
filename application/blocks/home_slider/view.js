$(document).ready(function(){
	
	$("ul.outerslide li:first-child").addClass("active");
	
	var carinn = $('.hom_slid');
	
	carinn.append($('.outerslide li').eq(0).find('.item').clone());
	carinn.find('.item').eq(0).fadeIn();
	$("ul.outerslide li").hover(function(){
	if($(this).hasClass('active')){
		return;
	}
		
		var sid = $(this).data('sid');
		//alert(sid)
		
		if(carinn.find("#"+sid) !== undefined){
				carinn.append($(this).find('.item').clone());
				carinn.find('.item').hide();
				carinn.find('#item_'+sid).fadeIn();
			}else{
				carinn.find('.item').hide();
				carinn.find('#item_'+sid).fadeIn();
				
			}
		$("ul.outerslide li").removeClass('active');
		$(this).addClass('active');
		
		
		
	});
	

	
	});
	