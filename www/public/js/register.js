$(document).ready(function(){
	$('#usernameReg').keyup(function(e){
		var name = $('#usernameReg').val();
	});
	$('#passwordReg').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger invisible">a</div>');
		}
	});
	$('button[name=submit]').click(function(){
		if($('#usernameReg').val() != ''){
			if($('#passwordReg').val() != ''){
				if($('#rPasswordReg').val() != ''){
					if($('#passwordReg').val() == $('#rPasswordReg').val()){
						if($('#usernameReg').val().length >= 4){	
							if($('#passwordReg').val().length >= 6){
								return true;
							
							}else{
								$('.errors').removeClass('alert alert-danger');
								$('.errors').addClass('hidden');
								$('.errors-block').html('<div class="alert alert-danger text-center">\
															<span class="glyphicon glyphicon-exclamation-sign"></span> your password must be at least 8 chars long\
														</div>');
								$('#passwordReg').val('');
								$('#rPasswordReg').val('');
								$('#passwordReg').focus();

								return false;
							}						
						}else{
							$('.errors').removeClass('alert alert-danger');
							$('.errors').addClass('hidden');
							$('.errors-block').html('<div class="alert alert-danger text-center">\
														<span class="glyphicon glyphicon-exclamation-sign"></span> the username must be at least 4 chars long\
													</div>');
							$('#usernameReg').focus();

							return false;
						}
					}else{
						$('.errors').removeClass('alert alert-danger');
						$('.errors').addClass('hidden');
						$('.errors-block').html('<div class="alert alert-danger text-center"><span class="glyphicon glyphicon-exclamation-sign"></span> please retype your password</div>');
						$('#passwordReg').val('');
						$('#rPasswordReg').val('');
						$('#passwordReg').focus();

						return false;
					}
				}else{
					$('.errors').removeClass('alert alert-danger');
					$('.errors').addClass('hidden');
					$('.errors-block').html('<div class="alert alert-danger text-center">\
												<span class="glyphicon glyphicon-exclamation-sign"></span> please retype your password\
											</div>');
					$('#rPasswordReg').focus();

					return false;
				}	
			}else{
				$('.errors').removeClass('alert alert-danger');
				$('.errors').addClass('hidden');
				$('.errors-block').html('<div class="alert alert-danger text-center">\
											<span class="glyphicon glyphicon-exclamation-sign"></span> please choose a password\
										</div>');
				$('#passwordReg').focus();

				return false;
			}
		}else{
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
										<span class="glyphicon glyphicon-exclamation-sign"></span> please choose an username\
									</div>');
			$('#usernameReg').focus();

			return false;
		}	
	});
});
