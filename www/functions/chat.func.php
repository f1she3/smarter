<?php

function display_chat(){
	$mysqli = get_link();
	$query = mysqli_query($mysqli, 'SELECT sender, message, date FROM chat WHERE isTyping = 0 ORDER BY id DESC LIMIT 80');
	$e = 0;
	while($infos = mysqli_fetch_assoc($query)){
		$date = date_create($infos['date']);
		$date = date_format($date, 'G:i:s, \l\e j/m Y');
		$infos['message'] = bb_decode($infos['message']);
		if($infos['sender'] != $_SESSION['username']){
			echo "<span class=\"username\">
					<a href=\"".constant('BASE_URL')."private&user=".$infos['sender']."\" target=\"_blank\">
						".$infos['sender']."
					</a>
					:
				</span> ";
			
		}else{ 
			echo "<span class=\"username\">
					<strong>
						".$infos['sender']."
					</strong>
					:
				</span>";
		}

		echo "<div class=\"pull-right\">
				".$date." 
			</div>
			<span>
				".$infos['message']."
			</span><hr>";
		$e++;
	}
}
function send_message($message){
	$mysqli = get_link();
	$query = mysqli_prepare($mysqli, 'INSERT INTO chat (sender, message, date) VALUES (?, ?, NOW())');
	mysqli_stmt_bind_param($query, 'ss', $_SESSION['username'], $message);
	mysqli_stmt_execute($query);
}
