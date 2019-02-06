$(document).ready(function(){
	$('button[name=submit]').click(function(){
		if($('#usernameLog').val() == ''){
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
						<span class="glyphicon glyphicon-exclamation-sign"></span> please type your username\
							</div>');
			$('#usernameLog').focus();

			return false;
		}else if($('#passwordLog').val() == ''){
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
						<span class="glyphicon glyphicon-exclamation-sign"></span> please type your password\
							</div>');
			$('#passwordLog').focus();

			return false;
		}
	})
});
