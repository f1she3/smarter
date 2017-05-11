<?php

	// Checks if the $user and $contact are friends or not
	function is_friend($user, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT contact FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND validate = 1');
		mysqli_stmt_bind_param($query, 'ssss', $user, $contact, $user, $contact);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $r);
		$result = mysqli_stmt_fetch($query);
		if(!empty($result)){
			return true;
		
		}else{
			return false;
		}
	}
	function new_friend_req($sender, $contact, $message, $attempts){
		$mysqli = get_link();
		$date = date('d/m Y');
		$query = mysqli_prepare($mysqli, 'INSERT INTO friends (sender, contact, message, date, attempts) VALUES (?, ?, ?, NOW(), ?)');
		mysqli_stmt_bind_param($query, 'sssi', $sender, $contact, $message, $attempts);
		mysqli_stmt_execute($query);
	}
	function attempts($sender, $contact, $attempts){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT attempts FROM friends WHERE BINARY sender = ? AND BINARY contact = ? AND attempts = ?');
		mysqli_stmt_bind_param($query, 'ssi', $sender, $contact, $attempts);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $r);
		$result = mysqli_stmt_fetch($query);
		
		return $result;
	}
	function is_asked($sender, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT sender, contact, message FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND validate = 0');
		mysqli_stmt_bind_param($query, 'ssss', $sender, $contact, $contact, $sender);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $s, $c, $m);
		while(mysqli_stmt_fetch($query)){
			if($s == $sender){
				$s = '';
			}
		}
		$result = array(
			'sender' 	=> $s,
			'message' 	=> $m
		);
		
		return $result;
	}
	function answer_friend_req($sender, $contact, $num){
		$mysqli = get_link();
		if($num == 1){
			// Set attempts to 0 when a friend is accepted, in order to know the exact number of friend an user has, it's impossible to know with multiple attempts
			$query = mysqli_prepare($mysqli, 'UPDATE friends SET validate = ?, attempts = 0 WHERE (BINARY sender = ?
				OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND validate != 2 AND (attempts = 1 OR attempts = 2)');
			mysqli_stmt_bind_param($query, 'issss', $num, $sender, $contact, $contact, $sender);
			mysqli_stmt_execute($query);
		
		}else{
			$query = mysqli_prepare($mysqli, 'UPDATE friends SET validate = ? WHERE (BINARY sender = ? OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND validate != 2 AND (attempts = 1 OR attempts = 2)');
			mysqli_stmt_bind_param($query, 'issss', $num, $sender, $contact, $contact, $sender);
			mysqli_stmt_execute($query);
		}
	}
	function friend_answer($sender, $contact, $num, $validate){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE (BINARY sender = ? AND BINARY contact = ?) AND attempts = ? AND validate = ?');
		mysqli_stmt_bind_param($query, 'ssii', $sender, $contact, $num, $validate);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $v);
		while(mysqli_stmt_fetch($query));

		return $v;
	}	
	// Views the friend request
	function view_req($sender, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND viewed != 2');
		mysqli_stmt_bind_param($query, 'ssss', $sender, $contact, $sender, $contact);
		mysqli_stmt_execute($query);
		$i = 0;
		while(mysqli_stmt_fetch($query)){
			$i++;
		}
		if($i > 0){
			$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 0');
			mysqli_stmt_bind_param($query, 'ss', $contact, $sender);
			mysqli_stmt_execute($query);
			$i = 0;
			while(mysqli_stmt_fetch($query)){
				$i++;
			}
			if($i > 0){
				$query = mysqli_prepare($mysqli, 'UPDATE friends SET viewed = 1 WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 0');
				mysqli_stmt_bind_param($query, 'ss', $contact, $sender);
				mysqli_stmt_execute($query);
			
			}else{
				$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 1');
				mysqli_stmt_bind_param($query, 'ss', $sender, $contact);
				mysqli_stmt_execute($query);
				$i = 0;
				while(mysqli_stmt_fetch($query)){
					$i++;
				}
				if($i > 0){
					$query = mysqli_prepare($mysqli, 'UPDATE friends SET viewed = 2 WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 1');
					mysqli_stmt_bind_param($query, 'ss', $sender, $contact);
					mysqli_stmt_execute($query);
				}
			}
		}
	}
	function display_private_chat($user, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT name FROM users WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $contact);
		mysqli_stmt_execute($query);
		$i = 0;
		while(mysqli_stmt_fetch($query)){
			$i++;
		}
		if($i > 0){
			$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND
				(BINARY contact = ? OR BINARY contact = ?) AND validate = 1');
			mysqli_stmt_bind_param($query, 'ssss', $_SESSION['name'], $contact, $_SESSION['name'], $contact);
			mysqli_stmt_execute($query);
			$i = 0;
			while(mysqli_stmt_fetch($query)){
				$i++;
			}
			if($i > 0){	
				$query = mysqli_prepare($mysqli, 'SELECT sender, contact, message, date FROM private WHERE (BINARY sender = ? OR BINARY sender = ?) AND
					(BINARY contact = ? OR BINARY contact = ?) AND is_typing = 0 ORDER BY id DESC LIMIT 80');
				mysqli_stmt_bind_param($query, 'ssss', $_SESSION['name'], $contact, $_SESSION['name'], $contact);
				mysqli_stmt_execute($query);
				mysqli_stmt_bind_result($query, $s, $c, $m, $d);
				$i = 0;
				while(mysqli_stmt_fetch($query)){
					$month = substr($d, 5, 2);
					$day = substr($d, 8, 2);
					$hour = substr($d, 11, 8);
					$m = bb_decode($m);
					echo "<span class=\"name\">
							<strong>
								".$s."
							</strong>
						</span> : 
						<div class=\"pull-right\">
							à ".$hour." le ".$day."/".$month."
						</div>
						<span>
							".$m."
						</span><hr>";
					$i++;
				}
				if($i > 0){
					$query = mysqli_prepare($mysqli, 'SELECT message FROM private WHERE BINARY sender = ? AND BINARY contact = ?');
					mysqli_stmt_bind_param($query, 'ss', $contact, $user);
					mysqli_stmt_execute($query);
					mysqli_stmt_bind_result($query, $m);
					while(mysqli_stmt_fetch($query));
					if(!empty($m)){
						$query = mysqli_prepare($mysqli, 'SELECT sender FROM private WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 0');
						mysqli_stmt_bind_param($query, 'ss', $contact, $user);
						mysqli_stmt_execute($query);
						mysqli_stmt_bind_result($query, $v);
						while(mysqli_stmt_fetch($query));
						if(!empty($v)){
							$query = mysqli_prepare($mysqli, 'UPDATE private SET viewed = 1 WHERE BINARY sender = ? AND BINARY contact = ?');
							mysqli_stmt_bind_param($query, 'ss', $contact, $user);
							mysqli_stmt_execute($query);
						}
					}
				
				}else{
					echo "<h4 class=\"text-center\">Aucun message privé ...</h4>";
				}

			}else{
				echo "<h4 class=\"text-center\">
						<a href=\"".$_SESSION['host'].constant('BASE_URL')."private&user=".$contact."\">
							votre contact n'est plus ami avec vous, veuillez rafraichir la page
						</a>
					</h4>";
			}

		}else{
			echo "<h4 class=\"text-center\">
					<a href=\"".$_SESSION['host'].constant('BASE_URL')."private&user=".$contact."\">
						votre contact n'est plus disponible, veuillez rafraichir la page
					</a>
				</h4>";
		}
	}
	function send_private_message($user, $contact, $message){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO private (sender, contact, message, date) VALUES (?, ?, ?, NOW())');
		mysqli_stmt_bind_param($query, 'sss', $_SESSION['name'], $contact, $message);
		mysqli_stmt_execute($query);
	}
