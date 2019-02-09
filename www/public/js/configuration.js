$(document).ready(function(){
	$('#oldPassword').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors-block').addClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger invisible">silence is gold</div>');		
		}	
	});
	$('#newPassword').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors-block').addClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger invisible">silence is gold</div>');		
		}	
	});
	$('#repeatPassword').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors-block').addClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger invisible">silence is gold</div>');		
		}	
	});
	$('button[name=submit]').click(function(){
		if($('#oldPassword').val() === ''){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please type your current password\
						</div>');
			$('#oldPassword').focus();

			return false;
		}
		if($('#newPassword').val() === ''){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please type your new password\
						</div>');
			$('#newPassword').focus();

			return false;
		}
		if($('#repeatPassword').val() === ''){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please repeat your new password\
						</div>');
			$('#repeatPassword').focus();

			return false;
		}
		if($('#oldPassword').val() === $('#newPassword').val()){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please choose a new password\
						</div>');
			$('#newPassword').val('');
			$('#repeatPassword').val('');
			$('#newPassword').focus();

			return false;
		}
		if($('#newPassword').val() !== $('#repeatPassword').val()){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> the new passwords don\'t match\
						</div>');
			$('#newPassword').val('');
			$('#repeatPassword').val('');
			$('#newPassword').focus();

			return false;
		}
		if($('#oldPassword').val().length < 8){	
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> your current password is incorrect\
						</div>');
			$('#oldPassword').val('');
			$('#oldPassword').focus();

			return false;
		}
		if($('#newPassword').val().length < 8){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> your password must be at least\
						8 chars long</div>');
			$('#newPassword').val('');
			$('#repeatPassword').val('');
			$('#newPassword').focus();

			return false;
		}
	});
});
