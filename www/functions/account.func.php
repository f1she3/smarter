<?php
	
function user_infos($username){
	$mysqli = get_link();
	// Message counter
	$query = mysqli_prepare($mysqli, 'SELECT id FROM chat WHERE BINARY sender = ?');
	mysqli_stmt_bind_param($query, 's', $username);
	mysqli_stmt_execute($query);
	$i = 0;
	while(mysqli_stmt_fetch($query)){
		$i++;
	}
	$result['public_message_count'] = $i;
	$query = mysqli_prepare($mysqli, 'SELECT id FROM private WHERE BINARY sender = ?');
	mysqli_stmt_bind_param($query, 's', $username);
	mysqli_stmt_execute($query);
	$e = 0;
	while(mysqli_stmt_fetch($query)){
		$e++;
	}
	$result['private_message_count'] = $e;
	$result['message_count'] = $i + $e;
	// Friend counter	
	$query = mysqli_prepare($mysqli, 'SELECT id FROM friends WHERE (BINARY sender = ? OR BINARY contact = ?) AND validate = 1');
	mysqli_stmt_bind_param($query, 'ss', $username, $username);
	mysqli_stmt_execute($query);
	$x = 0;
	while(mysqli_stmt_fetch($query)){
		$x++;
	}
	$result['friend_count'] = $x;
	$query = mysqli_prepare($mysqli, 'SELECT rank, regDate FROM users WHERE BINARY username = ?');
	mysqli_stmt_bind_param($query, 's', $username);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $result['rank'], $result['reg_date']);
	mysqli_stmt_fetch($query);

	return $result;		
}
function display_user_infos($username, $flag){
	$mysqli = get_link();
	$query = mysqli_prepare($mysqli, 'SELECT username FROM users WHERE BINARY username = ?');
	mysqli_stmt_bind_param($query, 's', $username);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $name);
	mysqli_stmt_fetch($query);
	$ranks = get_rank_list();
	$ret_user_infos = user_infos($username);
	$ret_reg_date = date_create($ret_user_infos['reg_date']);
	$ret_reg_date = date_format($ret_reg_date, '\l\e j/m Y');
	if($ret_user_infos['message_count'] > 1){
		$msg_text = 'Messages postés : ';
	
	}else{
		$msg_text = 'Message posté : ';
	}
	if($ret_user_infos['friend_count'] > 1){
		$friend_text = 'Amis : ';
	
	}else{
		$friend_text = 'Ami : ';
	}
	// $flag :  boolean var used to know if we display the infos about 
	// the current user, or just about another user
	if(!$flag){
		echo "<div class=\"page-header\">
				<h3 class=\"text-center\">Informations</h3>
			</div>
			<h3 class=\"text-center\">".$username."</h3>
			<pre style=\"border-radius:10px\">
				<ul>
					<li style=\"font-size:18px\">Pseudo : ".$username."</li>
					<li style=\"font-size:18px\">Inscription : ".$ret_reg_date."</li>
					<li style=\"font-size:18px\">".$friend_text." ".$ret_user_infos['friend_count']."</li>
					<li style=\"font-size:18px\">".$msg_text." ".$ret_user_infos['public_message_count']."</li>
					<li style=\"font-size:18px\">Grade : ".$ranks[$ret_user_infos['rank']]."</li>
				</ul>
			</pre>";
	
	}else{
		echo "<div class=\"page-header\">
				<h3 class=\"text-center\">Informations</h3>
			</div>
			<h3 class=\"text-center\">".$username."</h3>
			<pre style=\"border-radius:10px\">
				<ul>
					<li style=\"font-size:18px\">Pseudo : ".$username."</li>
					<li style=\"font-size:18px\">Inscription : ".$ret_reg_date."</li>
					<li style=\"font-size:18px\">".$friend_text." ".$ret_user_infos['friend_count']."</li>
					<li style=\"font-size:18px\">".$msg_text." ".$ret_user_infos['message_count']."</li>
					<li style=\"font-size:18px\">Grade : ".$ranks[$ret_user_infos['rank']]."</li>
					<li style=\"font-size:18px\">Adresse IP actuelle : ".$_SERVER['REMOTE_ADDR']."</li>
				</ul>
			</pre>";
	}
}
