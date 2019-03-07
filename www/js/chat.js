$(document).ready(function(){
	var i = 1;
	// Uses to know if it's a message or an accept / remove action 
	var y = 0;
	getMessages();
	$("textarea[name=message]").keypress(function(e){
		if(e.keyCode == 13){
			if($("textarea[name=message]").val() != ""){
				i = 1;
				$("form").submit();
			}
			
			return false;
		}
	});
	$("textarea[name=message]").keyup(function(x){
		if($("textarea[name=message]").val() == ""){
			if(x.keyCode != 13 && y != 0){
				y = 0;
				$.post("/ajax/send.php", {is_typing:1, empty:1}, function(writing){
					$(".messages").html(writing);
				});
				getMessages();
			}
		}else{
			if(y == 0){
				$.post("/ajax/send.php", {is_typing:1}, function(writing){
					$(".messages").html(writing);
				});
				getMessages();
			}
			y++;
		}
	});
	$("textarea[name=send_req]").keypress(function(e){
		if(e.keyCode == 13){
			if(e.shiftKey == false){
				if($("textarea[name=send_req]").val() != ""){
					$('button[name=send]').click();	
				}
				
				return false;
			}
		}
	});
	$("button[name=submit]").click(function(){
		$("textarea").focus();
		$("form").submit();
		
		return false;
	});
	$("form").submit(function(){
		if($("textarea[name=message]").val() != ""){
			var message = $("textarea[name=message]").val();
			$.post("/ajax/send.php",{message:message}, function(data){
				$(".messages").html(data);
			});
			$("textarea[name=message]").val("");
		}
		$("textarea[name=message]").focus();
		getMessages();

		return false;
	});
	function getMessages(){
		$.post("/ajax/get.php", function(data){
			$(".messages").html(data);
		});
	}
	
	setInterval(getMessages, 900);
});
