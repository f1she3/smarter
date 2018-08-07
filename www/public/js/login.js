$(document).ready(function(){
	var socket = io.connect('http://127.0.0.1:8080');
	$('form').submit(function(event){
		event.preventDefault();
		if($('#name_log').val() === ''){
			$('#name_log').focus();
			return false;
		}
		if($('#password_log').val() === ''){
			$('#password_log').focus();
			return false;
		}
		socket.emit('login', {
			name	: $('#name_log').val(),
			password : $('#password_log').val()
		})
	})
/*
	$('#name_log').keyup(function(e){
		if(e.keyCode != 13){
			// Removes the errors when the user types something
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html("<div class='alert alert-danger invisible'>a</div>");
		}	
	})
	$('#password_log').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors').removeClass('alert alert-danger center-block');
			$('.errors').addClass('hidden');
			$('.errors-block').html("<div class='alert alert-danger invisible'>a</div>");
		}	
	})
	
	$('#submit_log').click(function(){
		if($('#name_log').val() != ''){
			if($('#password_log').val() != ''){
				if($('#password_log').val().length >= 6){
					if($('#name_log').val().length >= 4){
						return true;
					
					}else{
						$('#name_log').val('');
						$('#name_log').focus();
						$('.errors').removeClass('alert alert-danger');
						$('.errors').addClass('hidden');
						$('.errors-block').html("<div class='alert alert-danger center-block text-center'>\n
													<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> le nom d'utilisateur est trop court\
												</div>");
				
						return false;
					}
				
				}else{
					$('#password_log').val('');
					$('#password_log').focus();
					$('.errors').removeClass('alert alert-danger');
					$('.errors').addClass('hidden');
					$('.errors-block').html("<div class='alert alert-danger center-block text-center'>\
												<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> le mot de passe est trop court\
											</div>");
				
					return false;
				}
			
			}else{
				$('#password_log').focus();
				$('.errors').removeClass('alert alert-danger');
				$('.errors').addClass('hidden');
				$('.errors-block').html("<div class='alert alert-danger center-block text-center'>\
											<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir votre mot de passe\
										</div>");
				
				return false;
			}
			
		}else{
			$('#name_log').focus();
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html("<div class='alert alert-danger text-center'>\
										<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir votre pseudo\
									</div>");
		
			return false;
		}			
	})	
*/
})
