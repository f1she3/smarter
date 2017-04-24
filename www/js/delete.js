$(document).ready(function(){
	$("input[type=password]").keyup(function(e){
		if(e.keyCode != 13){
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$('.errors-block').html("<div class='alert alert-danger invisible'>a</div>");		
		}	
	})
	$("button[name=submit]").click(function(){
		if($("input[type=password]").val() != ''){
			if($("input[type=password]").val().length >= 6){
				return true;
			
			}else{
				$("input[type=password]").val('');
				$("input[type=password]").focus();
				$(".errors").removeClass("alert alert-danger");
				$(".errors").addClass("hidden");
				$(".errors-block").html("<div class='alert alert-danger text-center'>\
											<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> le mot de passe est incorrect\
										</div>");
				
				return false;
			}
		
		}else{
			$("input[type=password]").val('');
			$("input[type=password]").focus();
			$(".errors").removeClass("alert alert-danger");
			$(".errors").addClass("hidden");
			$(".errors-block").html("<div class='alert alert-danger text-center'>\
										<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> veuillez saisir votre mot de passe\
									</div>");
				
			return false;
		}
	})
})
