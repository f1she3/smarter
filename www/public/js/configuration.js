$(document).ready(function(){
	$('#oldPassword').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger invisible">a</div>');		
		}	
	})
	$('#newPassword').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger invisible">a</div>');		
		}	
	})
	$('#repeatPassword').keyup(function(e){
		if(e.keyCode != 13){
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger invisible">a</div>');		
		}	
	})
	$('button[name=submit]').click(function(){
		if($('#oldPassword').val() != ''){
			if($('#newPassword').val() != ''){
				if($('#repeatPassword').val() != ''){
					if($('#oldPassword').val() != $('#newPassword').val()){
						if($('#newPassword').val() == $('#repeatPassword').val()){
							if($('#oldPassword').val().length >= 8){	
								if($('#newPassword').val().length >= 8){
									return true;
								
								}else{
									$('.errors').removeClass('alert alert-danger');
									$('.errors').addClass('hidden');
									$('.errors-block').html('<div class="alert alert-danger text-center">\
																<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> the new password is too short (8 chars min)\
															</div>');
									$('#newPassword').val('');
									$('#repeatPassword').val('');
									$('#newPassword').focus();
									return false;
								}
							
							}else{	
								$('.errors').removeClass('alert alert-danger');
								$('.errors').addClass('hidden');
								$('.errors-block').html('<div class="alert alert-danger text-center">\
															<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> the old password is incorrect\
														</div>');
								$('#oldPassword').val('');
								$('#oldPassword').focus();
								return false;
							}

						}else{
							$('.errors').removeClass('alert alert-danger');
							$('.errors').addClass('hidden');
							$('.errors-block').html('<div class="alert alert-danger text-center">\
														<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> the new passwords don\'t match\
													</div>');
							$('#newPassword').val('');
							$('#repeatPassword').val('');
							$('#newPassword').focus();
							return false;
						}
					
					}else{	
						$('.errors').removeClass('alert alert-danger');
						$('.errors').addClass('hidden');
						$('.errors-block').html('<div class="alert alert-danger text-center">\
													<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please choose a new password\
												</div>');
						$('#newPassword').val('');
						$('#repeatPassword').val('');
						$('#newPassword').focus();
						return false;
					}
				
				}else{
					$('.errors').removeClass('alert alert-danger');
					$('.errors').addClass('hidden');
						$('.errors-block').html('<div class="alert alert-danger text-center">\
													<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please repeat your new password\
												</div>');
					$('#repeatPassword').focus();
					return false;
				}
			
			}else{		
				$('.errors').removeClass('alert alert-danger');
				$('.errors').addClass('hidden');
				$('.errors-block').html('<div class="alert alert-danger text-center">\
											<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please type your new password\
										</div>');
				$('#newPassword').focus();
				return false;
			}
		
		}else{		
			$('.errors').removeClass('alert alert-danger');
			$('.errors').addClass('hidden');
			$('.errors-block').html('<div class="alert alert-danger text-center">\
										<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> please type your old password\
									</div>');
			$('#oldPassword').focus();
			return false;
		}
	})
})
