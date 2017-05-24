<?php

	require_once '../functions/init.php';
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		if(is_logged()){
			if(!is_mute($_SESSION['name'])){
				if(isset($_POST['message']) && !empty($_POST['message']) && strlen($_POST['message']) <= 350){
					$message = $_POST['message'] = secure($_POST['message']);
					$req = mysqli_prepare($mysqli, 'DELETE FROM chat WHERE sender = ? and is_typing = 1');
					mysqli_stmt_bind_param($req, 's', $_SESSION['name']);
					mysqli_stmt_execute($req);
					$query = mysqli_prepare($mysqli, 'INSERT INTO chat (sender, message, date) VALUES (?, ?, NOW())');
					mysqli_stmt_bind_param($query, 'ss', $_SESSION['name'], $message);
					mysqli_stmt_execute($query);
					
				}else{
					if(isset($_POST['is_typing'])){
						if(isset($_POST['empty'])){
							$req = mysqli_prepare($mysqli, 'DELETE FROM chat WHERE sender = ? and is_typing = 1');
							mysqli_stmt_bind_param($req, 's', $_SESSION['name']);
							mysqli_stmt_execute($req);
							
						}else{
							$req = mysqli_prepare($mysqli, 'SELECT sender FROM chat WHERE sender = ? and is_typing = 1');
							mysqli_stmt_bind_param($req, 's', $_SESSION['name']);
							mysqli_stmt_execute($req);
							$result = mysqli_stmt_fetch($req);
							if(empty($result)){
								$one = 1;
								$query = mysqli_prepare($mysqli, 'INSERT INTO chat (sender, is_typing) VALUES (?, ?)');
								mysqli_stmt_bind_param($query, 'ss', $_SESSION['name'], $one);
								mysqli_stmt_execute($query);
							}
						}
					}
				}
			}

		}else{
			redirect(2);
		}
		
	}else{
		redirect(2);
	}
