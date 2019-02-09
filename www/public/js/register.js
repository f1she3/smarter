$(document).ready(function(){
	$('#usernameReg').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors-block').addClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger invisible">silence is gold</div>');		
		}
	});
	$('#passwordReg').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors-block').addClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger invisible">silence is gold</div>');		
		}
	});
	$('button[name=submit]').click(function(){
		if($('#usernameReg').val() === ''){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign"></span> please choose an username\
						</div>');
			$('#usernameReg').focus();

			return false;
		}	
		if($('#passwordReg').val() === ''){
			$('.errors-block').removeClass('invisible');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign"></span> please choose a password\
						</div>');
			$('#passwordReg').focus();

			return false;
		}
		if($('#rPasswordReg').val() === ''){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign"></span> please retype your password\
						</div>');
			$('#rPasswordReg').focus();

			return false;
		}	
		if($('#passwordReg').val() !== $('#rPasswordReg').val()){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center"><span class="glyphicon glyphicon-exclamation-sign"></span> the passwords don\'t match</div>');
			$('#passwordReg').val('');
			$('#rPasswordReg').val('');
			$('#passwordReg').focus();

			return false;
		}
		if($('#passwordReg').val().length < 8){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign"></span> your password must be at least 8 chars long\
						</div>');
			$('#passwordReg').val('');
			$('#rPasswordReg').val('');
			$('#passwordReg').focus();

			return false;
		}
	});
});
