/*
this will establish form validation rules and actions

format-email: must follow the email format
match-#[id]: must match the value of the element #[id]
min-[val]: must be >= val
max-[val]: must be <= val

numerical rules
decimal-only: 
integer-only:
text-only:


loop through every validate class
	- split up the data-rule attribute
		apply appropriate functions based upon data rule
		
		

*/

$(document).ready(function(){

	//append data validation rules to any validate class elements
	
	$('.validate').each(function(){
	  
		datarules=[];
		
		datarules=$(this).attr('validation-rule').split(' ');
		
		//alert($(this).attr('validation-rule'));
		
		var thisID=$(this).attr('id');
		
		for(i=0;i<datarules.length;i++){

			//get sub rules from compound rule
			subrules=[];
			subrules=datarules[i].split('-');
			
			switch(subrules[0]){
			 case 'required':
			 // alert("binding required blur function to " + thisID);
			  $(this).on('blur',function(){
		
				  if($.trim($(this).val())!=''){
				    $.showValidation(1, thisID);
				  }
				  else{
				    $.showValidation(0, thisID);
				  }

			  });
			  
			  break;
			
			case 'match':
			  alert (subrules[1]);
			  //alert ("binding match function to " +thisID);
			  var matchID=subrules[1];
			  
			//must match value of specified 
			 $(this).on('blur',function(){
			   
				 if($(this).val()!=$(matchID).val()){	 
					 $.showValidation(0, thisID);
				 }
				  else{
					 $.showValidation(1,thisID);
				  }
			 });
			 
			  break;
			  
			 case 'decimal':
			  // alert ("binding keydown function to " +thisID);
			 	$(this).on('keydown',function(event){
				 	var charcode= (event.which)?	event.which:event.keyCode;
				 	if(charcode != 46 && charcode > 31 && (charcode < 48 || charcode > 57)){
				 		return false;
				 	}	
				 	return true;
			 	});
			 
			   break;
			   
			 case 'integer':
			 // alert ("binding keydown integer function to" +thisID);
			 
			  	$(this).on('keydown',function(event){
				 	charchode= (event.which)?	event.which:event.keyCode;
				 	if(charcode > 31 && (charcode < 48 || charcode > 57)){
				 		return false;
				 	}	
            		 	return true;
			 	});
			   
			   break;
			  
			 case 'min':
			  //alert('binding min function to '+ thisID);
			  $(this).on('blur',function(){
				  if($(this).val()<subrules[1]){
					  $.showValidation(0, thisID);
					  alert(subrules[1]);
				  }
				  else{
					  $.showValidation(1, thisID);
				  }
				  
			  });
			  
			   break;
			   
			 case 'format':
			  //alert('binding formatting to ' + thisID);
			  
			 	$(this).on('blur',function(){
				 	
				 	 var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

				 	 if(!pattern.test($(this).val())){
				 	 	$.showValidation(0, thisID);
				 	 }
				 	 else{
					 	$.showValidation(1, thisID);
				 	 }	 
				 });
				 break;
				 
			}	
		}
	});
	
	$.showValidation=function(state, inputID){
	
		var oppState=1; 
		if(state==1){oppState=0;}
		
		var errmsg=$('#'+inputID).attr('err-msg');
		
		var validationStates=[['error',errmsg],['valid','√']];
		
			$('#'+inputID).addClass(validationStates[state][0]);
			$('#'+inputID).removeClass(validationStates[oppState][0]);
			$('#'+inputID).addClass(validationStates[state][0]);
			
			$('#' +inputID + '-validate').addClass(validationStates[state][0]);
			$('#'+inputID + '-validate').removeClass(validationStates[oppState][0]);
		    $('#'+inputID + '-validate').html(validationStates[state][1]);
	};
	
});

/*
$(document).ready(function(){

var errorMessage=new Object;

with(errorMessage){
	emailValidate='Please enter a valid email address.';
	confirmEmailValidate='Email addresses must match';
	giftAmountValidate='The minimum gift card amount is $25.00';
}

alert(errorMessage[emailValidate]);

 	$('#email').blur(function(){
	 	
	 var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

	 	if(!pattern.test($('#email').val())){
	 		$('#email').removeClass('valid');
	 		$('#email-validate').removeClass('valid');
	 		$('#email').addClass('error');
		 	$('#email-validate').addClass('error');
		 	$('#email-validate').html('Please enter a valid email address.');
	 	}
	 	else{
	 		$('#email').removeClass('error');
	 		$('#email-validate').removeClass('error');
		 	$('#email').addClass('valid');
		 	$('#email-validate').html('√');
	 	}

	 	$.checkForm();

 	});
 	$('#confirm-email').blur(function(){
 	    if($('#confirm-email').val()!=''){
 	    
	 	if($('#email').val()!=$('#confirm-email').val()){
	 		$('#confirm-email').removeClass('valid');
	 		$('#confirm-email').addClass('error');
	 		$('#confirm-email-validate').removeClass('valid');
		 	$('#confirm-email-validate').addClass('error');
		 	$('#confirm-email-validate').html('Email addresses do not match.');
	 	}
	 	else{
	 		$('#confirm-email').removeClass('error');
	 		$('#confirm-email').addClass('valid');
		 	$('#confirm-email-validate').removeClass('error');
		    $('#confirm-email-validate').html('√');
	 	}
	  }
	  $.checkForm();
 	});
 	
 	$('#gift-amount').blur(function(){
 	
	 	if($('#gift-amount').val()<25){
		 	$('#gift-amount').removeClass('valid');
		 	$('#gift-amount').addClass('error');
		 	$('#gift-amount-validate').removeClass('valid');
		 	$('#gift-amount-validate').addClass('error');
		 	$('#gift-amount-validate').html('The minimum gift card amount is $25.00');
	 	}
	 	else{
		 	$('#gift-amount').removeClass('error');
		 	$('#gift-amount').addClass('valid');
		 	$('#gift-amount-validate').removeClass('error');
		 	$('#gift-amount-validate').html('√');
	 	}
	 	
	 	$.checkForm();
 	}
 	);
 	
 
 $('#add-to-cart').bind('click',function(){
	 $('#gift-card-form').submit();	 
 });
 
 	$.checkForm=function(){
 		var formvalid=true;
	 	$('.validate').each(function(){
		 	if(!$(this).hasClass('valid')){
			 	formvalid=false;
		 	}
	 	});
	 	
	 	if(formvalid){
	 		$('#add-to-cart').removeAttr('disabled');
	 		//$('#checkout').removeAttr('disabled');
	 	}
	 	else{
		 	if(!$('#add-to-cart').attr('disabled')){
		 	 	$('#add-to-cart').attr('disabled','disabled');
		 	}
		 	//if(!$('#checkout').attr('disabled')){
			 //	$('#checkout').attr('disabled','disabled');
		 	//}
	 	}
 	}
 	
 	
 
});
*/