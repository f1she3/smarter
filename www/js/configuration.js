$(document).ready(function(){
	$("#o_password").keyup(function(e){
		if(e.keyCode != 13){
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$('.errors-block').html("<div class='alert alert-danger invisible'>a</div>");		
		}	
	})
	$("#n_password").keyup(function(e){
		if(e.keyCode != 13){
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$('.errors-block').html("<div class='alert alert-danger invisible'>a</div>");		
		}	
	})
	$("#r_password").keyup(function(e){
		if(e.keyCode != 13){
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$('.errors-block').html("<div class='alert alert-danger invisible'>a</div>");		
		}	
	})
	$("button[name=submit]").click(function(){
		if($("#o_password").val() != ""){
			if($("#n_password").val() != ""){
				if($("#r_password").val() != ""){
					if($("#o_password").val() != $("#n_password").val()){
						if($("#n_password").val() == $("#r_password").val()){
							if($("#o_password").val().length >= 6){	
								if($("#n_password").val().length >= 6){
									return true;
								
								}else{
									$(".errors").removeClass("alert alert-danger");
									$(".errors").addClass("hidden");
									$(".errors-block").html("<div class='alert alert-danger text-center'>\
																<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> le nouveau mot de passe est trop court\
															</div>");
									$("#n_password").val("");
									$("#r_password").val("");
									$("#n_password").focus();
									return false;
								}
							
							}else{	
								$(".errors").removeClass("alert alert-danger");
								$(".errors").addClass("hidden");
								$(".errors-block").html("<div class='alert alert-danger text-center'>\
															<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> l'ancien mot de passe est incorrect\
														</div>");
								$("#o_password").val("");
								$("#o_password").focus();
								return false;
							}

						}else{
							$(".errors").removeClass("alert alert-danger");
							$(".errors").addClass("hidden");
							$(".errors-block").html("<div class='alert alert-danger text-center'>\
														<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> les deux mots de passe ne correspondent pas\
													</div>");
							$("#n_password").val("");
							$("#r_password").val("");
							$("#n_password").focus();
							return false;
						}
					
					}else{	
						$(".errors").removeClass("alert alert-danger");
						$(".errors").addClass("hidden");
						$(".errors-block").html("<div class='alert alert-danger text-center'>\
													<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez choisir un mot de passe différent de l\'ancien\
												</div>");
						$("#n_password").val("");
						$("#r_password").val("");
						$("#n_password").focus();
						return false;
					}
				
				}else{
					$(".errors").removeClass("alert alert-danger");
					$(".errors").addClass("hidden");
						$(".errors-block").html("<div class='alert alert-danger text-center'>\
													<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez répéter votre mot de passe\
												</div>");
					$("#r_password").focus();
					return false;
				}
			
			}else{		
				$(".errors").removeClass("alert alert-danger");
				$(".errors").addClass("hidden");
				$(".errors-block").html("<div class='alert alert-danger text-center'>\
											<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir votre nouveau votre mot de passe\
										</div>");
				$("#n_password").focus();
				return false;
			}
		
		}else{		
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$(".errors-block").html("<div class='alert alert-danger text-center'>\
										<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir votre ancien votre mot de passe\
									</div>");
			$("#o_password").focus();
			return false;
		}
	})
})
