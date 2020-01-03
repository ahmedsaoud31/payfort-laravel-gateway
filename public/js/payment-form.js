$(function(){
	$('[name=cardnumber]').payment('formatCardNumber');
	$('[name=cc-exp]').payment('formatCardExpiry');
	$('[name=cvc]').payment('formatCardCVC');
	$.fn.toggleInputError = function(erred) {
		this.parent().parent('.form-group').toggleClass('has-error', erred);
		return this;
	};
	$('#paymentsForm [name=ccname]').bind("input", function() {
		$(this).parent().parent('.form-group').removeClass('has-error');
		changeNameColor($(this).val());
	});
	
	$('#paymentsForm [name=email]').bind("input", function() {
		$(this).parent().parent('.form-group').removeClass('has-error');
		changeEmailColor($(this).val());
	});
	
	$('#paymentsForm [name=cardnumber]').bind("input", function() {
		$(this).parent().parent('.form-group').removeClass('has-error');
		changeCardIcon($(this).val());
	});
	
	$('#paymentsForm [name=cc-exp]').bind("input", function() {
		$(this).parent().parent('.form-group').removeClass('has-error');
		changeExpColor($(this).val());
	});
	
	$('#paymentsForm [name=cvc]').bind("input", function() {
		$(this).parent().parent('.form-group').removeClass('has-error');
		changeCvcColor($(this).val());
	});
	
	$('#paymentsForm [name=ccname]').bind("change", function() {
		if(validateCCName($(this).val())){
			$('#paymentsForm [name=ccname]').parent().parent('.form-group').removeClass('has-error');
		}else{
			$('#paymentsForm [name=ccname]').parent().parent('.form-group').addClass('has-error');
		}
	});
	
	$('#paymentsForm [name=email]').bind("change", function() {
		if(validateEmail($(this).val())){
			$('#paymentsForm [name=email]').parent().parent('.form-group').removeClass('has-error');
		}else{
			$('#paymentsForm [name=email]').parent().parent('.form-group').addClass('has-error');
		}
	});
	
	$('#paymentsForm [name=cardnumber]').bind("change", function() {
		var cardnumber = $.payment.validateCardNumber($(this).val());
		changeCardIcon($(this).val());
		if(cardnumber){
			$('#paymentsForm [name=cardnumber]').parent().parent('.form-group').removeClass('has-error');
		}else{
			$('#paymentsForm [name=cardnumber]').parent().parent('.form-group').addClass('has-error');
		}
	});
	
	$('#paymentsForm [name=cc-exp]').bind("change", function() {
		changeExpColor($(this).val());
		if(validateExp($(this).val())){
			$('#paymentsForm [name=cc-exp]').parent().parent('.form-group').removeClass('has-error');
		}else{
			$('#paymentsForm [name=cc-exp]').parent().parent('.form-group').addClass('has-error');
		}
	});
	
	$('#paymentsForm [name=cvc]').bind("change", function() {
		changeCvcColor($(this).val());
		if($.payment.validateCardCVC($(this).val())){
			$('#paymentsForm [name=cvc]').parent().parent('.form-group').removeClass('has-error');
		}else{
			$('#paymentsForm [name=cvc]').parent().parent('.form-group').addClass('has-error');
		}
	});
});
function changeCardIcon(cardnumber){
	var type = $.payment.cardType(cardnumber);
	$('#cardIcon').removeClass(function(index, className) {
		return (className.match (/\bsite-color\S+/g) || []).join(' ');
	});
	$('#cardIcon').removeClass('fa-credit-card-alt fa-cc-visa fa-cc-mastercard');
	if(type == 'visa'){
		$('#cardIcon').addClass('site-color1');
		$('#cardIcon').addClass('fa-cc-visa');
	}else if(type == 'mastercard'){
		$('#cardIcon').addClass('site-color2');
		$('#cardIcon').addClass('fa-cc-mastercard');
	}else{
		$('#cardIcon').addClass('site-color0');
		$('#cardIcon').addClass('fa-credit-card-alt');
	}
}

function changeNameColor(ccname){
	$('#ccnameIcon').removeClass(function(index, className) {
		return (className.match (/\bsite-color\S+/g) || []).join(' ');
	});
	if(validateCCName(ccname)){
		$('#ccnameIcon').addClass('site-color3');
	}else{
		$('#ccnameIcon').addClass('site-color0');
	}
}

function changeEmailColor(email){
	$('#emailIcon').removeClass(function(index, className) {
		return (className.match (/\bsite-color\S+/g) || []).join(' ');
	});
	if(validateEmail(email)){
		$('#emailIcon').addClass('site-color3');
	}else{
		$('#emailIcon').addClass('site-color0');
	}
}

function changeExpColor(ccexp){
	var ccexp_validate = validateExp(ccexp);
	$('#expIcon').removeClass(function(index, className) {
		return (className.match (/\bsite-color\S+/g) || []).join(' ');
	});
	if(ccexp_validate){
		$('#expIcon').addClass('site-color3');
	}else{
		$('#expIcon').addClass('site-color0');
	}
}

function changeCvcColor(cvc){
	var cvc_validate = $.payment.validateCardCVC(cvc);
	$('#cvcIcon').removeClass(function(index, className) {
		return (className.match (/\bsite-color\S+/g) || []).join(' ');
	});
	if(cvc_validate){
		$('#cvcIcon').addClass('site-color3');
	}else{
		$('#cvcIcon').addClass('site-color0');
	}
}

function validateCCName(ccname){
	if(ccname.match(/[^A-Za-z ]/g) || !ccname){
		return false;
	}else{
		return true;
	}
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validateExp(ccexp){
	ccexp = ccexp.replace(/[^0-9]/g, "");
	if(ccexp.length == 4){
		ccexp = ccexp[2]+ccexp[3]+ccexp[0]+ccexp[1];
	}else if(ccexp.length == 6){
		ccexp = ccexp[4]+ccexp[5]+ccexp[0]+ccexp[1];
	}
	return $.payment.validateCardExpiry(ccexp[2]+ccexp[3], ccexp[0]+ccexp[1]);
}

function getCcExp(ccexp){
	ccexp = ccexp.replace(/[^0-9]/g, "");
	if(ccexp.length == 4){
		ccexp = ccexp[2]+ccexp[3]+ccexp[0]+ccexp[1];
	}else if(ccexp.length == 6){
		ccexp = ccexp[4]+ccexp[5]+ccexp[0]+ccexp[1];
	}
	return ccexp;
}

function validatePaymentForm(){
	var ccname = $('#paymentsForm [name=ccname]').val();
	var ccname_validate  = validateCCName(ccname);
	
	var cardnumber = $('#paymentsForm [name=cardnumber]').val();
	var cardnumber_validate = $.payment.validateCardNumber(cardnumber);
	
	var cvc = $('#paymentsForm [name=cvc]').val();
	var cvc_validate = $.payment.validateCardCVC(cvc);
	
	var ccexp = $('#paymentsForm [name=cc-exp]').val();
	var ccexp_validate = validateExp(ccexp);
	
	$('#paymentsForm').find('.form-group').removeClass('has-error');
	$('#paymentsForm').find('.alert-danger').hide().html('');
	
	if(!ccname_validate){
		$('#paymentsForm [name=ccname]').parent().parent('.form-group').addClass('has-error');
		$('#paymentsForm').find('.alert-danger').append('<div>- Card holder name not vaild.</div>');
	}
	if(!cardnumber_validate){
		$('#paymentsForm [name=cardnumber]').parent().parent('.form-group').addClass('has-error');
		$('#paymentsForm').find('.alert-danger').append('<div>- Card number not vaild.</div>');
	}
	if(!ccexp_validate){
		$('#paymentsForm [name=cc-exp]').parent().parent('.form-group').addClass('has-error');
		$('#paymentsForm').find('.alert-danger').append('<div>- Expiry date not vaild.</div>');
	}
	if(!cvc_validate){
		$('#paymentsForm [name=cvc]').parent().parent('.form-group').addClass('has-error');
		$('#paymentsForm').find('.alert-danger').append('<div>- CVC not vaild.</div>');
	}
	
	if(!ccname_validate || !cardnumber_validate || !ccexp_validate || !cvc_validate){
		$('#paymentsForm').find('.alert-danger').show();
		return false;
	}else{
		return true;
	}
}