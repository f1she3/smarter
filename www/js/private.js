$(document).ready(function(){
	var i = 1;
	// Uses to know if it's a message or an accept / remove action 
	var y = 0;
	var contact = $("#contact_name").val();
	getPrivateMessages();
	$("textarea[name=message]").keypress(function(e){
		if(e.keyCode == 13){
			if($("textarea[name=message]").val() != ""){
				i = 1;
				$("form").submit();
			}
			
			return false;
		}
	})
	$("textarea[name=message]").keyup(function(x){
		if($("textarea[name=message]").val() == ""){
			if(x.keyCode != 13 && y != 0){
				y = 0;
				$.post("/ajax/s_private.php", {is_typing:1, empty:1, contact:contact}, function(writing){
					$(".messages").html(writing);
				})	
				getPrivateMessages();
			}
		
		}else{
			if(y == 0){
				$.post("/ajax/s_private.php", {contact:contact, is_typing:1}, function(writing){
					$(".messages").html(writing);
				})	
				getPrivateMessages();
			}
			y++;
		}
	})
	$("textarea[name=send_req]").keypress(function(e){
		if(e.keyCode == 13){
			if(e.shiftKey == false){
				if($("textarea[name=send_req]").val() != ""){
					$('button[name=send]').click();	
				}
				
				return false;
			}
		}
	})
	// Do not reload the page if click on button + empty textarea
	$("button[name=submit]").click(function(){
		$("textarea").focus();
		$("form").submit();
		
		return false;
	})
	$("button[name=accept]").click(function(){
		i = 0;
	})
	$("button[name=refuse]").click(function(){
		i = 0;
	})
	$("button[name=send]").click(function(){
		i = 0;
	})
	$("form").submit(function(){
		if(i == 1){
			if($("textarea[name=message]").val() != ""){
				var message = $("textarea[name=message]").val();
				$.post("/ajax/s_private.php",{message:message, contact:contact}, function(donnees){
					$(".messages").html(donnees);
				});
				$("textarea[name=message]").val("");
			}
			$("textarea[name=message]").focus();
			getPrivateMessages();

			return false;
					
		}else{
			if($("textarea[name=send_req]").val() != ""){
				return true;
			
			}else{
				$("textarea[name=send_req]").focus();
				return false;
			}
		}
	})
	function getPrivateMessages(){
		$.post("/ajax/get_private.php", {contact:contact}, function(data){
			$(".messages").html(data);
		})
	}
	
	setInterval(getPrivateMessages, 900);
})
