$(document).ready(function(){
	$("#name_reg").keyup(function(e){
		var name = $("#name_reg").val();
		$.post('/ajax/is_free.php',{name:name}, function(free_name){
			if(free_name != ""){
				$("#name_reg").focus();
				$(".errors").removeClass("alert alert-danger");
				$(".errors").addClass("hidden");
				$(".errors-block").html("<div class='alert alert-danger text-center'>\
											<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ce nom d\'utilisateur est déjà utilisé\
										</div>");
				return false;
			
			}else{
				if(e.keyCode != 13){
					$(".errors").removeClass("alert alert-danger");
					$(".errors").addClass("hidden");
					$(".errors-block").html("<div class='alert alert-danger invisible'>a</div>");
				}
			}
		})
	})
	$("#email_reg").keyup(function(e){
		var email = $("#email_reg").val();
		$.post('/ajax/is_free.php',{email:email}, function(free_email){
			if(free_email != ""){
				$("#email_reg").focus();
				$(".errors").removeClass("alert alert-danger");
				$(".errors").addClass("hidden");
				$(".errors-block").html("<div class='alert alert-danger text-center'>\
											<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> cette adresse email est déjà utilisée\
										</div>");
				return false;
			
			}else{
				if(e.keyCode != 13){
					$(".errors").removeClass("alert alert-danger");
					$(".errors").addClass("hidden");
					$(".errors-block").html("<div class='alert alert-danger invisible'>a</div>");
				}
			}
		})				
	})
	$("#password_reg").keyup(function(e){
		if(e.keyCode != 13){
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$(".errors-block").html("<div class='alert alert-danger invisible'>a</div>");
		}
	})	
	$("button[name=reg-submit]").click(function(){
		if($("#name_reg").val() != ""){
			if($("#email_reg").val() != ""){
				if($("#password_reg").val() != ""){
					if($("#r_password_reg").val() != ""){
						if($("#password_reg").val() == $("#r_password_reg").val()){
							if($("#name_reg").val().length >= 4){	
								if($("#password_reg").val().length >= 6){
									return true;
								
								}else{
									$(".errors").removeClass("alert alert-danger");
									$(".errors").addClass("hidden");
									$(".errors-block").html("<div class='alert alert-danger text-center'>\
																<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> votre mot de passe est trop court\
															</div>");
									$("#password_reg").val("");
									$("#r_password_reg").val("");
									$("#password_reg").focus();
									return false;
								}						
								
							}else{
								$(".errors").removeClass("alert alert-danger");
								$(".errors").addClass("hidden");
								$(".errors-block").html("<div class='alert alert-danger text-center'>\
															<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> le nom d\'utilisateur doit contenir au minimum 4 caractères\
														</div>");
								$("#name_reg").focus();
								return false;
							}
				
						}else{
							$(".errors").removeClass("alert alert-danger");
							$(".errors").addClass("hidden");
							$(".errors-block").html("<div class='alert alert-danger text-center'>\
														<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> les mots de passe ne correspondent pas\
													</div>");
							$("#password_reg").val("");
							$("#r_password_reg").val("");
							$("#password_reg").focus();
							return false;
						}
						
					}else{
						$(".errors").removeClass("alert alert-danger");
						$(".errors").addClass("hidden");
						$(".errors-block").html("<div class='alert alert-danger text-center'>\
													<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez répéter votre mot de passe\
												</div>");
						$("#r_password_reg").focus();
						return false;
					}	
							
				}else{
					$(".errors").removeClass("alert alert-danger");
					$(".errors").addClass("hidden");
					$(".errors-block").html("<div class='alert alert-danger text-center'>\
												<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir votre mot de passe\
											</div>");
					$("#password_reg").focus();
					return false;
				}
				
			}else{
				$(".errors").removeClass("alert alert-danger");
				$(".errors").addClass("hidden");
				$(".errors-block").html("<div class='alert alert-danger text-center'>\
											<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir une adresse email\
										</div>");
				$("#email_reg").focus();
				return false;
			}
			
		}else{
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$(".errors-block").html("<div class='alert alert-danger text-center'>\
										<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir un pseudo\
									</div>");
			$("#name_reg").focus();
			return false;
		}	
	}) 
})
