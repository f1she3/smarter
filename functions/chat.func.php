<?php

	function display_chat(){
		$mysqli = get_link();
		$query = mysqli_query($mysqli, "SELECT sender, message, date FROM chat WHERE is_typing = 0 ORDER BY id DESC LIMIT 80");
		$e = 0;
		while($m = mysqli_fetch_assoc($query)){
			$m['message'] = bb_decode($m['message']);
			$month = substr($m['date'], 5, 2);
			$day = substr($m['date'], 8, 2);
			$hour = substr($m['date'], 11, 8);
			if($m['sender'] != $_SESSION['name']){
				echo "<span class=\"name\">
						<a href=\"/?page=private&user=".$m['sender']."\" target=\"_blank\">
							".$m['sender']."
						</a>
						:
					</span> ";	
				
			}else{ 
				echo "<span class=\"name\">
						<strong>
							".$m['sender']."
						</strong>
						:
					</span>";
				}
			echo "<div class=\"pull-right\">
					à ".$hour." le ".$day."/".$month."
				</div>
				<span>
					".$m['message']."
				</span><hr>";
			$e++;
		}
	}
	function send_message($message){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO chat (sender, message, date) VALUES (?, ?, NOW())');
		mysqli_stmt_bind_param($query, 'ss', $_SESSION['name'], $message);
		mysqli_stmt_execute($query);
	}
