$(document).ready(function(){
	$('#usernameLog').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors-block').addClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger invisible">silence is gold</div>');		
		}	
	});
	$('#passwordLog').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors-block').addClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger invisible">silence is gold</div>');		
		}	
	});
	$('button[name=submit]').click(function(){
		if($('#usernameLog').val() === ''){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
						<span class="glyphicon glyphicon-exclamation-sign"></span> please type your username\
							</div>');
			$('#usernameLog').focus();

			return false;
		}else if($('#passwordLog').val() == ''){
			$('.errors-block').removeClass('invisible');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
						<span class="glyphicon glyphicon-exclamation-sign"></span> please type your password\
							</div>');
			$('#passwordLog').focus();

			return false;
		}
	});
});
