$(document).ready(function() {
	
	
	

	
	
	myAccount = function(){
		// Need to add a class to a label to style it differently to the other labels:
		$('label:contains("Billing Address")').addClass('special').text('Billing Address');
		$('label:contains("Shipping Address")').addClass('special').text('Shipping Address');
		$('label:contains("BillingFirstName")').text('First Name');
		$('label:contains("BillingLastName")').text('Last Name');
		$('label:contains("ShippingFirstName")').text('First Name');
		$('label:contains("ShippingLastName")').text('Last Name');
		$('label:contains("BillingPhone")').text('Phone');
		$('label:contains("City")').text('Town/City');
		$('label:contains("State/Province")').text('County');
		$('label:contains("PO Box")').text('PO Box');

	}();
	
	
	
	
	
	
	
});