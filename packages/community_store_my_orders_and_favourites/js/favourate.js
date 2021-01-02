var favourate = {
 addToFavourate : function(pID,uID,element){
	  $.ajax({
		url:CCM_APPLICATION_URL +'/addtofavourate',
		data:{'pID':pID,'uID':uID},
		success:function(response){
			var result = JSON.parse(response);	
			console.log(result);
			if(result.success){
				console.log("success");
				element.removeClass("add-to-favourite").addClass("remove-from-favourite");
				$('.message').text("Added to Favourites").fadeIn().delay( 1200 ).fadeOut();			
			}		
		},
		error:function(response){
			
		},
		method:'POST'
		});
	},
  removeFromFavourate:function(pID,uID,element){
	 $.ajax({
		url:CCM_APPLICATION_URL +'/removefromfavourate',
		data:{'pID':pID,'uID':uID},
		success:function(response){
			var result = JSON.parse(response);
			console.log(result);	
			if(result.success){
				console.log("success");
				element.removeClass("remove-from-favourite").addClass("add-to-favourite");
				$('.message').text("Removed from Favourites").fadeIn().delay( 1200 ).fadeOut();			
			}	
			
		},
		error:function(response){
			
		},
		method:'POST'
		});
	}	
}
$(function(){
	
	$(document).on('click','.add-to-favourite',function(){
		var uID = $(this).data('uid');
		var pID = $(this).data('pid');
		favourate.addToFavourate(pID,uID,$(this));
	});
	$(document).on('click','.remove-from-favourite',function(){
		var uID = $(this).data('uid');
		var pID = $(this).data('pid');
		favourate.removeFromFavourate(pID,uID,$(this));
	});
	
});